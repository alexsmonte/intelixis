(function () {
    // Registry de configurações do IMask (apenas tipos não-monetários)
    const maskRegistry = {
        'nome': {
            mask: /^[a-zA-ZÀ-ÿ\s]*$/
        },
        'cpf': {
            mask: '000.000.000-00'
        },
        'cnpj': {
            mask: '00.000.000/0000-00'
        },
        'documento': {
            mask: [
                { mask: '000.000.000-00' },
                { mask: '00.000.000/0000-00' }
            ],
            dispatch: function (appended, dynamicMasked) {
                const number = (dynamicMasked.value + appended).replace(/\D/g, '');
                return number.length <= 11
                    ? dynamicMasked.compiledMasks[0]
                    : dynamicMasked.compiledMasks[1];
            }
        },
        'telefone': {
            mask: '(00) 0000-0000'
        },
        'celular': {
            mask: '(00) 00000-0000'
        },
        'cep': {
            mask: '00000-000'
        },
        'uf': {
            mask: /^[A-Za-z]{0,2}$/,
            prepare: str => str.toUpperCase(),
            commit: function (value, masked) {
                masked._value = value.toUpperCase();
            }
        },
        'numero': {
            mask: /^[0-9]*$/
        }
    };

    /**
     * Máscara monetária customizada — estilo calculadora (direita para esquerda).
     *
     * Comportamento:
     *  - Digitar "5"       → "0,05"
     *  - Digitar "0"       → "0,50"
     *  - Digitar "0"       → "5,00"
     *  - Digitar "1"       → "50,01"  (continua empurrando para a esquerda)
     *  - Foco              → remove pontos de milhar e seleciona tudo
     *  - Blur              → garante terminação em ",XX"
     *  - Valor inicial BD  → converte ponto decimal para vírgula ("150.00" → "150,00")
     */
    function aplicarMascaraMonetariaCustom(el) {
        if (el._customMonetaryInitialized) return;
        el._customMonetaryInitialized = true;

        function formatarValor(val) {
            let str = String(val).trim();

            // Valor vindo do banco com ponto decimal (ex: "10.5" ou "10.50"):
            // converte via parseFloat para não perder o zero fracionário
            if (/^\d+\.\d*$/.test(str)) {
                str = String(Math.round(parseFloat(str) * 100));
            } else {
                str = str.replace(/[^\d]/g, '');
            }

            let v = str;
            if (v === '') return '0,00';
            while (v.length < 3) v = '0' + v;
            const c = v.slice(-2);
            let i = v.slice(0, -2).replace(/^0+/g, '') || '0';
            if (i.length > 3) i = i.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return i + ',' + c;
        }

        // Foco: remove pontos de milhar e seleciona todo o conteúdo
        el.addEventListener('focus', () => {
            el.value = (el.value || '0,00').replace(/\./g, '');
            setTimeout(() => el.select(), 10);
        });

        // Input: formatação calculadora em tempo real (direita para esquerda)
        el.addEventListener('input', () => {
            if (el._monetaryLoop) return;
            const formatted = formatarValor(el.value);
            if (el.value !== formatted) {
                el.value = formatted;
                el._monetaryLoop = true;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el._monetaryLoop = false;
            }
        });

        // Blur: garante terminação em vírgula + 2 casas decimais
        el.addEventListener('blur', () => {
            let val = el.value || '0,00';
            if (!val.includes(',')) val += ',00';
            if (el.value !== val) {
                el.value = val;
                el.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });

        // Formata o valor inicial (ex: vindo do banco como "150.00" → "150,00")
        el.value = formatarValor(el.value);
    }

    /**
     * Inicializa a máscara correta em um elemento, roteando pelo tipo.
     */
    function inicializarMascara(el, maskType) {
        if (maskType === 'valor') {
            aplicarMascaraMonetariaCustom(el);
            return;
        }

        if (typeof IMask === 'undefined') {
            console.error('IMask não foi carregado.');
            return;
        }

        if (!maskRegistry[maskType]) {
            console.warn('Tipo de mask inválido ou não encontrado:', maskType);
            return;
        }

        if (el._imaskInstance) return; // já inicializado

        const maskInstance = IMask(el, maskRegistry[maskType]);
        el._imaskInstance = maskInstance;

        // Sincroniza o v-model do Vue quando o IMask aceita uma mudança
        maskInstance.on('accept', () => {
            if (el.value !== el._lastValue) {
                el._lastValue = el.value;
                el.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    }

    /**
     * Diretiva Vue 3 (v-mask) — usada em apps Vue com createApp().
     */
    const vueMaskDirective = {
        mounted(el, binding) {
            const maskType = binding.value;
            if (!maskType) return;
            inicializarMascara(el, maskType);
        },
        unmounted(el) {
            if (el._imaskInstance) {
                el._imaskInstance.destroy();
                delete el._imaskInstance;
            }
        }
    };

    // Expor globalmente
    window.VueMasks = {
        directive: vueMaskDirective,
        maskRegistry,
        aplicarMascaraMonetariaCustom
    };

    if (typeof module !== 'undefined') module.exports = window.VueMasks;

    // Inicialização automática robusta para inputs com v-mask fora de um app Vue (CakePHP SSR)
    function autoInit() {
        document.querySelectorAll('input[v-mask]').forEach(input => {
            const maskType = input.getAttribute('v-mask').replace(/['"]/g, '');
            if (maskType) inicializarMascara(input, maskType);
        });
    }

    if (document.readyState !== 'loading') {
        autoInit();
    } else {
        document.addEventListener('DOMContentLoaded', autoInit);
    }
});
