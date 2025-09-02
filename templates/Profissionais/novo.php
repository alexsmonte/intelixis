<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Profissional $profissional
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 * @var \Cake\Collection\CollectionInterface|string[] $convenios
 * @var \Cake\Collection\CollectionInterface|string[] $servicos
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Profissionais', 'url'=>['controller' => 'Profissionais', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Cadastrar Profissional') ?></h4>
            </div>

            <?= $this->Form->create($profissional) ?>
            <div class="row">
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('estabelecimento_id', [
                        'label' => 'Selecione o Estabelecimento',
                        'class' => 'form-control',
                        'options' => $estabelecimentos
                    ]); ?>  </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('nome',['label' => 'Nome Completo','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email',['label' => 'Email','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('telefone',['label' => 'Telefone','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('celular',['label' => 'Celular','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cep',['label' => 'CEP','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('logradouro',['label' => 'Logradoudo','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('numero',['label' => 'Número','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('complemento',['label' => 'Complemento','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('bairro',['label' => 'Bairro','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cidade',['label' => 'Cidade','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                                <?= $this->Form->checkbox('ativar', [
                                'class' => 'form-check-input',
                                'id' => 'ativar',
                                'default'=>true,
                                'hiddenField' => true, // evita campo hidden extra
                                ]) ?>
                                <?= $this->Form->label('ativar', 'Ativar', ['class' => 'form-check-label']) ?>
                        </div>
            </div>
    <div class="col-md-3 mb-3">
                    <?= $this->Form->control('convenios._ids', ['label' => 'Convênios','class' => 'form-control','options' => $convenios]); ?>
    </div>
    <div class="col-md-3 mb-3">
                    <?= $this->Form->control('servicos._ids', ['label' => 'Serviços','class' => 'form-control','options' => $servicos]); ?>
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