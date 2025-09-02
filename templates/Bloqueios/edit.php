<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bloqueio $bloqueio
 * @var string[]|\Cake\Collection\CollectionInterface $estabelecimentos
 * @var string[]|\Cake\Collection\CollectionInterface $profissionais
 * @var string[]|\Cake\Collection\CollectionInterface $servicos
 * @var string[]|\Cake\Collection\CollectionInterface $locais
 */
?>

<?php

$this->BreadcrumbsPlus->add([['title' => 'Bloqueios', 'url'=>['controller' => 'Bloqueios', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Bloqueios') ?></h4>
            </div>
            
            <?= $this->Form->create($bloqueio) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('estabelecimento_id', ['label' => 'estabelecimento_id','class' => 'form-control','options' => $estabelecimentos]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('profissional_id', ['label' => 'profissional_id','class' => 'form-control','options' => $profissionais, 'empty' => true]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('servico_id', ['label' => 'servico_id','class' => 'form-control','options' => $servicos, 'empty' => true]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('local_id', ['label' => 'local_id','class' => 'form-control','options' => $locais, 'empty' => true]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_inicio',['label' => 'data_inicio','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_fim',['label' => 'data_fim','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('dia_inteiro',['label' => 'dia_inteiro','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('hora_inicio',['label' => 'hora_inicio','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('hora_fim',['label' => 'hora_fim','class' => 'form-control',]); ?>
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