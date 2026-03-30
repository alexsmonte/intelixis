<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Procedimento $procedimento
 * @var string[]|\Cake\Collection\CollectionInterface $consultas
 * @var string[]|\Cake\Collection\CollectionInterface $servicos
 * @var string[]|\Cake\Collection\CollectionInterface $profissionais
 */
?>

<?php

$this->BreadcrumbsPlus->add([['title' => 'Procedimentos', 'url'=>['controller' => 'Procedimentos', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Procedimentos') ?></h4>
            </div>
            
            <?= $this->Form->create($procedimento) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('consulta_id', ['label' => 'consulta_id','class' => 'form-control','options' => $consultas]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('servico_id', ['label' => 'servico_id','class' => 'form-control','options' => $servicos]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('profissional_id', ['label' => 'profissional_id','class' => 'form-control','options' => $profissionais]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('quantidade',['label' => 'quantidade','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('valor_unitario',['label' => 'valor_unitario','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('desconto',['label' => 'desconto','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('aprovar',['label' => 'aprovar','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('parcelar',['label' => 'parcelar','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('parcelas',['label' => 'parcelas','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('faces',['label' => 'faces','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('dentes',['label' => 'dentes','class' => 'form-control',]); ?>
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