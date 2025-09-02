<?php

$this->BreadcrumbsPlus->add([['title' => 'Bloqueios']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Bloqueios') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Bloqueio', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                                                <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
                        <div class="row">

                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('estabelecimento_id', ['label'=>'estabelecimento_id','options' => $estabelecimentos]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('profissional_id', ['label'=>'profissional_id','options' => $profissionais, 'empty' => true]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('servico_id', ['label'=>'servico_id','options' => $servicos, 'empty' => true]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('local_id', ['label'=>'local_id','options' => $locais, 'empty' => true]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_inicio', ['label'=>'data_inicio', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_fim', ['label'=>'data_fim', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('dia_inteiro', ['label'=>'dia_inteiro', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('hora_inicio', ['label'=>'hora_inicio', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('hora_fim', ['label'=>'hora_fim', 'class'=>'form-control']); ?>
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
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('profissional_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('servico_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('local_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_inicio') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_fim') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('dia_inteiro') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('hora_inicio') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('hora_fim') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('modified') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bloqueios as $bloqueio): ?>
                    <tr>
                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($bloqueio->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $bloqueio->hasValue('estabelecimento') ? $this->Html->link($bloqueio->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $bloqueio->estabelecimento->id]) : '' ?></p></td>
                                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $bloqueio->hasValue('profissional') ? $this->Html->link($bloqueio->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $bloqueio->profissional->id]) : '' ?></p></td>
                                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $bloqueio->hasValue('servico') ? $this->Html->link($bloqueio->servico->nome, ['controller' => 'Servicos', 'action' => 'view', $bloqueio->servico->id]) : '' ?></p></td>
                                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $bloqueio->hasValue('local') ? $this->Html->link($bloqueio->local->nome, ['controller' => 'Locais', 'action' => 'view', $bloqueio->local->id]) : '' ?></p></td>
                                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= h($bloqueio->data_inicio) ?> </p></td>
                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= h($bloqueio->data_fim) ?> </p></td>
                                                                                                                                                                                <td><?= $bloqueio->dia_inteiro == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?></td>
                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $bloqueio->hora_inicio === null ? '' : $this->Number->format($bloqueio->hora_inicio) ?></p></td>
                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $bloqueio->hora_fim === null ? '' : $this->Number->format($bloqueio->hora_fim) ?></p></td>
                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= h($bloqueio->created) ?> </p></td>
                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= h($bloqueio->modified) ?> </p></td>
                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $bloqueio->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $bloqueio->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $bloqueio->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $bloqueio->id),
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