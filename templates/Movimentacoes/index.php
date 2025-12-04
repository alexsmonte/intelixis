<?php

$this->BreadcrumbsPlus->add([['title' => 'Movimentacoes']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Movimentacoes') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Movimentacao', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                                                <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
                        <div class="row">

                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('caixa_id', ['label'=>'caixa_id','options' => $caixas]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('forma_pagamento_id', ['label'=>'forma_pagamento_id','options' => $formaPagamentos]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('valor_apurado', ['label'=>'valor_apurado', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('situacao', ['label'=>'situacao', 'class'=>'form-control']); ?>
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
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('caixa_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('forma_pagamento_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('valor_apurado') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('situacao') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('modified') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($movimentacoes as $movimentacao): ?>
                    <tr>
                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($movimentacao->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $movimentacao->hasValue('caixa') ? $this->Html->link($movimentacao->caixa->id, ['controller' => 'Caixas', 'action' => 'view', $movimentacao->caixa->id]) : '' ?></p></td>
                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $movimentacao->hasValue('forma_pagamento') ? $this->Html->link($movimentacao->forma_pagamento->nome, ['controller' => 'FormaPagamentos', 'action' => 'view', $movimentacao->forma_pagamento->id]) : '' ?></p></td>
                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $movimentacao->valor_apurado === null ? '' : $this->Number->format($movimentacao->valor_apurado) ?></p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($movimentacao->situacao) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($movimentacao->created) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($movimentacao->modified) ?> </p></td>
                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $movimentacao->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $movimentacao->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $movimentacao->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $movimentacao->id),
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