<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Caixa $caixa
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 * @var \Cake\Collection\CollectionInterface|string[] $usuarios
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Caixas', 'url'=>['controller' => 'Caixas', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Caixas') ?></h4>
            </div>

            <?= $this->Form->create($caixa) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('estabelecimento_id', ['label' => 'estabelecimento_id','class' => 'form-control','options' => $estabelecimentos]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('usuario_id', ['label' => 'usuario_id','class' => 'form-control','options' => $usuarios]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_abertura',['label' => 'data_abertura','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('saldo_inicial',['label' => 'saldo_inicial','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_fechamento', ['label' => 'data_fechamento','class' => 'form-control','empty' => true]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('situacao',['label' => 'situacao','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('usuario_conferiu',['label' => 'usuario_conferiu','class' => 'form-control',]); ?>
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