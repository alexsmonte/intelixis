<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cliente $cliente
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 */
$this->BreadcrumbsPlus->add([
    ['title' => 'Clientes', 'url'=>['controller' => 'Clientes', 'action' => 'index']],
    ['title'=> 'Novo']
]);
?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Clientes') ?></h4>
            </div>

            <?= $this->Form->create($cliente, ['id' => 'cliente-form']) ?>
            <div class="row">
                
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('nome',['label' => 'Nome Completo','class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('documento',['label' => 'CPF','id' => 'documento','class' => 'form-control']); ?>
                </div>
                
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email',['label' => 'Email','class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('telefone',['label' => 'Telefone','id' => 'telefone','class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('celular',['label' => 'Celular','id' => 'celular','class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cep',['label' => 'CEP','id' => 'cep','class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('endereco',['label' => 'Endereço','class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('bairro',['label' => 'Bairro','class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cidade',['label' => 'Cidade','class' => 'form-control']); ?>
                </div>
                
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 offset-md-10 mb-3">
                        <?= $this->Form->button(__('Salvar'), ['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']); ?>
                    </div>
                </div>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>



