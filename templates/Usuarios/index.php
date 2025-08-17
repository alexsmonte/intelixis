<?php

$this->BreadcrumbsPlus->add([['title' => 'Usuarios']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Usuários') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                        <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Usuário', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('nome', ['label'=>'Nome', 'class'=>'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email', ['label'=>'E-Mail', 'class'=>'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3 form-group">
                    <?= $this->Form->control('ativar', ['label'=>'Ativos', 'class'=>'form-select', 'options' => ['0' => 'Não','Sim'], 'empty'=>true]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?php  $this->Form->control('estabelecimentos._ids', ['label'=>'', 'class'=>'form-control', 'options' => [], 'empty'=>true]); //$estabelecimentos ?>
                </div>
                <div class="col-md-12 mb-3">
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
                        <th style="width: 50%;"><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('nome') ?></h6></th>
                        <th style="width: 30%;"><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('email','E-Mail') ?></h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('ativar') ?></h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created', 'Criado em') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><p class="mb-0 fw-normal fs-4"><?= h($usuario->nome) ?> </p></td>
                            <td><p class="mb-0 fw-normal fs-4"><?= h($usuario->email) ?> </p></td>
                            <td><?= $usuario->ativar == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?></td>
                            <td><p class="mb-0 fw-normal fs-4"><?= h($usuario->created) ?> </p></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        Opções
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><?= $this->Html->link('Visualizar', ['action' => 'view', $usuario->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                        <li><?= $this->Html->link('Editar', ['action' => 'edit', $usuario->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                        <li>
                                            <?= $this->Form->postLink(
                                                'Excluir',
                                                ['action' => 'delete', $usuario->id],
                                                [
                                                    'class'=>'dropdown-item',
                                                    'escape'=>false,
                                                    'method' => 'delete',
                                                    'confirm' => __('Tem certeza que deseja excluir # {0}?', $usuario->id),
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