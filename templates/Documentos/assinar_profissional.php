<?php
/**
 * @var \App\View\AppView    $this
 * @var \App\Model\Entity\Documento $documento
 */
$this->assign('title', '');
$csrf = $this->request->getAttribute('csrfToken');
?>
<meta name="csrf-token" content="<?= h($csrf) ?>">

<style>
    /* Estilos preservados para compatibilidade com o retorno HTML do signer.js */
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

    .badge-status.online {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-status.offline {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .badge-status.aguardando {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-status.erro {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .alert-agente {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 20px;
    }

    .alert-agente p {
        margin: 6px 0;
        font-size: .85rem;
        color: #92400e;
    }

    .btn-download,
    .btn-retry {
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

    .btn-download {
        background: #1d4ed8;
        color: #fff;
    }

    .btn-retry {
        background: #f1f5f9;
        color: #475569;
    }

    .premium-gold {
        background: linear-gradient(135deg, #BF953F 0%, #FCF6BA 45%, #B38728 70%, #FBF5B7 85%, #AA771C 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
        filter: drop-shadow(0px 1px 1px rgba(0,0,0,0.1));
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card w-100 position-relative overflow-hidden mt-4">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-1 text-white d-flex align-items-center gap-2">
                    <iconify-icon icon="ph:certificate-bold" class="fs-8 premium-gold"></iconify-icon>
                    Assinatura Digital Qualificada
                </h5>
                <p class="mb-0 opacity-75 small">ICP-Brasil · A1 / A3 / Token / Smart Card</p>
            </div>

            <div class="card-body p-4">
                <!-- Info documento -->
                <div class="row mb-4">
                    <div class="col-md-12 mb-3 mt-1">
                        <h6 class="border-bottom pb-2">Informações do Documento:</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Documento</label>
                        <div class="fw-medium text-dark"><?= h($documento->titulo ?? $documento->tipo) ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Profissional</label>
                        <div class="fw-medium text-dark"><?= h($documento->profissional->nome ?? 'N/A') ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Paciente</label>
                        <div class="fw-medium text-dark">
                            <?= h($documento->procedimento->consulta->cliente->nome ?? 'N/A') ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Assinatura
                            Paciente</label>
                        <div class="fw-medium text-dark">
                            <?= !empty($documento->assinatura_hash) ? '✅ Presente' : '— Pendente' ?>
                        </div>
                    </div>
                </div>

                <!-- Status do agente -->
                <div id="status" class="mb-4">
                    <span class="badge-status aguardando">⏳ Verificando agente na porta 7777...</span>
                </div>

                <!-- Certificados (exibido só quando agente OK) -->
                <div id="cert-area" class="mb-4" style="display:none;">
                    <div class="col-md-12 mb-3">
                        <label for="cert-select" class="form-label">Selecione o Certificado Digital</label>
                        <select id="cert-select" class="form-select">
                            <option value="">— carregando certificados… —</option>
                        </select>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-grid gap-2 mb-3">
                    <button id="btn-assinar" class="btn btn-success rounded-pill py-2 fw-bold"
                        onclick="assinar(<?= $documento->id ?>)" disabled>
                        <iconify-icon icon="fluent:signature-24-filled" class="fs-6"
                            style="color: #FFFFFF;"></iconify-icon> Assinar Documento
                    </button>
                    <a href="<?= $this->Url->build(['controller' => 'Consultas', 'action' => 'editar', $documento->procedimento->consulta->id ?? $documento->procedimento->consulta_id]) ?>"
                        class="btn bg-danger-subtle text-danger rounded-pill py-2 fw-bold">
                        Fechar e Voltar
                    </a>
                </div>

                <!-- Área de resultado (injetada pelo signer.js) -->
                <div id="resultado" class="text-center mt-3"></div>

                <!-- Mini guia -->
                <div class="row">
                    <div class="col-md-12 mb-3 mt-3">
                        <h6 class="border-bottom pb-2">Instruções:</h6>
                    </div>
                </div>
                <ul class="list-unstyled text-muted small mb-0">
                    <li class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary rounded-circle me-2 p-2">1</span> Instale e execute o agente
                        assinador no seu computador.
                    </li>
                    <li class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary rounded-circle me-2 p-2">2</span> Selecione o certificado digital
                        desejado no menu acima.
                    </li>
                    <li class="d-flex align-items-center">
                        <span class="badge bg-primary rounded-circle me-2 p-2">3</span> Clique em "Assinar Documento" —
                        a chave privada nunca sai do seu dispositivo.
                    </li>
                </ul>

            </div>
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