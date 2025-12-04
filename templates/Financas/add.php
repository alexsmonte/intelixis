<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Financa $financa
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 * @var \Cake\Collection\CollectionInterface|string[] $ccustoses
 * @var \Cake\Collection\CollectionInterface|string[] $profissionais
 * @var \Cake\Collection\CollectionInterface|string[] $clientes
 * @var \Cake\Collection\CollectionInterface|string[] $procedimentos
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Financas', 'url'=>['controller' => 'Financas', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Financas') ?></h4>
            </div>

            <?= $this->Form->create($financa) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('estabelecimento_id', ['label' => 'estabelecimento_id','class' => 'form-control','options' => $estabelecimentos]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('ccustos_id', ['label' => 'ccustos_id','class' => 'form-control','options' => $ccustoses, 'empty' => true]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('profissional_id', ['label' => 'profissional_id','class' => 'form-control','options' => $profissionais, 'empty' => true]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cliente_id', ['label' => 'cliente_id','class' => 'form-control','options' => $clientes, 'empty' => true]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('procedimento_id', ['label' => 'procedimento_id','class' => 'form-control','options' => $procedimentos, 'empty' => true]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('tipo',['label' => 'tipo','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('descricao',['label' => 'descricao','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_vencimento',['label' => 'data_vencimento','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('valor',['label' => 'valor','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('desconto',['label' => 'desconto','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_baixa',['label' => 'data_baixa','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('valor_recebido',['label' => 'valor_recebido','class' => 'form-control',]); ?>
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