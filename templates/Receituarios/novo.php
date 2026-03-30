<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Receituario $receituario
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Receituarios', 'url'=>['controller' => 'Receituarios', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Novo receituário') ?></h4>
            </div>

            <?= $this->Form->create($receituario) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('titulo',['label' => 'titulo','class' => 'form-control',]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('descricao',['label' => 'descricao','class' => 'form-control',]); ?>
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