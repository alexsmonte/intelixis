<?php
$this->BreadcrumbsPlus->add([['title' => 'Locais']]);
?>
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>
<?= $this->element('barra-titulo', ['title' => $title ?? 'Locais']); ?>
<?= $this->Html->css('index')?>
<?= $this->Html->css('barra-filtro')?>

<!-- Vue App Container -->
<div id="app" v-cloak data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="o Local"
    data-routes='<?= json_encode([
    'search'     => $this->Url->build(['controller' => 'Locais', 'action' => 'search']),
    'visualizar' => $this->Url->build(['controller' => 'Locais', 'action' => 'visualizar']),
    'edit'       => $this->Url->build(['controller' => 'Locais', 'action' => 'editar']),
    'delete'     => $this->Url->build(['controller' => 'Locais', 'action' => 'delete']),
    'novo'       => $this->Url->build(['controller' => 'Locais', 'action' => 'novo'])
], JSON_HEX_APOS | JSON_HEX_QUOT)?>'>

    <div class="card w-100 position-relative overflow-x-hidden">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">

                <!-- Título -->
                <div class="d-flex mb-3 align-items-center justify-content-between">
                    <h4 class="card-title mb-0">
                        <?= __('Locais')?>
                    </h4>
                    <div class="col-md-6 d-flex justify-content-end">
                        <button
                            class="btn btn-novo bg-primary-subtle text-primary border-primary d-flex align-items-center justify-content-center"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="iconify" data-icon="solar:list-arrow-down-bold" style="font-size:22px;"></span>
                        </button>
                        <ul class="dropdown-menu animated flipInX">
                            <li>
                                <?= $this->Html->link('<span class="iconify text-gray me-2" data-icon="solar:add-circle-linear" style="font-size:16px;"></span> Novo Local', ['action' => 'novo'], ['class' => 'dropdown-item d-flex align-items-center', 'escape' => false])?>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- ── Linha de filtros ── -->
                <div class="row align-items-center g-2 mb-2">

                    <!-- Busca textual -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <filtro-busca v-model="query" placeholder="Buscar por local ou endereço..." @search="debouncedSearch"></filtro-busca>
                    </div>

                    <!-- Status -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <select v-model="situacaoFiltro" class="form-select select-situacao w-100"
                            @change="aplicarFiltros">
                            <option value="">Todos os status</option>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>

                </div>

                <!-- ── Filtros ativos ── -->
                <filtro-badges :query="query" :data-inicio="dataInicio" :data-fim="dataFim"
                    :tem-filtros-ativos="temFiltrosAtivos" @remover-filtro="removerFiltro" @limpar-filtros="limparFiltros">
                    <div v-if="situacaoFiltro !== ''" class="filtro-badge">
                        <i class="ti ti-map-pin" style="font-size:0.8rem;"></i>
                        <span>{{ textoSituacao }}</span>
                        <button class="btn-remover" @click="removerFiltro('situacao')">✕</button>
                    </div>
                </filtro-badges>

                <!-- ── Grid de Cards (Locais) ── -->
                <div class="position-relative min-vh-50 mt-4">
                    <!-- Overlay de loading -->
                    <div v-if="carregando"
                        class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center bg-white rounded-3"
                        style="opacity:0.8; z-index:10; min-height: 200px;">
                        <i class="fa fa-spinner fa-spin fa-3x text-primary"></i>
                    </div>

                    <div class="row g-3">
                        <!-- Cards Iterativos -->
                        <div v-for="local in filteredItems" :key="local.id" class="col-md-3">
                            <div class="card h-100 shadow-sm rounded-4 card-local d-flex flex-column border-0"
                                style="background:#fcfcfc;">
                                <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(local.endereco)}`"
                                    target="_blank" class="map-link">
                                    <div class="map-container">
                                        <iframe width="100%" height="150" frameborder="0"
                                            style="border:0; pointer-events:none;"
                                            :src="`https://maps.google.com/maps?q=${encodeURIComponent(local.endereco)}&z=15&output=embed`"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </a>
                                <div class="card-body d-flex flex-column flex-grow-1">
                                    <h5 class="card-title fw-bold text-truncate mb-2" :title="local.nome">{{ local.nome
                                        }}</h5>

                                    <p class="card-text d-flex align-items-start mb-3 text-muted"
                                        style="font-size:0.85rem; line-height:1.4;">
                                        <i class="ti ti-map-pin text-primary me-2 mt-1"></i>
                                        <span class="text-break"
                                            style="max-height: 2.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"
                                            :title="local.endereco">
                                            {{ local.endereco }}
                                        </span>
                                    </p>

                                    <!-- Tags/Status da parte inferior do card -->
                                    <div class="d-flex align-items-center mt-auto justify-content-between">

                                        <!-- Badge Status -->
                                        <div>
                                            <span v-if="local.ativar"
                                                class="badge bg-success-subtle border border-success text-success">
                                                Ativo
                                            </span>
                                            <span v-else
                                                class="badge bg-danger-subtle border border-danger text-danger">
                                                Inativo
                                            </span>
                                        </div>

                                        <!-- Ações Dropdown Canto Direito -->
                                        <div class="dropdown">
                                            <button
                                                class="btn bg-transparent text-primary d-inline-flex align-items-center justify-content-center p-1"
                                                type="button" :id="'dropdownMenu-' + local.id" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow"
                                                :aria-labelledby="'dropdownMenu-' + local.id">
                                                <li>
                                                    <a class="dropdown-item" :href="`${routes.visualizar}/${local.id}`">
                                                        <span class="iconify text-gray"
                                                            data-icon="solar:eye-bold-duotone"
                                                            style="font-size:15px;"></span> Visualizar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" :href="`${routes.edit}/${local.id}`">
                                                        <span class="iconify text-gray"
                                                            data-icon="solar:pen-bold-duotone"
                                                            style="font-size:15px;"></span> Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        @click="confirmarExclusao(local)">
                                                        <span class="iconify text-danger"
                                                            data-icon="solar:trash-bin-minimalistic-bold-duotone"
                                                            style="font-size:15px;"></span> Excluir
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Adicionar Novo Fim -->
                        <div class="col-md-3"
                            v-if="!carregando && items.length > 0 && pagination.page === pagination.pageCount">
                            <div
                                class="card h-100 shadow-sm rounded-4 card-add d-flex justify-content-center align-items-center card-local">
                                <a :href="routes.novo"
                                    class="text-decoration-none text-muted text-center w-100 h-100 d-flex flex-column justify-content-center">
                                    <span class="iconify mb-2 text-primary mx-auto" data-icon="solar:add-circle-linear"
                                        style="font-size: 3rem;"></span>
                                    <span class="fw-semibold">Adicionar Novo</span>
                                </a>
                            </div>
                        </div>

                        <!-- Estado Vazio -->
                        <div v-if="filteredItems.length === 0 && !carregando" class="col-12">
                            <div
                                class="alert alert-info text-center d-flex flex-column align-items-center justify-content-center py-5">
                                <span class="iconify mb-3" data-icon="solar:map-point-broken"
                                    style="font-size: 3rem;"></span>
                                <h5>Nenhum local encontrado</h5>
                                <p class="text-muted mb-3">Tente ajustar seus filtros ou cadastre um novo local.</p>
                                <a :href="routes.novo" class="btn btn-primary d-flex align-items-center gap-2">
                                    <span class="iconify" data-icon="solar:add-circle-linear"></span> Validar Novo Local
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paginação Padrão -->
                <div class="p-3 mt-3">
                    <?= $this->element('vue_pagination')?>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .map-container {
        position: relative;
        overflow: hidden;
        border-top-left-radius: calc(.5rem - 1px);
        border-top-right-radius: calc(.5rem - 1px);
        background: #e9ecef;
        /* skeleton loading cor base para o map */
    }

    .map-link:hover .map-container iframe {
        opacity: 0.85;
        transition: opacity 0.2s ease-in-out;
    }

    .card-local {
        min-height: 330px;
        border: 1px solid #ebf1f6 !important;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card-local:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
    }

    .card-add {
        border: 2px dashed #ced4da !important;
        background-color: transparent !important;
        cursor: pointer;
    }

    .card-add:hover {
        background-color: #f8f9fa !important;
        border-color: var(--bs-primary) !important;
    }
</style>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/vue-index.js?v=' . filemtime(WWW_ROOT . 'js/vue/vue-index.js'))?>
<script>
    createVueIndex({
        customFilters: {
            situacaoFiltro: 'ativar'
        }
    });
</script>