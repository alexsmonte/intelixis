<?php
$this->BreadcrumbsPlus->add([['title' => 'Locais']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Html->css('index') ?>

<div id="app" 
    data-entity='<?= json_encode($locais->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
    data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>"
    data-entity-name="o Local"
    data-routes='<?= json_encode([
        'search' => $this->Url->build(['controller' => 'Locais', 'action' => 'search']),
        'edit' => $this->Url->build(['controller' => 'Locais', 'action' => 'editar']),
        'delete' => $this->Url->build(['controller' => 'Locais', 'action' => 'delete']),
        'novo' => $this->Url->build(['controller' => 'Locais', 'action' => 'novo'])
    ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <div class="card w-100 position-relative overflow-hidden">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">

                <!-- Título -->
                <div class="d-flex mb-3 align-items-center">
                    <h4 class="card-title mb-0"><?= __('Locais') ?></h4>
                </div>

                <!-- Busca e botão Adicionar -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <form @submit.prevent="onSearch" class="mb-0">
                            <div class="input-group">
                                <input v-model="query" type="text" class="form-control form-control-lg placeholdert"
                                       placeholder="Buscar por nome">
                                <button class="btn bg-primary-subtle text-primary border-primary" type="submit"
                                        data-bs-toggle="tooltip" title="Pesquisar">
                                    <i class="ti ti-search fs-5"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end">
                        <button class="btn btn-novo bg-primary-subtle text-primary border-primary" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu animated flipInX">
                            <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Local', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' => false]) ?>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Cards de Locais -->
                <div class="row g-3 mt-3">
                    <div v-if="items.length > 0" v-for="local in filteredItems" :key="local.id" class="col-md-3">
                        <div class="card h-100 shadow-sm rounded-4 card-local d-flex flex-column">
                            <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(local.endereco)}`"
                               target="_blank" class="map-link">
                                <div class="map-container">
                                    <iframe width="100%" height="150" frameborder="0" style="border:0"
                                            :src="`https://maps.google.com/maps?q=${encodeURIComponent(local.endereco)}&z=15&output=embed`"
                                            allowfullscreen>
                                    </iframe>
                                </div>
                            </a>
                            <div class="card-body d-flex flex-column flex-grow-1">
                                <h5 class="card-title fw-bold text-truncate">{{ local.nome }}</h5>
                                <p class="card-text d-flex align-items-center mb-2">
                                    <i class="ti ti-map-pin text-primary me-2"></i>
                                    <span class="text-truncate" style="max-width: calc(100% - 1.5rem);">{{ local.endereco }}</span>
                                </p>
                                <span v-if="local.ativar" class="badge bg-success-subtle border border-success text-success mt-auto">Ativo</span>
                                <span v-else class="badge bg-danger-subtle border border-danger text-danger mt-auto">Inativo</span>

                                <div class="mt-3 d-flex justify-content-end align-items-center gap-2">
                                    <!-- Editar -->
                                    <a :href="`${routes.edit}/${local.id}`" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <!-- Excluir -->
                                    <form :action="`${routes.delete}/${local.id}`" method="post" @submit.prevent="confirmarExclusao($event)">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_csrfToken" :value="csrfToken">
                                        <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Excluir">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Adicionar Novo -->
                    <div class="col-md-3">
                        <div class="card h-100 shadow-sm rounded-4 card-add d-flex justify-content-center align-items-center card-local">
                            <a :href="routes.novo" class="text-decoration-none text-muted text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ti ti-circle-plus ti-xl mb-2 text-primary"></i>
                                    <span>Adicionar Novo Local</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div v-if="items.length === 0" class="col-12">
                        <div class="alert alert-info text-center">Nenhum local encontrado.</div>
                    </div>
                </div>

                <!-- Paginator -->
                <div class="paginator mt-4">
                    <ul class="pagination justify-content-center">
                        <?= $this->Paginator->first('<< ') ?>
                        <?= $this->Paginator->prev('< ') ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(' >') ?>
                        <?= $this->Paginator->last(' >>') ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.map-container {
    position: relative;
    overflow: hidden;
    border-top-left-radius: calc(.25rem - 1px);
    border-top-right-radius: calc(.25rem - 1px);
}
.card-title { font-size: 1rem; }
.card-text { font-size: 0.95rem; }
.card-local {
    min-height: 350px; /* garante mesmo tamanho para todos os cards */
}
.card-add {
    border-style: dashed;
    border-color: #ced4da;
    background-color: #f8f9fa;
}
.card-add:hover { background-color: #e9ecef; }
.ti-xl { font-size: 2.5rem; }
.text-truncate { 
    white-space: wrap; 
    overflow: hidden; 
    text-overflow: ellipsis; 
}
</style>

<!-- Vue 3 -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<!-- JS centralizado -->
<?= $this->Html->script('vue/app') ?>
