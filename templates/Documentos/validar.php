<?php
/**
 * View: Documentos/validar.php
 * Layout: assinar (mobile-first, sem autenticação)
 *
 * Variáveis disponíveis:
 *  @var \App\Model\Entity\Documento $documento
 */
$this->assign('title', 'Validar Documento');

$pacienteNome = h($documento->procedimento->consulta->cliente->nome ?? '');
$docTitulo = h($documento->titulo ?? $documento->tipo ?? 'Documento');
$profNome = h($documento->profissional->nome ?? '');
$estabelecimento = h($documento->estabelecimento->nome_fantasia ?? $documento->estabelecimento->razao_social ?? '');
$codigoValidacao = $documento->codigo_validacao;

// Mascarar CPF do paciente para LGPD
$pacienteCpfRaw = $documento->procedimento->consulta->cliente->documento ?? '';
$pacienteCpf = '***.***.***-**';
if (!empty($pacienteCpfRaw) && strlen($pacienteCpfRaw) >= 11) {
    $pacienteCpf = '***.' . substr($pacienteCpfRaw, 3, 3) . '.' . substr($pacienteCpfRaw, 6, 3) . '-**';
}

$estaAssinado = !empty($documento->assinatura_hash) || !empty($documento->assinatura_prof_hash);
?>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
    .card-header-validar {
        background: #001740;
        color: #fff;
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .text-success-dark {
        color: #146c43;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card w-100 position-relative overflow-hidden mt-2 shadow-sm border-0">
                <div class="card-header card-header-validar p-4 text-center border-0">
                    <div class="small opacity-75 mb-1 text-uppercase fw-bold"><?= $estabelecimento ?></div>
                    <h4 class="mb-1 text-white"><?= $docTitulo ?></h4>
                    <div class="small opacity-75 mt-1">Código: <span
                            class="font-monospace"><?= h($codigoValidacao) ?></span></div>
                </div>

                <div class="card-body p-4 p-md-5 text-center">
                    <?php if ($estaAssinado): ?>
                        <div class="mb-4">
                            <iconify-icon icon="solar:shield-check-bold-duotone" class="text-success mb-2"
                                style="font-size: 5.5rem;"></iconify-icon>
                            <h4 class="text-success-dark fw-bold mb-1">Documento Autêntico</h4>
                            <div class="badge bg-success-light text-success-dark rounded-pill px-3 py-2 fs-7">
                                <iconify-icon icon="solar:verified-check-bold" class="align-middle me-1"></iconify-icon>
                                Validado com Sucesso
                            </div>
                        </div>

                        <div class="text-start bg-light p-3 rounded-3 mb-4 border border-light-subtle">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-muted small text-uppercase fw-bold mb-0"
                                        style="font-size: 0.65rem;">Paciente / Signatário</label>
                                    <div class="fw-bold text-dark"><?= $pacienteNome ?></div>
                                    <div class="small text-muted">CPF: <?= $pacienteCpf ?></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small text-uppercase fw-bold mb-0"
                                        style="font-size: 0.65rem;">Profissional Responsável</label>
                                    <div class="fw-bold text-dark"><?= $profNome ?></div>
                                </div>

                                <?php if (!empty($documento->assinatura_data)): ?>
                                    <div class="col-12 border-top pt-2">
                                        <label class="form-label text-muted small text-uppercase fw-bold mb-0"
                                            style="font-size: 0.65rem;">Data da Assinatura (Paciente)</label>
                                        <div class="small text-dark">
                                            <?= $documento->assinatura_data->format('d/m/Y \à\s H:i:s') ?></div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($documento->assinatura_prof_data)): ?>
                                    <div class="col-12 border-top pt-2">
                                        <label class="form-label text-muted small text-uppercase fw-bold mb-0"
                                            style="font-size: 0.65rem;">Data da Assinatura Digital (Profissional)</label>
                                        <div class="small text-dark">
                                            <?= $documento->assinatura_prof_data->format('d/m/Y \à\s H:i:s') ?></div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12 border-top pt-2">
                                    <label class="form-label text-muted small text-uppercase fw-bold mb-0"
                                        style="font-size: 0.65rem;">Integridade (Hash SHA-256)</label>
                                    <code class="d-block text-primary small mt-1"
                                        style="word-break:break-all; font-size: 0.7rem;"><?= h($documento->assinatura_prof_hash ?: $documento->assinatura_hash) ?></code>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="<?= $this->Url->build(['action' => 'publicPdf', $codigoValidacao]) ?>" target="_blank"
                                class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                <iconify-icon icon="solar:document-text-bold" class="align-middle me-1"></iconify-icon>
                                Visualizar Documento (PDF)
                            </a>
                            <p class="small text-muted mt-2 mb-0">
                                A autenticidade deste documento eletrônico é garantida pela Medida Provisória nº
                                2.200-2/2001.
                            </p>
                        </div>

                    <?php else: ?>
                        <div class="mb-4">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="text-warning mb-2"
                                style="font-size: 5.5rem;"></iconify-icon>
                            <h4 class="text-warning fw-bold mb-1">Aguardando Conclusão</h4>
                            <p class="text-muted small">Este documento ainda não completou o ciclo total de assinaturas
                                eletrônicas exigidas.</p>
                        </div>

                        <div class="text-start bg-light p-3 rounded-3 mb-4 border border-light-subtle">
                            <p class="small text-muted mb-0">Assim que o documento for finalizado e assinado, os detalhes de
                                autenticidade e o link para visualização serão disponibilizados nesta página.</p>
                        </div>

                        <div class="d-grid">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill fw-bold">
                                <iconify-icon icon="solar:arrow-left-bold" class="align-middle me-1"></iconify-icon> Voltar
                            </a>
                        </div>
                    <?php endif; ?>

                    <hr class="my-5 opacity-25">

                    <div class="text-muted" style="font-size: 0.7rem;">
                        <p class="mb-1 fw-bold text-uppercase">Validade Jurídica:</p>
                        <p class="mb-0">Este processo segue estritamente os padrões da Medida Provisória nº 2.200-2/2001
                            e da Lei nº 14.063/2020.</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 text-muted small">
                &copy; <?= date('Y') ?> Intelixis Soluções Inteligentes
            </div>

        </div>
    </div>
</div>