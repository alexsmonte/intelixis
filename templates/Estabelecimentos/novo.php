<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estabelecimento $estabelecimento
 * @var \Cake\Collection\CollectionInterface|string[] $contas
 * @var \Cake\Collection\CollectionInterface|string[] $assinaturas
 * @var \Cake\Collection\CollectionInterface|string[] $extensoes
 * @var \Cake\Collection\CollectionInterface|string[] $usuarios
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Estabelecimentos', 'url'=>['controller' => 'Estabelecimentos', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Estabelecimentos') ?></h4>
            </div>

            <?= $this->Form->create($estabelecimento) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('conta_id', ['label' => 'conta_id','class' => 'form-control','options' => $contas]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('assinatura_id', ['label' => 'assinatura_id','class' => 'form-control','options' => $assinaturas]); ?>
                </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('razao_social',['label' => 'razao_social','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('nome_fantasia',['label' => 'nome_fantasia','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cpf_cnpj',['label' => 'cpf_cnpj','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('logradouro',['label' => 'logradouro','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('numero',['label' => 'numero','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('complemento',['label' => 'complemento','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email',['label' => 'email','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('telefone',['label' => 'telefone','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('celular',['label' => 'celular','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                    <?= $this->Form->control('ativar',['label' => 'ativar','class' => 'form-control',]); ?>
            </div>
    <div class="col-md-3 mb-3">
                    <?= $this->Form->control('extensoes._ids', ['label' => '','class' => 'form-control','options' => $extensoes]); ?>
    </div>
    <div class="col-md-3 mb-3">
                    <?= $this->Form->control('usuarios._ids', ['label' => '','class' => 'form-control','options' => $usuarios]); ?>
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