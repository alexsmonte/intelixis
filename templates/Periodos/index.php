<?php

$this->BreadcrumbsPlus->add([['title' => 'Periodos']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Periodos') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Periodo', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
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
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('convenio_id', ['label'=>'convenio_id','options' => $convenios, 'empty' => true]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_hora_inicio', ['label'=>'data_hora_inicio', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_hora_fim', ['label'=>'data_hora_fim', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('dia_semana', ['label'=>'dia_semana', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_personalizada', ['label'=>'data_personalizada', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('cadeia_id', ['label'=>'cadeia_id', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('status', ['label'=>'status', 'class'=>'form-control']); ?>
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
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('convenio_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_hora_inicio') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_hora_fim') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('dia_semana') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_personalizada') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('cadeia_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('status') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('modified') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($periodos as $periodo): ?>
                    <tr>
                                                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($periodo->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $periodo->hasValue('estabelecimento') ? $this->Html->link($periodo->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $periodo->estabelecimento->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $periodo->hasValue('profissional') ? $this->Html->link($periodo->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $periodo->profissional->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $periodo->hasValue('servico') ? $this->Html->link($periodo->servico->nome, ['controller' => 'Servicos', 'action' => 'view', $periodo->servico->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $periodo->hasValue('local') ? $this->Html->link($periodo->local->nome, ['controller' => 'Locais', 'action' => 'view', $periodo->local->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $periodo->hasValue('convenio') ? $this->Html->link($periodo->convenio->nome, ['controller' => 'Convenios', 'action' => 'view', $periodo->convenio->id]) : '' ?></p></td>
                                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($periodo->data_hora_inicio) ?> </p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($periodo->data_hora_fim) ?> </p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($periodo->dia_semana) ?></p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($periodo->data_personalizada) ?> </p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($periodo->cadeia_id) ?> </p></td>
                                                                                                                                                                                                    <td><?= $periodo->status == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($periodo->created) ?> </p></td>
                                                                                                                                                                                                    <td><p class="mb-0 fw-normal fs-4"><?= h($periodo->modified) ?> </p></td>
                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $periodo->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $periodo->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $periodo->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $periodo->id),
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