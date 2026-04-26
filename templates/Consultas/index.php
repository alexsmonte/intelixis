<?php

$this->BreadcrumbsPlus->add([['title' => 'Consultas']]);

?>

<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Html->css('barra-filtro') ?>


<!-- Modal Visualizar Usuario -->
<div class="modal fade" id="novaConsulta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <?= $this->Form->create($consulta, ['url' => ['controller' => 'Consultas', 'action' => 'novo']]) ?>
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Nova consulta</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start" id="modal-body-content">

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <?= $this->Form->control('cliente_id', ['label' => 'Cliente', 'class' => 'selectpicker form-select', 'data-live-search' => '1', 'data-remote-url' => $this->Url->build(['controller' => 'Clientes', 'action' => 'localizar'], ['fullBase' => true])]); ?>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger-subtle text-danger " data-bs-dismiss="modal">
                    Fechar
                </button>
                <button type="submit" class="btn btn-success">
                    Salvar
                </button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>



<!-- Vue App Container -->
<div id="app" v-cloak data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="a Consulta"
    data-routes='<?= json_encode([
        'search' => $this->Url->build(['controller' => 'Consultas', 'action' => 'search']),
        'visualizar' => $this->Url->build(['controller' => 'Consultas', 'action' => 'visualizar']),
        'edit' => $this->Url->build(['controller' => 'Consultas', 'action' => 'editar']),
        'delete' => $this->Url->build(['controller' => 'Consultas', 'action' => 'delete'])
    ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <div class="card w-100 position-relative overflow-hidden">
        <div class="card-body p-4">
            <div class="d-flex mb-4 align-items-center">
                <h4 class="card-title mb-0">Consultas</h4>
                <div class="ms-auto flex-shrink-0">
                    <div class="dropdown">
                        <button class="btn bg-primary-subtle text-primary d-flex align-items-center"
                            data-bs-toggle="dropdown">
                            <span class="iconify" data-icon="solar:list-arrow-down-bold" style="font-size:22px;"></span>
                        </button>
                        <ul class="dropdown-menu animated flipInX">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal"
                                    data-bs-target="#novaConsulta">
                                    <span class="iconify text-gray me-2" data-icon="solar:add-circle-linear"
                                        style="font-size:16px;"></span> Nova Consulta
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ── Linha de filtros ── -->
            <div class="row align-items-center g-2 mb-2">

                <!-- Busca textual -->
                <div class="col-xl-4 col-lg-4 col-md-12">
                    <filtro-busca v-model="query" placeholder="Buscar por cliente, documento ou celular..."
                        @search="debouncedSearch"></filtro-busca>
                </div>

                <!-- Profissional -->
                <div class="col-xl-4 col-lg-4 col-md-12">
                    <select v-model="profissional_id" class="form-select select-situacao w-100"
                        @change="aplicarFiltros">
                        <option value="">Todos os profissionais</option>
                        <?php foreach ($profissionais as $id => $nome): ?>
                            <option value="<?= $id ?>"><?= $nome ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Período -->
                <div class="col-xl-4 col-lg-4 col-md-12">
                    <filtro-periodo v-model:inicio="dataInicio" v-model:fim="dataFim" @change="aplicarFiltros">
                    </filtro-periodo>
                </div>

            </div>

            <!-- ── Filtros ativos ── -->
            <filtro-badges :query="query" :data-inicio="dataInicio" :data-fim="dataFim"
                :tem-filtros-ativos="temFiltrosAtivos" @remover-filtro="removerFiltro" @limpar-filtros="limparFiltros">
                <div v-if="profissional_id !== ''" class="filtro-badge">
                    <i class="ti ti-user-star" style="font-size:0.8rem;"></i>
                    <span>Profissional: {{ getProfissionalNome(profissional_id) }}</span>
                    <button class="btn-remover" @click="removerFiltro('profissional_id')">✕</button>
                </div>
            </filtro-badges>


            <!-- ── Tabela ── -->
            <div class="table-responsive-sm mb-4 border rounded-1 position-relative">
                <div v-if="carregando" class="position-absolute top-50 start-50 translate-middle z-index-1">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>

                <table class="table table-hover text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4 bg-light">
                        <tr>
                            <th style="width:40%;">Cliente</th>
                            <th style="width:20%;">Profissional</th>
                            <th style="width:15%;">Data/Hora</th>
                            <th style="width:10%;" class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="items.length === 0 && !carregando">
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ti ti-search fs-7 d-block mb-2"></i>
                                    Nenhuma consulta encontrada
                                </div>
                            </td>
                        </tr>
                        <tr v-for="item in items" :key="item.id">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="ms-1">
                                        <h6 class="fs-4 fw-semibold mb-0">{{ item.cliente?.nome }}</h6>
                                        <small class="text-muted">{{ item.cliente?.documento || 'Sem CPF' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-semibold">
                                    {{ item.procedimentos && item.procedimentos.length > 0 &&
                                    item.procedimentos[0].profissional ? item.procedimentos[0].profissional.nome : '—'
                                    }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-dark">{{ formatarDataHoraBR(item.created) }}</span>
                                </div>
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
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>



<!-- Modal Visualizar -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Detalhes da Consulta</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Conteúdo via AJAX -->
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script(['vue/vue-index.js']); ?>

<script>
    createVueIndex({
        data() {
            return {
                profissional_id: '',
                items: <?= json_encode($consultas->toArray()) ?>,
                profissionais: <?= json_encode($profissionais) ?>,
                customFiltersMap: {
                    profissional_id: ''
                }
            }
        },
        methods: {
            buildSearchParams() {
                let extra = '';
                if (this.profissional_id) extra += `&profissional_id=${encodeURIComponent(this.profissional_id)}`;
                return extra;
            },
            getProfissionalNome(id) {
                return this.profissionais[id] || id;
            }
        }
    });
</script>
