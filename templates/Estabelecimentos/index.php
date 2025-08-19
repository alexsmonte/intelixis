<?php

$this->BreadcrumbsPlus->add([['title' => 'Estabelecimentos']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Estabelecimentos') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                                                <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Estabelecimento', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                                                <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                        <li><?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>
                        <div class="row">

                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('conta_id', ['label'=>'conta_id','options' => $contas]); ?>
                                </div>                                <div class="col-md-3 mb-3">
                                    <?=  $this->Form->control('assinatura_id', ['label'=>'assinatura_id','options' => $assinaturas]); ?>
                                </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('razao_social', ['label'=>'razao_social', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('nome_fantasia', ['label'=>'nome_fantasia', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('cpf_cnpj', ['label'=>'cpf_cnpj', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('logradouro', ['label'=>'logradouro', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('numero', ['label'=>'numero', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('complemento', ['label'=>'complemento', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('email', ['label'=>'email', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('telefone', ['label'=>'telefone', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('celular', ['label'=>'celular', 'class'=>'form-control']); ?>
                            </div>                            <div class="col-md-3 mb-3">
                                <?= $this->Form->control('ativar', ['label'=>'ativar', 'class'=>'form-control']); ?>
                            </div>                        <div class="col-md-3 mb-3">
                            <?=  $this->Form->control('extensoes._ids', ['label'=>'', 'class'=>'form-control', 'options' => $extensoes]); ?>
                        </div>                        <div class="col-md-3 mb-3">
                            <?=  $this->Form->control('usuarios._ids', ['label'=>'', 'class'=>'form-control', 'options' => $usuarios]); ?>
                        </div>                                <div class="col-md-12 mb-3">
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
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('conta_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('assinatura_id') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('razao_social') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('nome_fantasia') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('cpf_cnpj') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('logradouro') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('numero') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('complemento') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('email') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('telefone') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('celular') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('ativar') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created') ?></h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('modified') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($estabelecimentos as $estabelecimento): ?>
                    <tr>
                                                                                                                            <td><p class="mb-0 fw-normal fs-4"><?= $this->Number->format($estabelecimento->id) ?></p></td>
                                                                                                <td><p class="mb-0 fw-normal fs-4"><?= $estabelecimento->hasValue('conta') ? $this->Html->link($estabelecimento->conta->cpf_cnpj, ['controller' => 'Contas', 'action' => 'view', $estabelecimento->conta->id]) : '' ?></p></td>
                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= $estabelecimento->hasValue('assinatura') ? $this->Html->link($estabelecimento->assinatura->id, ['controller' => 'Assinaturas', 'action' => 'view', $estabelecimento->assinatura->id]) : '' ?></p></td>
                                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->razao_social) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->nome_fantasia) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->cpf_cnpj) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->logradouro) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->numero) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->complemento) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->email) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->telefone) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->celular) ?> </p></td>
                                                                                                                                        <td><?= $estabelecimento->ativar == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->created) ?> </p></td>
                                                                                                                                        <td><p class="mb-0 fw-normal fs-4"><?= h($estabelecimento->modified) ?> </p></td>
                                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar', ['action' => 'view', $estabelecimento->id],['class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><?= $this->Html->link('Editar', ['action' => 'edit', $estabelecimento->id],['class'=>'dropdown-item','escape'=>false]) ?> </li>
                                    <li>
                                        <?= $this->Form->postLink(
                                        'Excluir',
                                        ['action' => 'delete', $estabelecimento->id],
                                        [
                                        'class'=>'dropdown-item',
                                        'escape'=>false,
                                        'method' => 'delete',
                                        'confirm' => __('Tem certeza que deseja excluir # {0}?', $estabelecimento->id),
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