<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Periodo $periodo
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 * @var \Cake\Collection\CollectionInterface|string[] $profissionais
 * @var \Cake\Collection\CollectionInterface|string[] $servicos
 * @var \Cake\Collection\CollectionInterface|string[] $locais
 * @var \Cake\Collection\CollectionInterface|string[] $convenios
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Periodos', 'url'=>['controller' => 'Periodos', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Periodos') ?></h4>
            </div>

            <?= $this->Form->create($periodo) ?>
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
                    <?= $this->Form->control('convenio_id', ['label' => 'convenio_id','class' => 'form-control','options' => $convenios, 'empty' => true]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_hora_inicio',['label' => 'data_hora_inicio','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_hora_fim',['label' => 'data_hora_fim','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('dia_semana',['label' => 'dia_semana','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_personalizada',['label' => 'data_personalizada','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cadeia_id',['label' => 'cadeia_id','class' => 'form-control',]); ?>
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