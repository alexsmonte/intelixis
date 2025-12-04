<?php

$this->BreadcrumbsPlus->add([['title' => 'Caixas']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Caixas') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Caixa', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                                                <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
                        <div class="row">

                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('estabelecimento_id', ['label'=>'estabelecimento_id','options' => $estabelecimentos]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('usuario_id', ['label'=>'usuario_id','options' => $usuarios]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_abertura', ['label'=>'data_abertura', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('saldo_inicial', ['label'=>'saldo_inicial', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_fechamento', ['label'=>'data_fechamento', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('situacao', ['label'=>'situacao', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('usuario_conferiu', ['label'=>'usuario_conferiu', 'class'=>'form-control']); ?>
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
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('usuario_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_abertura') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('saldo_inicial') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_fechamento') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('situacao') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('usuario_conferiu') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('modified') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($caixas as $caixa): ?>
                    <tr>
                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($caixa->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $caixa->hasValue('estabelecimento') ? $this->Html->link($caixa->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $caixa->estabelecimento->id]) : '' ?></p></td>
                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $caixa->hasValue('usuario') ? $this->Html->link($caixa->usuario->nome, ['controller' => 'Usuarios', 'action' => 'view', $caixa->usuario->id]) : '' ?></p></td>
                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($caixa->data_abertura) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($caixa->saldo_inicial) ?></p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($caixa->data_fechamento) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($caixa->situacao) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $caixa->usuario_conferiu === null ? '' : $this->Number->format($caixa->usuario_conferiu) ?></p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($caixa->created) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($caixa->modified) ?> </p></td>
                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $caixa->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $caixa->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $caixa->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $caixa->id),
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