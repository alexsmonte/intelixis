<?php
$this->BreadcrumbsPlus->add([['title' => 'Pagamentos']]);
?>
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Html->css('index')?>
<?= $this->Html->css('barra-filtro')?>

<!-- Vue App Container -->
<div id="app" v-cloak data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="o Pagamento"
    data-routes='<?= json_encode([
    'search'    => $this->Url->build(['controller' => 'Pagamentos', 'action' => 'search']),
    'view'      => $this->Url->build(['controller' => 'Pagamentos', 'action' => 'view']),
    'edit'      => $this->Url->build(['controller' => 'Pagamentos', 'action' => 'edit']),
    'delete'    => $this->Url->build(['controller' => 'Pagamentos', 'action' => 'delete'])
], JSON_HEX_APOS | JSON_HEX_QUOT)?>'
    data-forma-pagamentos='
    <?= json_encode($formaPagamentos, JSON_HEX_APOS | JSON_HEX_QUOT)?>'>

    <div class="card w-100 position-relative overflow-x-hidden mb-4 shadow-sm">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">

                <!-- ══ Seção: Extrato de pagamentos ══ -->
                <div class="d-flex mb-3 align-items-center">
                    <h4 class="card-title mb-0">
                        <?= __('Extrato de pagamentos')?>
                    </h4>
                </div>

                <!-- ── Linha de filtros ── -->
                <div class="row align-items-center g-2 mb-2">

                    <!-- Descrição -->
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <filtro-busca v-model="query" @search="debouncedSearch"></filtro-busca>
                    </div>

                    <!-- Meio de pagamento -->
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <select v-model="formaPagamentoFiltro" class="form-select form-select-lg rounded-pill w-100"
                            @change="aplicarFiltros">
                            <option value="" disabled selected>Forma de pagamento...</option>
                            <option v-for="(nome, id) in formasPagamento" :key="id" :value="id">{{ nome }}</option>
                        </select>
                    </div>

                    <!-- Período de baixa -->
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <filtro-periodo v-model:inicio="dataInicio" v-model:fim="dataFim" @change="aplicarFiltros"></filtro-periodo>
                    </div>

                    <!-- Antecipado -->
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <select v-model="antecipadoFiltro" class="form-select form-select-lg rounded-pill w-100"
                            @change="aplicarFiltros">
                            <option value="" disabled selected>Antecipação...</option>
                            <option value="1">Antecipado</option>
                            <option value="0">Não antecipado</option>
                        </select>
                    </div>
                </div>

                <!-- ── Filtros ativos ── -->
                <filtro-badges :query="query" :data-inicio="dataInicio" :data-fim="dataFim" 
                    :tem-filtros-ativos="temFiltrosAtivos" @remover-filtro="removerFiltro" @limpar-filtros="limparFiltros">
                    
                    <!-- Forma de pagamento (Badge Extra) -->
                    <div v-if="formaPagamentoFiltro" class="filtro-badge">
                        <i class="ti ti-credit-card" style="font-size:0.8rem;"></i>
                        <span>{{ formasPagamento[formaPagamentoFiltro] || formaPagamentoFiltro }}</span>
                        <button class="btn-remover" @click="removerFiltro('formaPagamentoFiltro')">✕</button>
                    </div>

                    <!-- Antecipado (Badge Extra) -->
                    <div v-if="antecipadoFiltro" class="filtro-badge">
                        <i class="ti ti-clock" style="font-size:0.8rem;"></i>
                        <span>{{ antecipadoFiltro == '1' ? 'Antecipado' : 'Não antecipado' }}</span>
                        <button class="btn-remover" @click="removerFiltro('antecipadoFiltro')">✕</button>
                    </div>
                </filtro-badges>

                <hr class="my-4">

                <!-- ══ Seção: Exibir pagamentos ══ -->
                <div class="d-flex mb-3 align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <?= __('Listagem de Pagamentos')?>
                    </h5>
                    <div class="text-end text-muted">
                        <span class="me-3">Valor total: <strong>R$ {{ formatarMoeda(valorTotal) }}</strong></span>
                        <span>Total de taxas: <strong>R$ {{ formatarMoeda(totalTaxas) }}</strong></span>
                    </div>
                </div>

                <!-- ── Tabela ── -->
                <div class="table-responsive-sm mb-4 border rounded-1 position-relative">
                    <div v-if="carregando"
                        class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center bg-white"
                        style="opacity:0.7; z-index:10;">
                        <i class="fa fa-spinner fa-spin fa-3x text-primary"></i>
                    </div>

                    <table class="table table-striped text-nowrap mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="text-primary">Descrição</th>
                                <th class="text-primary">Meio de pagamento</th>
                                <th class="text-primary">Valor</th>
                                <th class="text-primary">Data de baixa</th>
                                <th class="text-primary">Taxas</th>
                                <th class="text-primary">Data da liquidação</th>
                                <th class="text-primary">Antecipado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in filteredItems" :key="item.id">
                                <td>{{ item.financa ? item.financa.descricao : '' }}</td>
                                <td>{{ item.forma_pagamento ? item.forma_pagamento.nome : '' }}</td>
                                <td class="fw-medium">R$ {{ formatarMoeda(item.valor) }}</td>
                                <td>{{ item.financa ? formatarDataBR(item.financa.data_baixa) : '' }}</td>
                                <td>R$ {{ formatarMoeda(item.taxa) }}</td>
                                <td>{{ item.data_antecipacao ? formatarDataBR(item.data_antecipacao) : '' }}</td>
                                <td>

                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox"
                                            :class="{'bg-success border-success': !!item.antecipar}"
                                            :checked="!!item.antecipar" :disabled="!!item.antecipar"
                                            @click.prevent="confirmarPagamento(item)">
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredItems.length === 0 && !carregando">
                                <td colspan="7" class="text-center text-muted py-4">Nenhum pagamento encontrado</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="p-3">
                        <?= $this->element('vue_pagination')?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/vue-index.js?v=' . filemtime(WWW_ROOT . 'js/vue/vue-index.js'))?>

<script>
    createVueIndex({
        customFilters: {
            formaPagamentoFiltro: 'forma_pagamento_id',
            antecipadoFiltro: 'antecipado'
        },
        data() {
            return {
                formasPagamento: JSON.parse(
                    document.getElementById('app').getAttribute('data-forma-pagamentos') || '{}'
                )
            };
        },
        methods: {

            // --- Lógica de Antecipação (Corrigida) ---
            confirmarPagamento(item) {
                // Se já estiver antecipado, não faz nada
                if (!!item.antecipar) return;

                Swal.fire({
                    title: 'Confirmar Antecipação?',
                    text: "Deseja confirmar o pagamento antecipado? Esta ação não pode ser desfeita.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, confirmar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.enviarConfirmacao(item);
                    }
                });
            },

            enviarConfirmacao(item) {
                const url = '<?= $this->Url->build(["controller" => "Pagamentos", "action" => "confirmarPagamento"])?>/' + item.id;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '<?= $this->request->getAttribute("csrfToken")?>',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            item.antecipar = true; // Trava o botão visualmente
                            Swal.fire('Sucesso!', 'Pagamento confirmado.', 'success');
                        } else {
                            Swal.fire('Erro!', data.message || 'Erro ao processar.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro no fetch:', error);
                        Swal.fire('Erro!', 'Falha de comunicação com o servidor.', 'error');
                    });
            }
        }
    });
</script>