const { createApp, ref, onMounted } = Vue;

createApp({
    setup() {
        const isVidaasConnected = ref(false);
        const connectionError = ref(false);
        const errorMessage = ref('');
        const activeApiUrl = ref('');
        
        const isSigning = ref(false);
        const signSuccess = ref(false);

        // Tentar comunicar com o Assinador Serpro (REST Wrapper) na porta 8888.
        // Tentar comunicar com o Assinador Serpro
        const scanLocalApi = async () => {
            isVidaasConnected.value = false;
            connectionError.value = false;
            errorMessage.value = '';
            
            // Tentaremos HTTPS e HTTP puro, e as portas do Wrapper (8888) e do websocket legadas.
            const serversToTry = [
                'https://127.0.0.1:8888',
                'http://127.0.0.1:8888',
                'https://127.0.0.1:65056',
                'http://127.0.0.1:65056', 
                'http://localhost:8888'
            ];
            
            let foundUrl = null;

            for (let url of serversToTry) {
                try {
                    await axios.get(`${url}/`, { timeout: 1500 }).catch(e => {
                        if (e.code === 'ECONNREFUSED' || e.message === 'Network Error') {
                            throw e; // Recusou de verdade.
                        }
                        // Qualquer outra coisa como CORS significa servidor vivo.
                        return true;
                    });
                    
                    foundUrl = url;
                    break;
                } catch (err) {
                    continue; // Tenta o próximo
                }
            }
            
            if (foundUrl) {
                activeApiUrl.value = foundUrl;
                isVidaasConnected.value = true;
            } else {
                activeApiUrl.value = 'https://127.0.0.1:8888'; // fallback cego
                isVidaasConnected.value = false;
                connectionError.value = true;
                errorMessage.value = "Daemon local não conectou. Nenhum programa respondeu no localhost:8888. Confirme se o executor rodou e tente não usar HTTPS se ele não tiver chave SSL.";
            }
        };

        const startSignature = async () => {
            isSigning.value = true;
            errorMessage.value = '';

            try {
                // 1. Pede ao CakePHP preparar a assinatura (gera Hash do PDF original)
                const prepRes = await axios.post(window.AppConfig.urlPreparar, {}, {
                    headers: { 'X-CSRF-Token': window.AppConfig.csrfToken }
                });

                if (!prepRes.data.success) {
                    throw new Error(prepRes.data.message || 'Erro ao preparar o documento na base de dados.');
                }

                const hashToSign = prepRes.data.hash;
                let pkcs7Signature = '';

                // 2. Chama a API do Assinador Serpro passando o Hash
                try {
                    const signUrl = `${activeApiUrl.value}/assinar`;
                    
                    const signRes = await axios.post(signUrl, {
                        hash: hashToSign,
                        algorithm: 'SHA256'
                    });
                    
                    pkcs7Signature = signRes.data.signature || signRes.data.pkcs7 || signRes.data;
                    
                    if (!pkcs7Signature) {
                         throw new Error("O agente retornou um envelope vazio.");
                    }
                } catch(vidaasErr) {
                    console.error("Falha ao comunicar com o Assinador Serpro Desktop para gerar CMS:", vidaasErr);
                    throw new Error("Assinador Serpro recusou ou falhou na assinatura local. Detalhes: " + (vidaasErr.response?.data?.message || vidaasErr.message));
                }

                // 3. Envia o CMS/PKCS#7 para o Backend (Onde o mPDF deverá injetar sem quebrar)
                const conclRes = await axios.post(window.AppConfig.urlConcluir, {
                    assinatura_cms: pkcs7Signature
                }, {
                    headers: { 'X-CSRF-Token': window.AppConfig.csrfToken }
                });

                if (!conclRes.data.success) {
                    throw new Error(conclRes.data.message || 'Falha ao injetar a assinatura.');
                }

                signSuccess.value = true;

            } catch (err) {
                errorMessage.value = err.message || 'Erro ao concluir fluxo.';
            } finally {
                isSigning.value = false;
            }
        };

        onMounted(() => {
            setTimeout(scanLocalApi, 500);
        });

        return {
            isVidaasConnected,
            connectionError,
            errorMessage,
            activeApiUrl,
            isSigning,
            signSuccess,
            scanLocalApi,
            startSignature
        };
    }
}).mount('#app-assinatura-prof');
