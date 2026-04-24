<?php
$this->BreadcrumbsPlus->add([['title' => 'Caixas']]);
?>
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Html->css('index') ?>

<?= $this->Html->css('barra-filtro') ?>

<!-- Vue App Container -->
<div id="app" v-cloak
    data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>"
    data-entity-name="o Caixa"
    data-routes='<?= json_encode([
        'search' => $this->Url->build(['controller' => 'Caixas', 'action' => 'search']),
        'visualizar'   => $this->Url->build(['controller' => 'Caixas', 'action' => 'visualizar']),
        'edit'   => $this->Url->build(['controller' => 'Caixas', 'action' => 'edit']),
        'delete' => $this->Url->build(['controller' => 'Caixas', 'action' => 'delete'])
    ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <div class="card w-100 position-relative overflow-x-hidden">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">

                <!-- Título -->
                <div class="d-flex mb-3 align-items-center">
                    <h4 class="card-title mb-0"><?= __('Caixas') ?></h4>
                </div>

                <!-- ── Linha de filtros ── -->
                <div class="row align-items-center g-2 mb-2">

                    <!-- Busca textual -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <filtro-busca v-model="query" @search="debouncedSearch"></filtro-busca>
                    </div>

                    <!-- Grupo datas -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <filtro-periodo v-model:inicio="dataInicio" v-model:fim="dataFim" @change="aplicarFiltros"></filtro-periodo>
                    </div>

                    <!-- Situação -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <select v-model="situacaoFiltro"
                            class="form-select select-situacao w-100"
                            @change="aplicarFiltros">
                            <option value="">Todas situações</option>
                            <option value="aberto">Em aberto</option>
                            <option value="fechado">Fechado</option>
                        </select>
                    </div>
                </div>

                <!-- ── Filtros ativos ── -->
                <filtro-badges :query="query" :data-inicio="dataInicio" :data-fim="dataFim" 
                    :tem-filtros-ativos="temFiltrosAtivos" @remover-filtro="removerFiltro" @limpar-filtros="limparFiltros">
                    
                    <!-- Situação (Badge Extra) -->
                    <div v-if="situacaoFiltro" class="filtro-badge">
                        <i class="ti ti-cash" style="font-size:0.8rem;"></i>
                        <span>{{ textoSituacao }}</span>
                        <button class="btn-remover" @click="removerFiltro('situacao')">✕</button>
                    </div>
                </filtro-badges>

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
                                
                                <th>Usuário</th>
                                <th>Abertura</th>
                                <th>Saldo Inicial</th>
                                <th>Fechamento</th>
                                <th>Valor Fechamento</th>
                                <th>Conferente</th>
                                <th>Situação</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in filteredItems" :key="item.id">
                               
                                <td>{{ item.usuario ? item.usuario.nome : '' }}</td>
                                <td>{{ formatarDataHoraBR(item.data_abertura) }}</td>
                                <td class=" fw-medium">R$ {{ formatarMoeda(item.saldo_inicial) }}</td>
                                <td>{{ formatarDataHoraBR(item.data_fechamento) }}</td>
                                <td class=" fw-medium">R$ {{ formatarMoeda(item.saldo_final) }}</td>
                                <td>{{ item.conferente ? item.conferente.nome : '' }}</td>
                                <td>
                                    <span v-if="!item.situacao || item.situacao === 'aberto'"
                                        class="badge bg-success-subtle border border-success text-success">
                                        Aberto
                                    </span>
                                    <span v-else class="badge bg-secondary-subtle border border-secondary text-secondary">
                                        {{ capitalizar(item.situacao) }}
                                    </span>
                                </td>
                                <td style="width:8%;">
                                    <div class="dropdown">
                                        <button
                                            class="btn bg-transparent text-primary d-inline-flex align-items-center justify-content-center"
                                            type="button" :id="'dropdownMenu-' + item.id"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" :aria-labelledby="'dropdownMenu-' + item.id">
                                            <li>
                                                <a class="dropdown-item" :href="`${routes.visualizar}/${item.id}`">
                                                    <span class="iconify text-gray" data-icon="solar:eye-bold-duotone"style="font-size:15px;"></span> Visualizar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" :href="`${routes.edit}/${item.id}`">
                                                    <span class="iconify text-gray" data-icon="solar:pen-bold-duotone"style="font-size:15px;"></span> Editar
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger"
                                                    @click="confirmarExclusao(item)">
                                                    <span class="iconify text-danger" data-icon="solar:trash-bin-minimalistic-bold-duotone" style="font-size:15px;"></span> Excluir
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredItems.length === 0 && !carregando">
                                <td colspan="9" class="text-center text-muted py-4">Nenhum caixa encontrado</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="p-3">
                        <?= $this->element('vue_pagination') ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/vue-index.js?v=' . filemtime(WWW_ROOT . 'js/vue/vue-index.js')) ?>
<script>
    createVueIndex();
</script>