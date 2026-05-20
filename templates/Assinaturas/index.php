<?php
$this->BreadcrumbsPlus->add([['title' => 'Assinaturas']]);
?>
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>
<?= $this->element('barra-titulo', ['title' => $title ?? 'Assinaturas']); ?>
<?= $this->Html->css('index') ?>
<?= $this->Html->css('barra-filtro') ?>

<!-- Vue App Container -->
<div id="app" v-cloak data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="a Assinatura"
    data-routes='<?= json_encode([
        'search' => $this->Url->build(['controller' => 'Assinaturas', 'action' => 'search']),
        'visualizar' => $this->Url->build(['controller' => 'Assinaturas', 'action' => 'view']),
        'edit' => $this->Url->build(['controller' => 'Assinaturas', 'action' => 'editar']),
        'delete' => $this->Url->build(['controller' => 'Assinaturas', 'action' => 'delete'])
    ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
    data-planos='<?= json_encode($planos, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
    data-contas='<?= json_encode($contas, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <div class="card w-100 position-relative overflow-x-hidden">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">

                <!-- Título e Menu -->
                <div class="d-flex mb-3 align-items-center justify-content-between">
                    <h4 class="card-title mb-0">
                        <?= __('Assinaturas') ?>
                    </h4>
                    <div class="col-md-6 d-flex justify-content-end">
                        <button
                            class="btn btn-novo bg-primary-subtle text-primary border-primary d-flex align-items-center justify-content-center"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="iconify" data-icon="solar:list-arrow-down-bold" style="font-size:22px;"></span>
                        </button>
                        <ul class="dropdown-menu animated flipInX">
                            <li>
                                <?= $this->Html->link('<span class="iconify text-gray me-2" data-icon="solar:add-circle-linear" style="font-size:16px;"></span> Nova Assinatura', ['action' => 'novo'], ['class' => 'dropdown-item d-flex align-items-center', 'escape' => false]) ?>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Linha de filtros -->
                <div class="row align-items-center g-2 mb-2">

                    <!-- Busca textual -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <filtro-busca v-model="query" placeholder="Buscar por CPF/CNPJ, Conta ou Plano..."
                            @search="debouncedSearch"></filtro-busca>
                    </div>

                    <!-- Plano -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <select v-model="planoFiltro" class="form-select w-100" @change="aplicarFiltros">
                            <option value="">Todos os Planos</option>
                            <option v-for="(nome, id) in planosMap" :value="id" :key="id">{{ nome }}</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <select v-model="statusFiltro" class="form-select w-100" @change="aplicarFiltros">
                            <option value="">Todos os status</option>
                            <option value="Ativa">Ativa</option>
                            <option value="Inativa">Inativa</option>
                            <option value="Pendente">Pendente</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>

                </div>

                <!-- Filtros ativos (Badges) -->
                <filtro-badges :query="query" :data-inicio="dataInicio" :data-fim="dataFim" 
                    :tem-filtros-ativos="temFiltrosAtivos" @remover-filtro="removerFiltro" @limpar-filtros="limparFiltros">
                    
                    <div v-if="statusFiltro !== ''" class="filtro-badge">
                        <span class="iconify" data-icon="solar:widget-4-line-duotone" style="font-size:0.8rem;"></span>
                        <span>Status: {{ statusFiltro }}</span>
                        <button class="btn-remover" @click="removerFiltro('status')">✕</button>
                    </div>

                    <div v-if="planoFiltro !== ''" class="filtro-badge">
                        <span class="iconify" data-icon="solar:book-2-line-duotone" style="font-size:0.8rem;"></span>
                        <span>Plano: {{ planosMap[planoFiltro] || planoFiltro }}</span>
                        <button class="btn-remover" @click="removerFiltro('plano')">✕</button>
                    </div>
                </filtro-badges>

                <!-- Tabela -->
                <div class="table-responsive-sm mb-4 border rounded-1 position-relative">
                    <div v-if="carregando"
                        class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center bg-white"
                        style="opacity:0.7; z-index:10;">
                        <i class="fa fa-spinner fa-spin fa-3x text-primary"></i>
                    </div>

                    <table class="table table-striped text-nowrap mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Cód/ID</th>
                                <th>Conta (CPF/CNPJ)</th>
                                <th>Plano</th>
                                <th>Vencimento</th>
                                <th>Desconto</th>
                                <th>Status</th>
                                <th>Criada em</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in filteredItems" :key="item.id">
                                <td>
                                    <span class="fw-semibold text-dark">#{{ item.id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column" v-if="item.conta">
                                        <span class="fw-semibold text-dark">{{ item.conta.nome }}</span>
                                        <small class="text-muted">{{ item.conta.cpf_cnpj }}</small>
                                    </div>
                                    <span v-else class="text-muted">—</span>
                                </td>
                                <td>
                                    <span v-if="item.plano" class="fw-semibold text-primary">{{ item.plano.nome }}</span>
                                    <span v-else class="text-muted">—</span>
                                </td>
                                <td>
                                    <span v-if="item.data_vencimento">{{ formatarDataBR(item.data_vencimento) }}</span>
                                    <span v-else class="text-muted">—</span>
                                </td>
                                <td>
                                    <span>{{ formatarMoeda(item.desconto || 0) }}</span>
                                </td>
                                <td>
                                    <span class="badge" :class="{
                                        'bg-success-subtle text-success border border-success': item.status?.toLowerCase() === 'ativo' || item.status?.toLowerCase() === 'ativa',
                                        'bg-danger-subtle text-danger border border-danger': item.status?.toLowerCase() === 'inativo' || item.status?.toLowerCase() === 'inativa' || item.status?.toLowerCase() === 'cancelado' || item.status?.toLowerCase() === 'cancelada',
                                        'bg-warning-subtle text-warning border border-warning': item.status?.toLowerCase() === 'pendente',
                                        'bg-secondary-subtle text-secondary border border-secondary': !['ativo', 'ativa', 'inativo', 'inativa', 'cancelado', 'cancelada', 'pendente'].includes(item.status?.toLowerCase())
                                    }">
                                        {{ item.status || 'Pendente' }}
                                    </span>
                                </td>
                                <td>
                                    {{ formatarDataHoraBR(item.created) }}
                                </td>
                                <td style="width:8%;">
                                    <div class="dropdown">
                                        <button
                                            class="btn bg-transparent text-primary d-inline-flex align-items-center justify-content-center"
                                            type="button" :id="'dropdownMenu-' + item.id" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end"
                                            :aria-labelledby="'dropdownMenu-' + item.id">
                                            <li>
                                                <a class="dropdown-item" :href="`${routes.visualizar}/${item.id}`">
                                                    <span class="iconify text-gray" data-icon="solar:eye-bold-duotone"
                                                        style="font-size:15px;"></span> Visualizar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" :href="`${routes.edit}/${item.id}`">
                                                    <span class="iconify text-gray" data-icon="solar:pen-bold-duotone"
                                                        style="font-size:15px;"></span> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger"
                                                    @click="confirmarExclusao(item)">
                                                    <span class="iconify text-danger"
                                                        data-icon="solar:trash-bin-minimalistic-bold-duotone"
                                                        style="font-size:15px;"></span> Excluir
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredItems.length === 0 && !carregando">
                                <td colspan="8" class="text-center text-muted py-4">Nenhuma assinatura encontrada</td>
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
    const el = document.getElementById('app');
    const planosMap = JSON.parse(el.getAttribute('data-planos') || '{}');
    const contasMap = JSON.parse(el.getAttribute('data-contas') || '{}');

    createVueIndex({
        customFilters: {
            statusFiltro: 'status',
            planoFiltro: 'plano_id',
            contaFiltro: 'conta_id'
        },
        data() {
            return {
                planosMap,
                contasMap,
                statusFiltro: '',
                planoFiltro: '',
                contaFiltro: ''
            };
        },
        computed: {
            temFiltrosAtivos() {
                return this.query !== '' || this.statusFiltro !== '' || this.planoFiltro !== '';
            }
        },
        methods: {
            removerFiltro(tipo) {
                if (tipo === 'texto') this.query = '';
                if (tipo === 'status') this.statusFiltro = '';
                if (tipo === 'plano') this.planoFiltro = '';
                this.aplicarFiltros();
            },
            limparFiltros() {
                this.query = '';
                this.statusFiltro = '';
                this.planoFiltro = '';
                this.aplicarFiltros();
            },
            getItemNome(item) {
                if (item.conta) {
                    return `Assinatura de ${item.conta.nome}`;
                }
                return `Assinatura #${item.id}`;
            }
        }
    });
</script>