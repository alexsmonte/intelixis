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

$this->BreadcrumbsPlus->add([['title' => 'Fornecedores', 'url' => ['controller' => 'Profissionais', 'action' => 'fornecedor_index']], ['title' => 'Novo']]);

?>

<?= $this->element('barra-titulo', ['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Cadastrar Fornecedor') ?></h4>
            </div>

            <?= $this->Form->create($profissional) ?>

            <div class="col-md-3 mb-3">
                <div class="form-check form-switch">
                    <?= $this->Form->checkbox('ativar', [
                        'class' => 'form-check-input',
                        'id' => 'ativar',
                        'default' => true,
                        'hiddenField' => true, // evita campo hidden extra
                    ]) ?>
                    <?= $this->Form->label('ativar', 'Ativar', ['class' => 'form-check-label']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="border-bottom pb-2">Dados Básicos:</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('nome', ['label' => 'Razão Social', 'class' => 'form-control', 'v-mask' => "'nome'", 'minlength' => '3']); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('documento', ['label' => 'CPF/CNPJ', 'class' => 'form-control', 'v-mask' => "'documento'", 'maxlength' => 18]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email', ['label' => 'Email', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('telefone', ['label' => 'Telefone', 'class' => 'form-control', 'v-mask' => "'telefone'"]); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('celular', ['label' => 'Celular', 'class' => 'form-control', 'v-mask' => "'celular'"]); ?>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="border-bottom pb-2">Endereço:</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3" data-vue="cep-autocomplete">
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('logradouro', ['label' => 'Logradoudo', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-1 mb-3">
                    <?= $this->Form->control('numero', ['label' => 'Número', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('complemento', ['label' => 'Complemento', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('bairro', ['label' => 'Bairro', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cidade', ['label' => 'Cidade', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-1 mb-3">
                    <?= $this->Form->control('uf', ['label' => 'UF', 'class' => 'form-control',]); ?>
                </div>

            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3 mt-3">
                        <h5 class="border-bottom pb-2"></h5>
                    </div>
                </div>
            </div>




        </div>




        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 mb-3 text-end">
                    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success rounded-pill']) ?>
                    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn bg-danger-subtle text-danger rounded-pill ms-2']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>

    </div>

</div>
</div>


<!-- vue  -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<!-- vue cep autocomplete-->
<?= $this->Html->script('vue/cep-autocomplete.js?v=' . filemtime(WWW_ROOT . 'js/vue/cep-autocomplete.js')) ?>

<!-- vue-masks -->
<?= $this->Html->script('vue/vue-masks.js') ?>