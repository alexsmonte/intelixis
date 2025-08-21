<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Assinatura $assinatura
 * @var string[]|\Cake\Collection\CollectionInterface $contas
 * @var string[]|\Cake\Collection\CollectionInterface $planos
 */
?>

<?php

$this->BreadcrumbsPlus->add([['title' => 'Assinaturas', 'url'=>['controller' => 'Assinaturas', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Assinaturas') ?></h4>
            </div>
            
            <?= $this->Form->create($assinatura) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('conta_id', ['label' => 'conta_id','class' => 'form-control','options' => $contas]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('plano_id', ['label' => 'plano_id','class' => 'form-control','options' => $planos]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_vencimento',['label' => 'data_vencimento','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('desconto',['label' => 'desconto','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('status',['label' => 'status','class' => 'form-control',]); ?>
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