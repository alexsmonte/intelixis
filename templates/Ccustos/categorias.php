<?php
/**  


*/
$this->BreadcrumbsPlus->add([['title' => 'Centros de Custos']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Html->css('index') ?>
<?
//debug ($ccustos);
//debug ($parentCcustos);
//exit;
?>





<!-- Vue App Container -->
<div id="app" data-entity='<?= json_encode($ccustos->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
    data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="a categoria" data-routes='<?= json_encode([
          'search' => $this->Url->build(['controller' => 'Ccustos', 'action' => 'search']),
          'view' => $this->Url->build(['controller' => 'Ccustos', 'action' => 'visualizar']),
          'edit' => $this->Url->build(['controller' => 'Ccustos', 'action' => 'categorias']),
          'delete' => $this->Url->build(['controller' => 'Ccustos', 'action' => 'delete'])
      ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <div class="card w-100 position-relative overflow-hidden">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">
                <!-- LINHA PRINCIPAL COM DUAS COLUNAS -->
                <div class="row">
                    <!-- COLUNA DO FORMULÁRIO (ESQUERDA) -->
                    <div class="col-md-4">

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <?= isset($ccusto) && $ccusto->id ? 'Editar Categoria' : 'Adicionar Categoria' ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Configura a ação do formulário
                                // Para edição: enviar para categorias/id
                                // Para novo: enviar para categorias
                                $formAction = isset($ccusto) && $ccusto->id ?
                                    ['action' => 'categorias', $ccusto->id] :
                                    ['action' => 'categorias'];

                                // Para edição, precisa usar o método PUT
                                $options = ['class' => 'form-vertical'];
                                if (isset($ccusto) && $ccusto->id) {
                                    $options['type'] = 'put'; // Para edição
                                }

                                echo $this->Form->create($ccusto ?? null, array_merge([
                                    'url' => $formAction
                                ], $options));
                                ?>

                                <!-- Campo oculto para método PUT se for edição -->
                                <?php if (isset($ccusto) && $ccusto->id): ?>
                                    <?= $this->Form->hidden('id', ['value' => $ccusto->id]); ?>
                                <?php endif; ?>

                                <?= $this->Form->control('parent_id', [
                                    'label' => 'Selecione a Aba',
                                    'options' => $parentCcustos,
                                    'empty' => 'Selecione uma categoria',
                                    'class' => 'form-control mb-4',
                                    'value' => isset($ccusto) ? $ccusto->parent_id : '' // Garante o valor
                                ]); ?>

                                <?= $this->Form->control('name', [
                                    'label' => 'Nome da categoria',
                                    'class' => 'form-control mb-3',
                                    'placeholder' => 'Digite o nome da categoria',
                                    'required' => true,
                                    'value' => isset($ccusto) ? $ccusto->name : '' // Garante o valor
                                ]); ?>



                                <div class="d-flex gap-2">
                                    <?= $this->Form->button(isset($ccusto) && $ccusto->id ? 'Salvar Alterações' : 'Cadastrar', [
                                        'class' => 'btn btn-primary flex-grow-1'
                                    ]); ?>

                                    <?php if (isset($ccusto) && $ccusto->id): ?>
                                        <?= $this->Html->link(
                                            'Cancelar',
                                            ['action' => 'categorias'],
                                            ['class' => 'btn btn-secondary']
                                        ); ?>
                                    <?php endif; ?>
                                </div>

                                <?= $this->Form->end(); ?>
                            </div>
                        </div>
                    </div>
                    <!-- COLUNA DA TABELA (DIREITA) -->
                    <div class="col-md-8">
                        <!-- Título e menu -->
                        <div class="d-flex mb-3 align-items-center">
                            <h4 class="card-title mb-0"><?= __('Categorias de despesas') ?></h4>

                        </div>

                        <!-- Busca -->
                        <!-- Busca -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-6">
                                <form @submit.prevent="onSearch" class="mb-0">
                                    <div class="input-group">
                                        <input v-model="query" type="text"
                                            class="form-control form-control-lg placeholdert"
                                            placeholder="Buscar por nome ou tipo">
                                        <button class="btn bg-primary-subtle text-primary border-primary" type="submit"
                                            data-bs-toggle="tooltip" title="Pesquisar">
                                            <i class="ti ti-search fs-5"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <!-- Tabela -->
                        <div class="table-responsive-sm mb-4 border rounded-1">
                            <table class="table table-striped text-nowrap mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Tipo</th>
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
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fs-5 fa fa-bars"></i>
                                                </button>
                                                <ul class="dropdown-menu animated flipInX">

                                                    <li>
                                                        <a class="dropdown-item"
                                                            :href="`${routes.edit}/${item.id}`">Editar</a>
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
                                        <td colspan="8" class="text-center text-muted">Nenhum serviço encontrado
                                        </td>
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
    </div>
</div>


<!-- Vue 3  -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<!-- Import do JS genérico -->
<?= $this->Html->script('vue/app') ?>