<?php
/**
 * @var \App\View\AppView    $this
 * @var \App\Model\Entity\Documento $documento
 */
$this->assign('title', 'Assinar Documento — Agente Local');
$csrf = $this->request->getAttribute('csrfToken');
?>
<meta name="csrf-token" content="<?= h($csrf) ?>">
<style>
    :root {
        --blue: #1d4ed8;
        --blue-dark: #1e3a8a;
        --green: #16a34a;
        --red: #dc2626;
        --amber: #d97706;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-600: #475569;
    }

    .sign-card {
        max-width: 640px;
        margin: 0 auto;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 32px rgba(0,0,0,.10);
    }
    .sign-header {
        background: linear-gradient(135deg, var(--blue-dark), var(--blue));
        padding: 28px 32px 22px;
        color: #fff;
        position: relative;
    }
    .sign-header h4 { font-weight: 700; letter-spacing: -.01em; margin: 0 0 4px; }
    .sign-header p  { opacity: .8; font-size: .88rem; margin: 0; }
    .sign-body { background: #fff; padding: 28px 32px; }

    /* Status badge */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .04em;
    }
    .badge-status.online    { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
    .badge-status.offline   { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
    .badge-status.aguardando{ background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
    .badge-status.erro      { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }

    /* Doc info grid */
    .doc-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 24px;
    }
    .doc-item {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 10px 14px;
    }
    .doc-item label { display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:2px; }
    .doc-item span  { font-size:.88rem; color:#1e293b; font-weight:500; }
    @media(max-width:480px){ .doc-grid{ grid-template-columns:1fr; } }

    /* Alert agente */
    .alert-agente {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 20px;
    }
    .alert-agente p { margin: 6px 0; font-size: .85rem; color: #92400e; }
    .btn-download, .btn-retry {
        display: inline-block;
        margin: 8px 6px 0 0;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
    }
    .btn-download { background: var(--blue); color: #fff; }
    .btn-retry    { background: var(--gray-100); color: var(--gray-600); }

    /* Cert select */
    #cert-area { margin-bottom: 22px; }
    #cert-area label { display:block; font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--blue); margin-bottom:6px; }
    #cert-select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        font-size: .88rem;
        background: var(--gray-50);
        color: #1e293b;
        outline: none;
        transition: border-color .2s;
    }
    #cert-select:focus { border-color: var(--blue); }

    /* Sign button */
    #btn-assinar {
        width: 100%;
        padding: 15px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--blue), var(--blue-dark));
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        letter-spacing: .02em;
        box-shadow: 0 4px 20px rgba(29,78,216,.3);
        transition: all .22s;
    }
    #btn-assinar:hover:not(:disabled)  { transform:translateY(-2px); box-shadow:0 8px 28px rgba(29,78,216,.4); }
    #btn-assinar:active:not(:disabled) { transform:none; }
    #btn-assinar:disabled { opacity:.6; cursor:not-allowed; transform:none; }

    /* Resultado */
    #resultado { display:none; margin-top:18px; }
    .btn-ver-pdf {
        display:inline-block;
        padding:10px 24px;
        background:var(--green);
        color:#fff;
        border-radius:10px;
        font-weight:700;
        text-decoration:none;
        font-size:.9rem;
    }

    /* Steps helper */
    .steps-mini { list-style:none; padding:0; margin:0 0 20px; display:flex; flex-direction:column; gap:8px; }
    .steps-mini li { display:flex; gap:10px; align-items:flex-start; font-size:.82rem; color:var(--gray-600); }
    .step-n { flex-shrink:0; width:22px;height:22px;border-radius:50%;background:var(--blue);color:#fff;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center; }
</style>

<div class="container-fluid py-4">
    <div class="sign-card">
        <div class="sign-header">
            <h4>🔏 Assinatura Digital — Profissional</h4>
            <p>Via agente local · ICP-Brasil · A1 / A3 / Token / Smart Card</p>
        </div>

        <div class="sign-body">

            <!-- Info documento -->
            <div class="doc-grid">
                <div class="doc-item">
                    <label>Documento</label>
                    <span><?= h($documento->titulo ?? $documento->tipo) ?></span>
                </div>
                <div class="doc-item">
                    <label>Profissional</label>
                    <span><?= h($documento->profissional->nome ?? 'N/A') ?></span>
                </div>
                <div class="doc-item">
                    <label>Paciente</label>
                    <span><?= h($documento->procedimento->consulta->cliente->nome ?? 'N/A') ?></span>
                </div>
                <div class="doc-item">
                    <label>Assinatura paciente</label>
                    <span><?= !empty($documento->assinatura_hash) ? '✅ Presente' : '— Pendente' ?></span>
                </div>
            </div>

            <!-- Status do agente -->
            <div id="status" class="mb-3">
                <span class="badge-status aguardando">⏳ Verificando agente na porta 7777...</span>
            </div>

            <!-- Certificados (exibido só quando agente OK) -->
            <div id="cert-area" style="display:none;">
                <label for="cert-select">Selecione o Certificado Digital</label>
                <select id="cert-select">
                    <option value="">— carregando certificados… —</option>
                </select>
            </div>

            <!-- Botão assinar -->
            <button id="btn-assinar" onclick="assinar(<?= $documento->id ?>)" disabled>
                🔏 Assinar Documento
            </button>

            <!-- Área de resultado (injetada pelo signer.js) -->
            <div id="resultado" class="text-center mt-4"></div>

            <!-- Mini guia -->
            <hr style="margin:24px 0 16px; border-color:var(--gray-200);">
            <ul class="steps-mini">
                <li><span class="step-n">1</span>Instale e execute o agente assinador no seu computador.</li>
                <li><span class="step-n">2</span>Selecione o certificado digital desejado no menu acima.</li>
                <li><span class="step-n">3</span>Clique em "Assinar Documento" — a chave privada nunca sai do seu dispositivo.</li>
            </ul>

        </div>
    </div>
</div>

<script src="<?= $this->Url->build('/js/signer.js') ?>"></script>
<script>
    // Configura a URL base do CakePHP (/painel/) para o arquivo JS usar no fetch()
    window.API_BASE = "<?= $this->Url->build('/') ?>";
    
    // Inicializa a detecção do agente ao carregar a página
    document.addEventListener('DOMContentLoaded', carregarCertificados);
</script>
