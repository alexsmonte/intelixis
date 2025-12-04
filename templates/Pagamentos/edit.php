<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pagamento $pagamento
 * @var string[]|\Cake\Collection\CollectionInterface $financas
 * @var string[]|\Cake\Collection\CollectionInterface $formaPagamentos
 */
?>

<?php

$this->BreadcrumbsPlus->add([['title' => 'Pagamentos', 'url'=>['controller' => 'Pagamentos', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Pagamentos') ?></h4>
            </div>
            
            <?= $this->Form->create($pagamento) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('financa_id', ['label' => 'financa_id','class' => 'form-control','options' => $financas]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('forma_pagamento_id', ['label' => 'forma_pagamento_id','class' => 'form-control','options' => $formaPagamentos]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('valor',['label' => 'valor','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('taxa',['label' => 'taxa','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('antecipar',['label' => 'antecipar','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_antecipacao', ['label' => 'data_antecipacao','class' => 'form-control','empty' => true]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('usuario_recebeu',['label' => 'usuario_recebeu','class' => 'form-control',]); ?>
            </div>
    <div class="col-md-12">
    <div class="row">
        <div class="col-md-2 offset-md-10  mb-3">
    <?= $this->Form->button(__('Salvar'),['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
        </div>
    <?= $this->Form->end() ?>
    </div>
    </div>

</div>

        </div>
    </div>
</div>