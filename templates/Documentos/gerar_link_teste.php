<?php
/**
 * View: Documentos/gerar_link_teste.php
 * Action: DocumentosController::gerarLinkTeste()
 * Acesso autenticado.
 *
 * @var \App\Model\Entity\Documento $documento
 * @var string $urlCurta
 * @var string $urlLonga
 */
$this->assign('title', 'Link de Assinatura');

$jaAssinado = !empty($documento->assinatura_hash);
?>

<div class="container-fluid py-4" style="max-width:640px;">

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header py-3" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);border-radius:12px 12px 0 0;">
            <h5 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                <iconify-icon icon="solar:shield-keyhole-bold" width="20"></iconify-icon>
                Link de Assinatura Eletrônica
            </h5>
        </div>

        <div class="card-body p-4">

            <?php if ($jaAssinado): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 mb-4">
                <iconify-icon icon="solar:check-circle-bold" width="20"></iconify-icon>
                <span><strong>Documento já assinado!</strong> Este documento já possui assinatura eletrônica registrada.</span>
            </div>
            <?php endif; ?>

            <!-- Info documento -->
            <table class="table table-sm table-borderless mb-4" style="font-size:.88rem;">
                <tr>
                    <td class="text-muted fw-semibold" style="width:140px;">Tipo:</td>
                    <td><?= h($documento->tipo) ?></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Título:</td>
                    <td><?= h($documento->titulo ?? '—') ?></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Código:</td>
                    <td><code style="font-size:.78rem;"><?= h($documento->codigo_validacao) ?></code></td>
                </tr>
                <?php if ($jaAssinado): ?>
                <tr>
                    <td class="text-muted fw-semibold">Assinado em:</td>
                    <td><?= $documento->assinatura_data ? $documento->assinatura_data->format('d/m/Y H:i') : '—' ?></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Hash SHA-256:</td>
                    <td><code style="font-size:.7rem;word-break:break-all;"><?= h($documento->assinatura_hash) ?></code></td>
                </tr>
                <?php endif; ?>
            </table>

            <!-- URL Curta (para compartilhar) -->
            <label class="form-label fw-bold" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.05em;color:#2563eb;">
                Link para o Paciente Assinar
            </label>
            <div class="input-group mb-2">
                <input type="text" class="form-control form-control-sm font-monospace"
                       id="urlCurta" value="<?= h($urlCurta) ?>" readonly
                       style="font-size:.82rem;background:#f8fafc;">
                <button class="btn btn-primary btn-sm" type="button" onclick="copiarUrl('urlCurta', this)"
                        style="border-radius:0 8px 8px 0;background:#2563eb;border-color:#2563eb;">
                    <iconify-icon icon="solar:copy-bold" width="15"></iconify-icon>
                    Copiar
                </button>
            </div>

            <div class="d-flex gap-2 mt-3 flex-wrap">
                <a href="<?= $urlCurta ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                    <iconify-icon icon="solar:eye-bold" width="15"></iconify-icon>
                    Visualizar como paciente
                </a>
                <a href="<?= $urlLonga ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <iconify-icon icon="solar:shield-check-bold" width="15"></iconify-icon>
                    Validar documento
                </a>
                <a href="javascript:gerarWhatsApp()" class="btn btn-success btn-sm rounded-pill">
                    <iconify-icon icon="ic:baseline-whatsapp" width="15"></iconify-icon>
                    Enviar via WhatsApp
                </a>
            </div>

            <hr class="my-4" />

            <label class="form-label fw-bold" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.05em;color:#10b981;">
                Ações do Profissional (ICP-Brasil)
            </label>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <!-- Assinatura via Agente Local -->
                <a href="<?= $this->Url->build(['action' => 'assinarProfissional', $documento->id]) ?>" class="btn btn-primary btn-sm rounded-pill d-inline-flex align-items-center gap-1">
                    <iconify-icon icon="solar:pen-bold" width="15"></iconify-icon>
                    Assinar com Certificado (Agente Local)
                </a>
                <a href="https://validar.iti.gov.br/" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill">
                    <iconify-icon icon="solar:shield-check-bold" width="15"></iconify-icon>
                    Verificador ITI
                </a>
            </div>

            <hr class="my-4" />

            <div class="alert alert-info rounded-3 mb-0" style="font-size:.82rem;">
                <iconify-icon icon="solar:info-circle-bold" width="16" style="vertical-align:middle;margin-right:4px;"></iconify-icon>
                <strong>Atenção:</strong> Certifique-se de que o documento foi <strong>impresso/gerado</strong> pelo menos uma vez
                antes de enviar este link ao paciente. O arquivo PDF precisa existir no servidor para que a assinatura seja processada.
            </div>
        </div>
    </div>
</div>

<script>
function copiarUrl(inputId, btn) {
    const input = document.getElementById(inputId);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function () {
        const orig = btn.innerHTML;
        btn.innerHTML = '<iconify-icon icon="solar:check-bold" width="15"></iconify-icon> Copiado!';
        btn.classList.replace('btn-primary', 'btn-success');
        setTimeout(function () {
            btn.innerHTML = orig;
            btn.classList.replace('btn-success', 'btn-primary');
        }, 2000);
    });
}
function gerarWhatsApp() {
    const url = document.getElementById('urlCurta').value;
    const msg = encodeURIComponent('Olá! Por favor, acesse o link abaixo para assinar seu documento eletronicamente:\n\n' + url);
    window.open('https://wa.me/?text=' + msg, '_blank');
}
</script>
