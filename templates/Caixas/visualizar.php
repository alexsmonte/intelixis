<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Caixa $caixa
 */

$this->BreadcrumbsPlus->add([['title' => 'Caixas', 'url' => ['controller' => 'Caixas', 'action' => 'index']], ['title' => 'Visualizar Caixa #' . $caixa->id]]);
?>
<?= $this->element('barra-titulo', ['title' => 'Visualizar Caixa #' . $caixa->id]); ?>
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>


<?php //debug($caixa); ?>
<?php //die(); ?>
<style>
    [v-cloak] {
        display: none;
    }

    /* ── Card Icon Section (Spike Bootstrap) ── */
    .card-icon-section {
        display: flex;
        align-items: center;
        padding: 20px 24px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        border: 1px solid #e9ecef;
        transition: transform 0.15s, box-shadow 0.15s;
        height: 100%;
    }

    .card-icon-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .card-icon-section .card-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 16px;
    }

    .card-icon-section .card-icon-wrap .iconify {
        font-size: 26px;
    }

    .card-icon-section .card-info h4 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .card-icon-section .card-info p {
        margin: 2px 0 0;
        font-size: 0.82rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* ── Ícone de ação circular ── */
    .action-icon-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: 2px solid;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .action-icon-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .action-icon-btn .iconify {
        font-size: 24px;
    }

    .action-icon-btn.btn-retirada {
        border-color: #dc3545;
        color: #dc3545;
    }

    .action-icon-btn.btn-retirada:hover {
        background: #dc3545;
        color: #fff;
    }

    .action-icon-btn.btn-deposito {
        border-color: #198754;
        color: #198754;
    }

    .action-icon-btn.btn-deposito:hover {
        background: #198754;
        color: #fff;
    }

    .action-icon-btn.btn-conferencia {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .action-icon-btn.btn-conferencia:hover {
        background: #0d6efd;
        color: #fff;
    }

    .action-icon-label {
        font-size: 0.72rem;
        color: #6c757d;
        font-weight: 500;
        text-align: center;
        margin-top: 5px;
    }

    /* ── Situação badge ── */
    .badge-conferido {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .badge-nao-conferido {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    /* ── Table hover ── */
    .table-hover-custom tbody tr:hover {
        background-color: #f0f7ff;
    }

    /* ── Total row ── */
    .total-row {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        font-weight: 600;
    }

    .total-row td {
        border-top: 2px solid #dee2e6 !important;
    }
</style>

<?php $this->assign('title', 'Visualizar Caixa'); ?>

<div id="app-caixa-view" v-cloak data-csrf="<?= $this->request->getAttribute('csrfToken') ?>" data-routes='<?= json_encode([
      'movimentar' => $this->Url->build(['controller' => 'Caixas', 'action' => 'movimentar', $caixa->id]),
      'conferir' => $this->Url->build(['controller' => 'Caixas', 'action' => 'conferir', $caixa->id]),
      'visualizar' => $this->Url->build(['controller' => 'Caixas', 'action' => 'visualizar', $caixa->id]),
      'index' => $this->Url->build(['controller' => 'Caixas', 'action' => 'index']),
  ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <!-- Loader -->
    <div v-if="carregando" class="d-flex justify-content-center align-items-center py-5">
        <div class="spinner-border text-primary me-2"></div>
        <span class="text-muted">Carregando dados do caixa...</span>
    </div>

    <div v-else>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- CARDS + AÇÕES                                         -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="row g-3 mb-4">

            <!-- Card: Abertura no Caixa -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card-icon-section" style="border-left: 4px solid #0d6efd;">
                    <div class="card-icon-wrap" style="background: #e7f1ff;">
                        <span class="iconify text-primary" data-icon="solar:wallet-bold-duotone"></span>
                    </div>
                    <div class="card-info">
                        <h4 class="text-primary">R$ {{ formatarMoeda(caixa.saldo_inicial) }}</h4>
                        <p>Abertura no Caixa</p>
                    </div>
                </div>
            </div>

            <!-- Card: Saldo Atual -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card-icon-section" style="border-left: 4px solid #198754;">
                    <div class="card-icon-wrap" style="background: #d1fae5;">
                        <span class="iconify text-success" data-icon="solar:money-bag-bold-duotone"></span>
                    </div>
                    <div class="card-info">
                        <h4 class="text-success">R$ {{ caixa.situacao === 'aberto' ? formatarMoeda(saldoAtual) : '0,00'
                            }}</h4>
                        <p>Saldo Atual</p>
                    </div>
                </div>
            </div>

            <!-- Card: Saldo de Fechamento -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card-icon-section" style="border-left: 4px solid #fd7e14;">
                    <div class="card-icon-wrap" style="background: #fff3e0;">
                        <span class="iconify text-warning" data-icon="solar:cash-out-bold-duotone"></span>
                    </div>
                    <div class="card-info">
                        <h4 class="text-warning">R$ {{ formatarMoeda(caixa.saldo_final || 0) }}</h4>
                        <p>Saldo de Fechamento</p>
                    </div>
                </div>
            </div>

            <!-- Ícones de Ação -->
            <div class="col-xl-3 col-lg-12 col-md-6">
                <div v-if="caixa.situacao === 'aberto'"
                    class="d-flex align-items-center justify-content-center gap-4 h-100">
                    <div class="text-center">
                        <button class="action-icon-btn btn-retirada" @click="abrirModalMovimentacao('retirada')"
                            title="Retirada do Caixa">
                            <span class="iconify" data-icon="solar:card-send-bold-duotone"></span>
                        </button>
                        <div class="action-icon-label">Retirar</div>
                    </div>
                    <div class="text-center">
                        <button class="action-icon-btn btn-deposito" @click="abrirModalMovimentacao('entrada')"
                            title="Depósito no Caixa">
                            <span class="iconify" data-icon="solar:card-recive-bold-duotone"></span>
                        </button>
                        <div class="action-icon-label">Depositar</div>
                    </div>
                    <div class="text-center">
                        <button class="action-icon-btn btn-conferencia" @click="abrirModalConferencia"
                            title="Conferência de Caixa">
                            <span class="iconify" data-icon="solar:clipboard-check-bold-duotone"></span>
                        </button>
                        <div class="action-icon-label">Conferir</div>
                    </div>
                </div>
                <div v-else class="d-flex align-items-center justify-content-center h-100">
                    <span class="badge bg-secondary-subtle border border-secondary text-secondary  px-1 py-1">
                        <span class="iconify me-1" data-icon="solar:lock-bold-duotone" style="font-size:14px;"></span>
                        Caixa Fechado
                    </span>
                </div>
            </div>

        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- TABELA DE MOVIMENTAÇÕES                               -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex mb-3 align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-semibold">
                        <span class="iconify me-2 text-primary" data-icon="solar:transfer-horizontal-bold-duotone"
                            style="font-size:20px;"></span>
                        Movimentação Consolidada
                    </h5>
                    <span v-if="caixa.situacao === 'aberto'"
                        class="badge bg-success-subtle border border-success text-success">
                        <span class="iconify me-1" data-icon="solar:check-circle-bold-duotone"
                            style="font-size:14px;"></span>
                        Caixa Aberto
                    </span>
                </div>
                <div class="table-responsive-sm border rounded-2">
                    <table class="table table-striped table-hover-custom text-nowrap mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>Meio de Pagamento</th>
                                <th>Tipo</th>
                                <th class="text-end">Valor Apurado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(mov, index) in caixa.movimentacoes" :key="'mov-'+mov.id">
                                <td class="text-muted">M{{ index + 1 }}</td>
                                <td>{{ mov.forma_pagamento ? mov.forma_pagamento.nome : 'Dinheiro' }}</td>
                                <td>
                                    <span v-if="mov.situacao === 'entrada' || mov.situacao === 'E'"
                                        class="badge bg-success-subtle text-success border border-success">
                                        <span class="iconify me-1" data-icon="solar:arrow-down-bold-duotone"
                                            style="font-size:13px;"></span>
                                        Entrada
                                    </span>
                                    <span v-else class="badge bg-danger-subtle text-danger border border-danger">
                                        <span class="iconify me-1" data-icon="solar:arrow-up-bold-duotone"
                                            style="font-size:13px;"></span>
                                        Retirada
                                    </span>
                                </td>
                                <td class="text-end fw-medium"
                                    :class="(mov.situacao === 'entrada' || mov.situacao === 'E') ? 'text-success' : 'text-danger'">
                                    R$ {{ formatarMoeda(Math.abs(parseFloat(mov.valor_apurado || 0))) }}
                                </td>
                            </tr>
                            <tr v-if="!caixa.movimentacoes || caixa.movimentacoes.length === 0">
                                <td colspan="4" class="text-center text-muted py-4">Nenhuma movimentação encontrada</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" class="text-end fw-bold">Saldo:</td>
                                <td class="text-end fw-bold"
                                    :class="totalMovimentacoes >= 0 ? 'text-success' : 'text-danger'">
                                    R$ {{ formatarMoeda(totalMovimentacoes) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- TABELA DE LANÇAMENTOS                                 -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-3 fw-semibold">
                    <span class="iconify me-2 text-info" data-icon="solar:card-2-bold-duotone"
                        style="font-size:20px;"></span>
                    Lançamentos do Caixa (Pagamentos)
                </h5>
                <div class="table-responsive-sm border rounded-2">
                    <table class="table table-striped table-hover-custom text-nowrap mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>Descrição</th>
                                <th>Cliente</th>
                                <th>Valor Original</th>
                                <th class="text-end">Valor Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pag, index) in caixa.pagamentos" :key="'pag-'+pag.id">
                                <td class="text-muted">P{{ index + 1 }}</td>
                                <td>{{ pag.financa ? pag.financa.descricao : 'Lançamento sem descrição' }}</td>
                                <td>{{ (pag.financa && pag.financa.cliente) ? pag.financa.cliente.nome : '—' }}</td>
                                <td>R$ {{ formatarMoeda(pag.financa ? parseFloat(pag.financa.valor || 0) : 0) }}</td>
                                <td class="text-end fw-medium text-success">R$ {{ formatarMoeda(parseFloat(pag.valor ||
                                    0)) }}</td>
                            </tr>
                            <tr v-if="!caixa.pagamentos || caixa.pagamentos.length === 0">
                                <td colspan="5" class="text-center text-muted py-4">Nenhum lançamento encontrado</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="4" class="text-end fw-bold">Total Recebido:</td>
                                <td class="text-end fw-bold text-success">R$ {{ formatarMoeda(totalPagamentos) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- INFORMAÇÕES DO CAIXA                                  -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-3 fw-semibold">
                    <span class="iconify me-2 text-primary" data-icon="solar:info-circle-bold-duotone"
                        style="font-size:20px;"></span>
                    Informações do Caixa
                </h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <small class="text-muted d-block">Operador</small>
                        <strong>{{ caixa.usuario ? caixa.usuario.nome : '—' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Data de Abertura</small>
                        <strong>{{ formatarDataHora(caixa.data_abertura) }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Data de Fechamento</small>
                        <strong>{{ caixa.data_fechamento ? formatarDataHora(caixa.data_fechamento) : '—' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Conferente</small>
                        <span v-if="caixa.conferente" class="badge badge-conferido">
                            <span class="iconify me-1" data-icon="solar:shield-check-bold-duotone"
                                style="font-size:14px;"></span>
                            {{ caixa.conferente.nome }}
                        </span>
                        <span v-else class="badge badge-nao-conferido">
                            <span class="iconify me-1" data-icon="solar:shield-warning-bold-duotone"
                                style="font-size:14px;"></span>
                            Não conferido
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botão Voltar -->
        <div class="mb-4">
            <a :href="routes.index" class="btn btn-outline-secondary rounded-pill px-4">
                <span class="iconify me-1" data-icon="solar:arrow-left-bold-duotone" style="font-size:16px;"></span>
                Voltar para Caixas
            </a>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- MODAL: MOVIMENTAÇÃO                                   -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="modal fade" id="modalMovimentacao" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="action-icon-btn"
                                :class="movimentacaoTipo === 'retirada' ? 'btn-retirada' : 'btn-deposito'"
                                style="width:40px;height:40px;border-width:1px;">
                                <span class="iconify"
                                    :data-icon="movimentacaoTipo === 'retirada' ? 'solar:card-send-bold-duotone' : 'solar:card-recive-bold-duotone'"
                                    style="font-size:18px;"></span>
                            </div>
                            <h5 class="modal-title mb-0">
                                {{ movimentacaoTipo === 'retirada' ? 'Retirada do Caixa' : 'Depósito no Caixa' }}
                            </h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form @submit.prevent="salvarMovimentacao">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Valor *</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" v-model="formMovimentacao.valor" v-monetario
                                        placeholder="0,00" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Meio de Pagamento *</label>
                                <select class="form-select" v-model="formMovimentacao.forma_pagamento_id" required>
                                    <option value="" disabled>Selecione...</option>
                                    <option v-for="(nome, id) in formasPagamento" :key="id" :value="id">{{ nome }}
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Motivo</label>
                                <input type="text" class="form-control" v-model="formMovimentacao.motivo"
                                    placeholder="Opcional">
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-danger rounded-pill px-4"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn rounded-pill px-4"
                                :class="movimentacaoTipo === 'retirada' ? 'btn-warning' : 'btn-success'"
                                :disabled="salvando">
                                <span v-if="salvando" class="spinner-border spinner-border-sm me-1"></span>
                                Confirmar {{ movimentacaoTipo === 'retirada' ? 'Retirada' : 'Depósito' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- MODAL: CONFERÊNCIA                                    -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="modal fade" id="modalConferencia" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form @submit.prevent="confirmarConferencia">
                        <div class="modal-header border-0 pb-0">
                            <div class="d-flex align-items-center gap-2">
                                <div class="action-icon-btn btn-conferencia"
                                    style="width:40px;height:40px;border-width:1px;">
                                    <span class="iconify" data-icon="solar:clipboard-check-bold-duotone"
                                        style="font-size:18px;"></span>
                                </div>
                                <h5 class="modal-title mb-0">Conferência de Caixa</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center py-2">
                                <span class="iconify text-primary mb-2" data-icon="solar:shield-check-bold-duotone"
                                    style="font-size:48px;"></span>
                                <h6 class="fw-semibold mb-1">Confirmar Conferência e Fechamento?</h6>
                                <p class="text-muted small mb-3">Ao confirmar, o caixa será fechado. Esta ação
                                    registrará os resumos de pagamento nas movimentações e não poderá ser desfeita.</p>
                                <div class="mt-3 p-3 rounded-3 border" style="background:#f8f9fa;">
                                    <h6 class="small fw-semibold text-start mb-2">Resumo de Formas de Pagamento</h6>
                                    <table class="table table-sm table-borderless text-start align-middle mb-0">
                                        <thead class="border-bottom small text-muted">
                                            <tr>
                                                <th>MÉTODO</th>
                                                <th class="text-end">VALOR CONSOLIDADO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="grupo in pagamentosAgrupados" :key="grupo.forma_pagamento_id">
                                                <td class="fw-medium">{{ grupo.nome }}</td>
                                                <td class="text-end text-success fw-medium">R$ {{
                                                    formatarMoeda(grupo.valor) }}</td>
                                            </tr>
                                            <tr v-if="pagamentosAgrupados.length === 0">
                                                <td colspan="2" class="text-center text-muted py-3 small">Nenhum
                                                    pagamento registrado</td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="border-top">
                                            <tr>
                                                <th class="pt-2">Saldo Final Estimado:</th>
                                                <th class="pt-2 text-end text-primary">R$ {{
                                                    formatarMoeda(saldoFinalEstimado) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-danger rounded-pill px-4"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4" :disabled="salvando">
                                <span v-if="salvando" class="spinner-border spinner-border-sm me-1"></span>
                                <span class="iconify me-1" data-icon="solar:check-circle-bold-duotone"
                                    style="font-size:16px;"></span>
                                Confirmar e Fechar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- /v-else -->
</div><!-- /app-caixa-view -->

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/vue-masks.js?v=2.5') ?>
<script>
    const elApp = document.getElementById('app-caixa-view');
    const csrfToken = elApp.getAttribute('data-csrf');
    const rotasData = JSON.parse(elApp.getAttribute('data-routes') || '{}');

    const app = Vue.createApp({
        directives: {
            monetario: {
                mounted(el) {
                    if (window.VueMasks && window.VueMasks.aplicarMascaraMonetariaCustom) {
                        window.VueMasks.aplicarMascaraMonetariaCustom(el);
                    }
                }
            }
        },
        data() {
            return {
                caixa: {},
                routes: rotasData,
                formasPagamento: {},
                csrfToken,
                carregando: true,
                salvando: false,
                movimentacaoTipo: 'entrada',
                formMovimentacao: { valor: '', motivo: '', forma_pagamento_id: '' },
            };
        },

        async mounted() {
            await this.carregarDados();
        },

        computed: {
            saldoAtual() {
                let saldo = parseFloat(this.caixa.saldo_inicial || 0);
                (this.caixa.movimentacoes || []).forEach(mov => {
                    let val = Math.abs(parseFloat(mov.valor_apurado || 0));
                    saldo += (mov.situacao === 'retirada' || mov.situacao === 'R') ? -val : val;
                });
                return saldo;
            },
            totalMovimentacoes() {
                let total = 0;
                (this.caixa.movimentacoes || []).forEach(mov => {
                    let val = Math.abs(parseFloat(mov.valor_apurado || 0));
                    total += (mov.situacao === 'retirada' || mov.situacao === 'R') ? -val : val;
                });
                return total;
            },
            totalPagamentos() {
                return (this.caixa.pagamentos || []).reduce((acc, p) => acc + parseFloat(p.valor || 0), 0);
            },
            pagamentosAgrupados() {
                const grupos = {};
                (this.caixa.pagamentos || []).forEach(p => {
                    const id = p.forma_pagamento_id || 0;
                    const nome = p.forma_pagamento ? p.forma_pagamento.nome : 'Desconhecida';
                    if (!grupos[id]) grupos[id] = { forma_pagamento_id: id, nome, valor: 0 };
                    grupos[id].valor += parseFloat(p.valor || 0);
                });
                return Object.values(grupos);
            },
            saldoFinalEstimado() {
                return this.pagamentosAgrupados.reduce((acc, g) => acc + g.valor, 0);
            },
        },

        methods: {
            async carregarDados() {
                this.carregando = true;
                try {
                    const resp = await fetch(this.routes.visualizar, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await resp.json();
                    if (data.success) {
                        this.caixa = data.caixa;
                        this.formasPagamento = data.formas;
                    } else {
                        Swal.fire({ icon: 'error', title: 'Erro', text: data.message || 'Erro ao carregar dados.' });
                    }
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro ao carregar dados do caixa.' });
                } finally {
                    this.carregando = false;
                }
            },

            formatarMoeda(valor) {
                if (!valor && valor !== 0) return '0,00';
                return parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            formatarDataHora(dataStr) {
                if (!dataStr) return '—';
                return new Date(dataStr).toLocaleString('pt-BR');
            },

            parseValorBR(str) {
                if (!str) return 0;
                return parseFloat(String(str).replace(/\./g, '').replace(',', '.')) || 0;
            },

            abrirModalMovimentacao(tipo) {
                this.movimentacaoTipo = tipo;
                this.formMovimentacao = { valor: '', motivo: '', forma_pagamento_id: '' };
                new bootstrap.Modal(document.getElementById('modalMovimentacao')).show();
            },

            abrirModalConferencia() {
                new bootstrap.Modal(document.getElementById('modalConferencia')).show();
            },

            async salvarMovimentacao() {
                const valor = this.parseValorBR(this.formMovimentacao.valor);
                if (valor <= 0) {
                    Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Informe um valor válido maior que zero.' });
                    return;
                }
                if (!this.formMovimentacao.forma_pagamento_id) {
                    Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Informe a forma de pagamento.' });
                    return;
                }

                this.salvando = true;
                try {
                    const formData = new FormData();
                    formData.append('_csrfToken', this.csrfToken);
                    formData.append('valor', valor);
                    formData.append('motivo', this.formMovimentacao.motivo);
                    formData.append('tipo', this.movimentacaoTipo);
                    formData.append('forma_pagamento_id', this.formMovimentacao.forma_pagamento_id);

                    const resp = await fetch(this.routes.movimentar, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const data = await resp.json();
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('modalMovimentacao'))?.hide();
                        await Swal.fire({ icon: 'success', title: 'Sucesso', text: 'Movimentação registrada com sucesso.', timer: 1500, showConfirmButton: false });
                        await this.carregarDados();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Erro', text: data.message || 'Erro ao registrar movimentação.' });
                    }
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro ao registrar movimentação.' });
                } finally {
                    this.salvando = false;
                }
            },

            async confirmarConferencia() {
                const confirmacao = await Swal.fire({
                    icon: 'question',
                    title: 'Confirmar conferência?',
                    text: 'O caixa será fechado e não poderá ser reaberto.',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, fechar caixa',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                });

                if (!confirmacao.isConfirmed) return;

                this.salvando = true;
                try {
                    const formData = new FormData();
                    formData.append('_csrfToken', this.csrfToken);
                    formData.append('pagamentos', JSON.stringify(this.pagamentosAgrupados));

                    const resp = await fetch(this.routes.conferir, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const data = await resp.json();
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('modalConferencia'))?.hide();
                        await Swal.fire({ icon: 'success', title: 'Caixa fechado!', text: 'Conferência realizada com sucesso.', timer: 1500, showConfirmButton: false });
                        await this.carregarDados();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Erro', text: data.message || 'Erro ao conferir caixa.' });
                    }
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro ao conferir caixa.' });
                } finally {
                    this.salvando = false;
                }
            },
        }
    });

    app.mount('#app-caixa-view');
</script>