<?php

$this->BreadcrumbsPlus->add([['title' => 'Financas']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Financas') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Financa', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                                                <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
                        <div class="row">

                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('estabelecimento_id', ['label'=>'estabelecimento_id','options' => $estabelecimentos]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('ccustos_id', ['label'=>'ccustos_id','options' => $ccustoses, 'empty' => true]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('profissional_id', ['label'=>'profissional_id','options' => $profissionais, 'empty' => true]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('cliente_id', ['label'=>'cliente_id','options' => $clientes, 'empty' => true]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('procedimento_id', ['label'=>'procedimento_id','options' => $procedimentos, 'empty' => true]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('tipo', ['label'=>'tipo', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('descricao', ['label'=>'descricao', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_vencimento', ['label'=>'data_vencimento', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('valor', ['label'=>'valor', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('desconto', ['label'=>'desconto', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_baixa', ['label'=>'data_baixa', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('valor_recebido', ['label'=>'valor_recebido', 'class'=>'form-control']); ?>
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
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('ccustos_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('profissional_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('cliente_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('procedimento_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('tipo') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('descricao') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_vencimento') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('valor') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('desconto') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_baixa') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('valor_recebido') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('modified') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($financas as $financa): ?>
                    <tr>
                                                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($financa->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $financa->hasValue('estabelecimento') ? $this->Html->link($financa->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $financa->estabelecimento->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $financa->hasValue('ccustos') ? $this->Html->link($financa->ccustos->name, ['controller' => 'Ccustos', 'action' => 'view', $financa->ccustos->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $financa->hasValue('profissional') ? $this->Html->link($financa->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $financa->profissional->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $financa->hasValue('cliente') ? $this->Html->link($financa->cliente->nome, ['controller' => 'Clientes', 'action' => 'view', $financa->cliente->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $financa->hasValue('procedimento') ? $this->Html->link($financa->procedimento->id, ['controller' => 'Procedimentos', 'action' => 'view', $financa->procedimento->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $financa->tipo === null ? '' : $this->Number->format($financa->tipo) ?></p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($financa->descricao) ?> </p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($financa->data_vencimento) ?> </p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($financa->valor) ?></p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $financa->desconto === null ? '' : $this->Number->format($financa->desconto) ?></p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $financa->data_baixa === null ? '' : $this->Number->format($financa->data_baixa) ?></p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $financa->valor_recebido === null ? '' : $this->Number->format($financa->valor_recebido) ?></p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($financa->created) ?> </p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($financa->modified) ?> </p></td>
                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $financa->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $financa->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $financa->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $financa->id),
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