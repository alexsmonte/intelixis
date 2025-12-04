<?php

$this->BreadcrumbsPlus->add([['title' => 'Pagamentos']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Pagamentos') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Pagamento', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                                                <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
                        <div class="row">

                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('financa_id', ['label'=>'financa_id','options' => $financas]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('forma_pagamento_id', ['label'=>'forma_pagamento_id','options' => $formaPagamentos]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('valor', ['label'=>'valor', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('taxa', ['label'=>'taxa', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('antecipar', ['label'=>'antecipar', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_antecipacao', ['label'=>'data_antecipacao', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('usuario_recebeu', ['label'=>'usuario_recebeu', 'class'=>'form-control']); ?>
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
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('financa_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('forma_pagamento_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('valor') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('taxa') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('antecipar') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_antecipacao') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('usuario_recebeu') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('modified') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pagamentos as $pagamento): ?>
                    <tr>
                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($pagamento->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $pagamento->hasValue('financa') ? $this->Html->link($pagamento->financa->descricao, ['controller' => 'Financas', 'action' => 'view', $pagamento->financa->id]) : '' ?></p></td>
                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $pagamento->hasValue('forma_pagamento') ? $this->Html->link($pagamento->forma_pagamento->nome, ['controller' => 'FormaPagamentos', 'action' => 'view', $pagamento->forma_pagamento->id]) : '' ?></p></td>
                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($pagamento->valor) ?></p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $pagamento->taxa === null ? '' : $this->Number->format($pagamento->taxa) ?></p></td>
                                                                                                                                        <td><?= $pagamento->antecipar == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($pagamento->data_antecipacao) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $pagamento->usuario_recebeu === null ? '' : $this->Number->format($pagamento->usuario_recebeu) ?></p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($pagamento->created) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($pagamento->modified) ?> </p></td>
                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $pagamento->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $pagamento->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $pagamento->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $pagamento->id),
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