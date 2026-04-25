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

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card w-100 position-relative overflow-hidden mt-4">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-1 text-white d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:shield-keyhole-bold" class="fs-6"></iconify-icon>
                    Link de Assinatura Eletrônica
                </h5>
                <p class="mb-0 opacity-75 small">Gerenciamento de Assinatura do Paciente</p>
            </div>

            <div class="card-body p-4">

                <?php if ($jaAssinado): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                    <iconify-icon icon="solar:check-circle-bold" class="fs-5"></iconify-icon>
                    <span><strong>Documento já assinado!</strong> Este documento já possui assinatura eletrônica registrada.</span>
                </div>
                <?php endif; ?>

                <!-- Info documento -->
                <div class="row mb-4">
                    <div class="col-md-12 mb-3 mt-1">
                        <h6 class="border-bottom pb-2">Informações do Documento:</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Tipo</label>
                        <div class="fw-medium text-dark"><?= h($documento->tipo) ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Título</label>
                        <div class="fw-medium text-dark"><?= h($documento->titulo ?? '—') ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Código</label>
                        <div class="fw-medium text-dark"><code class="text-primary"><?= h($documento->codigo_validacao) ?></code></div>
                    </div>
                    <?php if ($jaAssinado): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Assinado em</label>
                        <div class="fw-medium text-dark"><?= $documento->assinatura_data ? $documento->assinatura_data->format('d/m/Y H:i') : '—' ?></div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold mb-1">Hash SHA-256</label>
                        <div class="fw-medium text-dark"><code class="text-muted" style="word-break:break-all;"><?= h($documento->assinatura_hash) ?></code></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- URL Curta (para compartilhar) -->
                <div class="row">
                    <div class="col-md-12 mb-2 mt-2">
                        <h6 class="border-bottom pb-2 text-primary">Link para o Paciente Assinar:</h6>
                    </div>
                </div>
                
                <button class="btn btn-link p-0 text-secondary" type="button" 
        onclick="copiarUrl('urlCurta', this)"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Copiar link">
    <span class="nav-icon-hover-bg rounded-circle p-1">
        <iconify-icon icon="flowbite:link-outline" class="fs-7 align-middle"></iconify-icon>
    </span>
</button>

<input type="text" id="urlCurta" value="<?= h($urlCurta) ?>" class="d-none" readonly>

<button type="button" disabled 
        class="btn btn-link p-0 text-success" 
        onclick="gerarWhatsApp()"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Enviar via WhatsApp">
    <span class="nav-icon-hover-bg rounded-circle p-1">
        <iconify-icon icon="ic:baseline-whatsapp" class="fs-7 align-middle"></iconify-icon>
    </span>
</button>

<button type="button" disabled 
        class="btn btn-link p-0 text-dark" 
        onclick="enviarEmail()"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Enviar via Email">
    <span class="nav-icon-hover-bg rounded-circle p-1">
        <iconify-icon icon="streamline:send-email-remix" class="fs-7 align-middle"></iconify-icon>
    </span>
</button>

<a href="<?= $urlCurta ?>" target="_blank" 
   class="btn btn-link p-0 text-primary"
   data-bs-toggle="tooltip" data-bs-placement="top" title="Abrir link para assinatura">
    <span class="nav-icon-hover-bg rounded-circle p-1">
        <iconify-icon icon="streamline-plump:web" class="fs-7 align-middle"></iconify-icon>
    </span>
</a>




                <div class="alert alert-info d-flex align-items-center mb-0">
                    <iconify-icon icon="solar:info-circle-bold" class="fs-4 me-2 flex-shrink-0"></iconify-icon>
                    <div>
                        <strong>Atenção:</strong> Certifique-se de que o documento foi <strong>impresso/gerado</strong> pelo menos uma vez antes de enviar este link ao paciente. O arquivo PDF precisa existir no servidor para que a assinatura seja processada.
                    </div>
                </div>

            <div class="mt-4">
                <a href="<?= $this->Url->build(['controller' => 'Consultas', 'action' => 'editar', $documento->procedimento->consulta_id ?? '']) ?>" class="btn bg-danger-subtle text-danger rounded-pill fw-bold w-100">
                    Fechar e Voltar
                </a>
            </div>


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
        btn.innerHTML = '<iconify-icon icon="solar:check-bold" class="fs-5 align-middle me-1"></iconify-icon> Copiado!';
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
