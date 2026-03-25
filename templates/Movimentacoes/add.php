<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Movimentacao $movimentacao
 * @var \Cake\Collection\CollectionInterface|string[] $caixas
 * @var \Cake\Collection\CollectionInterface|string[] $formaPagamentos
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Movimentacoes', 'url'=>['controller' => 'Movimentacoes', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Movimentacoes') ?></h4>
            </div>

            <?= $this->Form->create($movimentacao) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('caixa_id', ['label' => 'caixa_id','class' => 'form-control','options' => $caixas]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('forma_pagamento_id', ['label' => 'forma_pagamento_id','class' => 'form-control','options' => $formaPagamentos]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('valor_apurado',['label' => 'valor_apurado','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('situacao',['label' => 'situacao','class' => 'form-control',]); ?>
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