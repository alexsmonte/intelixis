<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Convenio $convenio
 * @var string[]|\Cake\Collection\CollectionInterface $estabelecimentos
 * @var string[]|\Cake\Collection\CollectionInterface $profissionais
 * @var string[]|\Cake\Collection\CollectionInterface $servicos
 */
?>

<?php

$this->BreadcrumbsPlus->add([['title' => 'Convenios', 'url'=>['controller' => 'Convenios', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Convenios') ?></h4>
            </div>
            
            <?= $this->Form->create($convenio) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('estabelecimento_id', ['label' => 'estabelecimento_id','class' => 'form-control','options' => $estabelecimentos]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('nome',['label' => 'nome','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('registro_ans',['label' => 'registro_ans','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cnpj',['label' => 'cnpj','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('telefone',['label' => 'telefone','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email',['label' => 'email','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('site',['label' => 'site','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('ativar',['label' => 'ativar','class' => 'form-control',]); ?>
            </div>
    <div class="col-md-3 mb-3">
                    <?= $this->Form->control('profissionais._ids', ['label' => '','class' => 'form-control','options' => $profissionais]); ?>
    </div>
    <div class="col-md-3 mb-3">
                    <?= $this->Form->control('servicos._ids', ['label' => '','class' => 'form-control','options' => $servicos]); ?>
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