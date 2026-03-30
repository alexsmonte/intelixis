<?php
/**  

$this->BreadcrumbsPlus->add([['title' => 'Ccustos']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Ccustos') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Ccusto', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                                                <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
                        <div class="row">

                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('estabelecimento_id', ['label'=>'estabelecimento_id','options' => $estabelecimentos, 'empty' => true]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('name', ['label'=>'name', 'class'=>'form-control']); ?>
                            </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('parent_id', ['label'=>'parent_id','options' => $parentCcustos, 'empty' => true]); ?>
                                </div>                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-2 offset-md-10  mb-3">
                            <button type="button" class="justify-content-center w-100 btn btn-primary d-flex align-items-center">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4 border rounded-1">
                <table class="table table-striped text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                    <tr>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('estabelecimento_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('name') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('parent_id') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ccustos as $ccusto): ?>
                    <tr>
                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($ccusto->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $ccusto->hasValue('estabelecimento') ? $this->Html->link($ccusto->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $ccusto->estabelecimento->id]) : '' ?></p></td>
                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= h($ccusto->name) ?> </p></td>
                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $ccusto->hasValue('parent_ccusto') ? $this->Html->link($ccusto->parent_ccusto->name, ['controller' => 'Ccustos', 'action' => 'view', $ccusto->parent_ccusto->id]) : '' ?></p></td>
                                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $ccusto->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $ccusto->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $ccusto->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $ccusto->id),
                                        ]
                                        ) ?>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
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
*/
$this->BreadcrumbsPlus->add([['title' => 'Centros de Custos']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Html->css('index') ?>




<!-- Modal Visualizar Profissional -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Detalhes do Serviço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-start" id="modal-body-content">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<!-- Vue App Container -->
<div id="app" data-entity='<?= json_encode($ccustos->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
    data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="o ccusto" data-routes='<?= json_encode([
          'search' => $this->Url->build(['controller' => 'Ccustos', 'action' => 'search']),
          'view' => $this->Url->build(['controller' => 'Ccustos', 'action' => 'visualizar']),
          'edit' => $this->Url->build(['controller' => 'Ccustos', 'action' => 'editar']),
          'delete' => $this->Url->build(['controller' => 'Ccustos', 'action' => 'delete'])
      ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <div class="card w-100 position-relative overflow-hidden">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">

                <!-- Título e menu -->
                <div class="d-flex mb-3 align-items-center">
                    <h4 class="card-title mb-0"><?= __('Serviços') ?></h4>

                </div>

                <!-- Busca -->
                <!-- Busca -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <form @submit.prevent="onSearch" class="mb-0">
                            <div class="input-group">
                                <input v-model="query" type="text" class="form-control form-control-lg placeholdert"
                                    placeholder="Buscar por nome ou email">
                                <button class="btn bg-primary-subtle text-primary border-primary" type="submit"
                                    data-bs-toggle="tooltip" title="Pesquisar">
                                    <i class="ti ti-search fs-5"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div class="col-md-6 d-flex justify-content-end">
                        <button class="btn btn-novo bg-primary-subtle text-primary border-primary" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu animated flipInX">
                            <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Centro de custo', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' => false]) ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Tabela -->
                <div class="table-responsive-sm mb-4 border rounded-1">
                    <table class="table table-striped text-nowrap mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Parent</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in filteredItems" :key="item.id">
                                <td style="width:40%;">{{ item.name }}</td>
                                <td>{{ item.parent_ccusto?.name}} </td>
                              
                                
                                <td style="width:8%;">
                                    <div class="d-flex  align-items-center gap-2" style="min-width:120px;">





                                        <button
                                            class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fs-5 fa fa-bars"></i>
                                        </button>
                                        <ul class="dropdown-menu animated flipInX">

                                            <li>
                                                <button class="dropdown-item btn btn-primary btn-md"
                                                    @click="viewEntity(item.id)">Visualizar</button>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" :href="`${routes.edit}/${item.id}`">Editar</a>
                                            </li>
                                            <li>
                                                <form :action="`${routes.delete}/${item.id}`" method="post"
                                                    @submit.prevent="confirmarExclusao($event,item)">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_csrfToken" :value="csrfToken">
                                                    <button type="submit"
                                                        class="dropdown-item btn btn-primary btn-md">Excluir</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="items.length === 0">
                                <td colspan="8" class="text-center text-muted">Nenhum serviço encontrado</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginador -->
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
</div>


<!-- Vue 3  -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<!-- Import do JS genérico -->
<?= $this->Html->script('vue/app') ?>