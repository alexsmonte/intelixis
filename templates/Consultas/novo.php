<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Consulta $consulta
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 * @var \Cake\Collection\CollectionInterface|string[] $clientes
 * @var \Cake\Collection\CollectionInterface|string[] $profissionais
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Consultas', 'url'=>['controller' => 'Consultas', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Consultas') ?></h4>
            </div>

            <?= $this->Form->create($consulta) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('estabelecimento_id', ['label' => 'estabelecimento_id','class' => 'form-control','options' => $estabelecimentos]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cliente_id', ['label' => 'cliente_id','class' => 'form-control','options' => $clientes]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('profissional_id', ['label' => 'profissional_id','class' => 'form-control','options' => $profissionais]); ?>
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