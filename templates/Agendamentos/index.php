<?php

$this->BreadcrumbsPlus->add([['title' => 'Agendamentos']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Agendamentos') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Agendamento', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                                                <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
                        <div class="row">

                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('estabelecimento_id', ['label'=>'estabelecimento_id','options' => $estabelecimentos]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('cliente_id', ['label'=>'cliente_id','options' => $clientes]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('servico_id', ['label'=>'servico_id','options' => $servicos]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('statu_id', ['label'=>'statu_id','options' => $status]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('profissional_id', ['label'=>'profissional_id','options' => $profissionais, 'empty' => true]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('local_id', ['label'=>'local_id','options' => $locais, 'empty' => true]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?= $this->Form->control('convenio_id', ['label'=>'convenio_id','options' => $convenios, 'empty' => true]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_hora_inicio', ['label'=>'data_hora_inicio', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('data_hora_fim', ['label'=>'data_hora_fim', 'class'=>'form-control']); ?>
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
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('cliente_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('servico_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('statu_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('profissional_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('local_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('convenio_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_hora_inicio') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('data_hora_fim') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('modified') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($agendamentos as $agendamento): ?>
                    <tr>
                                                                                                                                                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($agendamento->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $agendamento->hasValue('estabelecimento') ? $this->Html->link($agendamento->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $agendamento->estabelecimento->id]) : '' ?></p></td>
                                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $agendamento->hasValue('cliente') ? $this->Html->link($agendamento->cliente->nome, ['controller' => 'Clientes', 'action' => 'view', $agendamento->cliente->id]) : '' ?></p></td>
                                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $agendamento->hasValue('servico') ? $this->Html->link($agendamento->servico->nome, ['controller' => 'Servicos', 'action' => 'view', $agendamento->servico->id]) : '' ?></p></td>
                                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $agendamento->hasValue('status') ? $this->Html->link($agendamento->status->nome, ['controller' => 'Status', 'action' => 'view', $agendamento->status->id]) : '' ?></p></td>
                                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $agendamento->hasValue('profissional') ? $this->Html->link($agendamento->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $agendamento->profissional->id]) : '' ?></p></td>
                                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $agendamento->hasValue('local') ? $this->Html->link($agendamento->local->nome, ['controller' => 'Locais', 'action' => 'view', $agendamento->local->id]) : '' ?></p></td>
                                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $agendamento->hasValue('convenio') ? $this->Html->link($agendamento->convenio->nome, ['controller' => 'Convenios', 'action' => 'view', $agendamento->convenio->id]) : '' ?></p></td>
                                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= h($agendamento->data_hora_inicio) ?> </p></td>
                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= h($agendamento->data_hora_fim) ?> </p></td>
                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= h($agendamento->created) ?> </p></td>
                                                                                                                                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= h($agendamento->modified) ?> </p></td>
                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $agendamento->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $agendamento->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $agendamento->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $agendamento->id),
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