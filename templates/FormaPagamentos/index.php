<?php
/**  


*/
$this->BreadcrumbsPlus->add([['title' => 'Formas de Pagamento']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Html->css('index') ?>







<!-- Vue App Container -->
<div id="app" data-entity='<?= json_encode($formapagamentos->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
    data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="a forma de pagamento" data-routes='<?= json_encode([
          'search' => $this->Url->build(['controller' => 'FormaPagamentos', 'action' => 'search']),
          'view' => $this->Url->build(['controller' => 'FormaPagamentos', 'action' => 'visualizar']),
          'edit' => $this->Url->build(['controller' => 'FormaPagamentos', 'action' => 'index']),
          'delete' => $this->Url->build(['controller' => 'FormaPagamentos', 'action' => 'delete'])
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
                                    <?= isset($formapagamento) && $formapagamento->id ? 'Atualizar Meio de Pagamento' : 'Adicionar Meio de Pagamento' ?>
                                </h5>
                            </div>

                            <div class="card-body">

                                <?php
                                $formAction = isset($formapagamento) && $formapagamento->id
                                    ? ['controller' => 'FormaPagamentos', 'action' => 'index', $formapagamento->id]
                                    : ['controller' => 'FormaPagamentos', 'action' => 'index'];

                                echo $this->Form->create($formaPagamento ?? null, [
                                    'url' => $formAction,
                                    'class' => 'form-vertical'
                                ]);
                                ?>

                                <?php if (isset($formapagamento) && $formapagamento->id): ?>
                                    <?= $this->Form->hidden('id', ['value' => $formapagamento->id]); ?>
                                <?php endif; ?>


                                <?= $this->Form->control('nome', [
                                    'label' => 'Nome',
                                    'class' => 'form-control mb-3',
                                    'required' => true,
                                    'value' => $formapagamento->nome ?? ''
                                ]); ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $this->Form->control('tipo_taxa', [
                                            'label' => 'Tipo Taxa',
                                            'options' => [
                                                '1' => 'Percentual %',
                                                '2' => 'Valor Fixo R$'
                                            ],
                                            'empty' => 'Selecione',
                                            'class' => 'form-control mb-2',
                                            'value' => $formapagamento->tipo_taxa ?? ''
                                        ]); ?>
                                    </div>

                                    <div class="col-md-6">
                                        <?= $this->Form->control('taxas', [
                                            'label' => 'Taxa',
                                            'type' => 'text',
                                            'id' => 'taxas',
                                            'class' => 'form-control mb-2',
                                            'value' => $formapagamento->taxas ?? ''
                                        ]); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $this->Form->control('dias', [
                                            'label' => 'Dias',
                                            'type' => 'text',
                                            'id' => 'dias',
                                            'class' => 'form-control mb-2',
                                            'value' => $formapagamento->dias ?? ''
                                        ]); ?>
                                    </div>


                                    <div class="col-md-6">
                                        <?= $this->Form->control('taxa_antecipacao', [
                                            'label' => 'Taxa Antecipação',
                                            'type' => 'text',
                                            'id' => 'taxa_antecipacao',
                                            'class' => 'form-control mb-2',
                                            'value' => $formapagamento->taxa_antecipacao ?? ''
                                        ]); ?>
                                    </div>
                                </div>

                                <div class="form-check form-switch mt-2 mb-3">
                                    <?= $this->Form->checkbox('ativar', [
                                        'class' => 'form-check-input',
                                        'id' => 'ativar',
                                        'default' => true,
                                        'hiddenField' => true
                                    ]) ?>
                                    <?= $this->Form->label('ativar', 'Ativar Forma de Pagamento', ['class' => 'form-check-label']) ?>
                                </div>

                                <!-- Espaço antes dos botões -->
                                <div class="d-flex gap-2 mt-3">
                                    <?= $this->Form->button(
                                        isset($formapagamento) && $formapagamento->id ? 'Salvar Alterações' : 'Cadastrar',
                                        ['class' => 'btn bg-success-subtle text-success flex-grow-1']
                                    ); ?>

                                    <?php if (isset($formapagamento) && $formapagamento->id): ?>
                                        <?= $this->Html->link(
                                            'Cancelar',
                                            ['action' => 'index'],
                                            ['class' => 'btn bg-primary-subtle text-primary ']
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
                            <h4 class="card-title mb-0"><?= __('Meios de Pagamento') ?></h4>

                        </div>

                        <!-- Busca -->
                        <!-- Busca -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-6">
                                <form @submit.prevent="onSearch" class="mb-0">
                                    <div class="input-group">
                                        <input v-model="query" type="text"
                                            class="form-control form-control-lg placeholdert"
                                            placeholder="pesquisar...">
                                        <button class="btn bg-primary-subtle text-primary border-primary" type="submit"
                                            data-bs-toggle="tooltip" title="Pesquisar">
                                            <i class="ti ti-search fs-5"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="row mb-3">

                            </div>

                        </div>
                        <!-- Tabela -->
                        <div class="table-responsive-sm mb-4 border rounded-1">
                            <table class="table table-striped text-nowrap mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Taxas</th>
                                        <th>Dias</th>
                                        <th>Antecipação</th>
                                        <th>Ativo</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in filteredItems" :key="item.id">
                                        <td style="width:35%;">{{ item.nome }}</td>
                                        <td>{{ item.taxas}} </td>
                                        <td>{{ item.dias}} </td>
                                        <td>{{ item.taxa_antecipacao}} </td>
                                        <td>
                                            <span v-if="item.ativar"
                                                class="badge bg-success-subtle border border-success text-success">
                                                Ativo
                                            </span>
                                            <span v-else
                                                class="badge bg-danger-subtle border border-danger text-danger">
                                                Inativo
                                            </span>
                                        </td>


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
                                                            :href="`${routes.edit}/index/${item.id}`">Editar</a>
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
                                        <td colspan="8" class="text-center text-muted">Nenhuma forma de pagamento
                                            encontrada
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