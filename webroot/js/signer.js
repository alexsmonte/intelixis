/**
 * signer.js — Agente Assinador Local (porta 7777)
 */

let AGENT = 'https://127.0.0.1:7777';
const AGENT_ALT = 'https://localhost:7777';
const AGENT_TOKEN = 'assinador-d7f8a3b2e1c4';

function agentHeaders() {
    return {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + AGENT_TOKEN
    };
}

async function tentarConexao(url) {
    try {
        const r = await fetch(`${url}/ping`, {
            method: 'GET',
            mode: 'cors',
            signal: AbortSignal.timeout(1500)
        });
        return { ok: r.ok, url: url, erro: null };
    } catch (e) {
        return { ok: false, url: url, erro: e.message };
    }
}

async function verificarAgente() {
    let teste = await tentarConexao(AGENT);
    if (!teste.ok) {
        teste = await tentarConexao(AGENT_ALT);
        if (teste.ok) AGENT = AGENT_ALT;
    }
    return teste;
}

async function carregarCertificados() {
    const statusEl = document.getElementById('status');
    const certArea = document.getElementById('cert-area');
    const btnAssinar = document.getElementById('btn-assinar');

    if (statusEl) statusEl.innerHTML = `<span class="badge-status aguardando">⏳ Verificando agente...</span>`;

    const teste = await verificarAgente();

    if (!teste.ok) {
        if (statusEl) statusEl.innerHTML = `
            <div class="alert-agente">
                <span class="badge-status offline">● Agente bloqueado ou offline</span>
                <p>O navegador bloqueou a comunicação com o agente local.</p>
                <div style="font-size: 0.78rem; opacity: 0.8; margin: 8px 0; background: rgba(0,0,0,0.05); padding: 8px; border-radius: 6px;">
                   <strong>Erro:</strong> <code>${teste.erro}</code><br><br>
                   Se você consegue abrir <strong>https://127.0.0.1:7777/ping</strong> em uma nova aba mas aqui dá erro,
                   é bloqueio de CORS ou Mixed Content.
                </div>
                <a href="/downloads/assinador_setup.exe" class="btn-download">⬇ Baixar Agente</a>
                <button onclick="carregarCertificados()" class="btn-retry">🔄 Tentar novamente</button>
            </div>`;
        if (certArea) certArea.style.display = 'none';
        if (btnAssinar) btnAssinar.disabled = true;
        return;
    }

    if (statusEl) statusEl.innerHTML = `<span class="badge-status online">● Agente conectado</span>`;

    try {
        const data = await fetch(`${AGENT}/certificates`, {
            headers: agentHeaders()
        }).then(r => {
            if (r.status === 401) throw new Error('Token de autenticação inválido.');
            return r.json();
        });

        const certs = data.default ?? data.certificates ?? data;
        const sel = document.getElementById('cert-select');
        if (!sel) return;

        sel.innerHTML = '<option value="">— Selecione seu certificado —</option>';
        certs.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = `${c.subject} | ${c.type} | vence ${c.valid_to}`;
            sel.appendChild(opt);
        });

        if (certArea) certArea.style.display = '';
        if (btnAssinar) btnAssinar.disabled = false;

    } catch (err) {
        if (statusEl) statusEl.innerHTML = `<span class="badge-status erro">⚠ Erro ao carregar certificados: ${err.message}</span>`;
    }
}

async function assinar(documentId) {
    const certId = document.getElementById('cert-select')?.value;
    const statusEl = document.getElementById('status');
    const btnAssinar = document.getElementById('btn-assinar');
    const resultEl = document.getElementById('resultado');

    if (!certId) {
        alert('Selecione um certificado digital antes de continuar.');
        return;
    }

    if (btnAssinar) { btnAssinar.disabled = true; btnAssinar.textContent = '⏳ Assinando...'; }
    if (statusEl) statusEl.innerHTML = `<span class="badge-status aguardando">⏳ Processando assinatura...</span>`;

    try {
        // 0. Metadados do certificado selecionado
        const certs = await fetch(`${AGENT}/certificates`, {
            headers: agentHeaders()
        }).then(r => r.json());
        const selectedCert = (certs.default ?? certs.certificates ?? certs).find(c => c.id === certId);

        // 1. Pede hash ao backend CakePHP
        const baseUrl = (window.API_BASE || '/').replace(/\/$/, '');
        const hashRes = await fetch(`${baseUrl}/signatures/hash/${documentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrf()
            },
            body: JSON.stringify({
                certificate_name: selectedCert?.subject || '',
                certificate_id: selectedCert?.id || ''
            })
        }).then(r => {
            if (!r.ok) throw new Error('Erro de HTTP ' + r.status);
            return r.json();
        });

        if (!hashRes.hash) throw new Error(hashRes.message || 'Falha ao obter hash do documento.');
        const { hash, session_token } = hashRes;

        // 2. Assina no agente local com autenticação
        const signRes = await fetch(`${AGENT}/sign`, {
            method: 'POST',
            headers: agentHeaders(),
            body: JSON.stringify({ cert_id: certId, hash_b64: hash })
        }).then(r => {
            if (r.status === 401) throw new Error('Token de autenticação do agente inválido.');
            return r.json();
        });

        if (signRes.error) throw new Error('Agente: ' + signRes.error);
        const { signature, certificate } = signRes;

        // 3. Envia assinatura ao backend para salvar
        const finalRes = await fetch(`${baseUrl}/signatures/finalize`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrf()
            },
            body: JSON.stringify({ session_token, signature, certificate })
        }).then(r => {
            if (!r.ok) throw new Error('Erro de HTTP ' + r.status);
            return r.json();
        });

        if (finalRes.ok) {
            if (statusEl) statusEl.innerHTML = `<span class="badge-status online">✅ Assinado com sucesso!</span>`;
            if (resultEl) {
                resultEl.style.display = '';
                resultEl.innerHTML = finalRes.html || '';
            }
            if (btnAssinar) btnAssinar.style.display = 'none';
            if (typeof onAssinaturaConcluida === 'function') onAssinaturaConcluida(finalRes);
        } else {
            throw new Error(finalRes.message || 'O servidor recusou a assinatura.');
        }

    } catch (err) {
        if (statusEl) statusEl.innerHTML = `<span class="badge-status erro">❌ ${err.message}</span>`;
        if (btnAssinar) { btnAssinar.disabled = false; btnAssinar.textContent = '🔏 Assinar Documento'; }
    }
}

function getCsrf() {
    return document.querySelector('[name="_csrfToken"]')?.value
        ?? document.querySelector('meta[name="csrf-token"]')?.content
        ?? '';
}