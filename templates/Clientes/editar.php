<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cliente $cliente
 * @var string[]|\Cake\Collection\CollectionInterface $estabelecimentos
 */
?>

<?php

$this->BreadcrumbsPlus->add([['title' => 'Clientes', 'url' => ['controller' => 'Clientes', 'action' => 'index']], ['title' => 'Editar Usuário']]);

?>

<?= $this->element('barra-titulo', ['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Clientes') ?></h4>
            </div>

            <?= $this->Form->create($cliente) ?>
            <div class="row">

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="border-bottom pb-2">Dados Pessoais:</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggleNomeSocial" <?= !empty($cliente->nome_social) ? 'checked="checked"' : '' ?>
                            onchange="document.getElementById('nomeSocialContainer').classList.toggle('d-none')">
                        <label class="form-check-label text-muted fw-bold" for="toggleNomeSocial">Incluir Nome
                            Social</label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('nome', ['label' => 'Nome Completo', 'class' => 'form-control', 'v-mask' => "'nome'"]); ?>
                </div>
                <div class="col-md-6 mb-3 <?= !empty($cliente->nome_social) ? '' : 'd-none' ?>" id="nomeSocialContainer">
                    <?= $this->Form->control('nome_social', ['label' => 'Nome Social', 'class' => 'form-control', 'v-mask' => "'nome'"]); ?>
                </div>

                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_nascimento', ['label' => 'Nascimento', 'id' => 'data_nascimento', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('sexo', ['label' => 'Sexo', 'type' => 'select', 'options' => ['M' => 'Masculino', 'F' => 'Feminino', 'I' => 'Indefinido'], 'empty' => 'Selecione', 'class' => 'form-select']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('documento', ['label' => 'CPF', 'id' => 'documento', 'class' => 'form-control', 'v-mask' => "'documento'", 'maxlength' => 14]); ?>
                </div>

                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email', ['label' => 'Email', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('telefone', ['label' => 'Telefone', 'id' => 'telefone', 'class' => 'form-control', 'v-mask' => "'telefone'", 'maxlength' => 15]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('celular', ['label' => 'Celular', 'id' => 'celular', 'class' => 'form-control', 'v-mask' => "'celular'", 'maxlength' => 14]); ?>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="border-bottom pb-2">Endereço:</h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3" data-vue="cep-autocomplete" data-cep="<?= h($cliente->cep ?? '') ?>">

                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('logradouro', ['label' => 'Logradouro', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('numero', ['label' => 'Número', 'class' => 'form-control', 'v-mask' => "'numero'"]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('complemento', ['label' => 'Complemento', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('bairro', ['label' => 'Bairro', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('cidade', ['label' => 'Cidade', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('uf', ['label' => 'UF', 'class' => 'form-control', 'v-mask' => "'uf'"]); ?>
                </div>

                <div class="col-md-12 mt-4 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggleResponsavel"
                            <?= !empty($cliente->nome_responsavel) ? 'checked="checked"' : '' ?>
                            onchange="document.getElementById('responsavelContainer').classList.toggle('d-none')">
                        <label class="form-check-label fw-bold text-muted" for="toggleResponsavel">Cadastrar Responsável
                            Legal</label>
                    </div>
                </div>

                <div class="col-md-12 <?= !empty($cliente->nome_responsavel) ? '' : 'd-none' ?>" id="responsavelContainer">
                    <div class="row">
                        <div class="col-md-12 mb-3 mt-2">
                            <h6 class="border-bottom pb-2">Responsável Legal:</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?= $this->Form->control('nome_responsavel', ['label' => 'Nome do Responsável', 'class' => 'form-control', 'v-mask' => "'nome'"]); ?>
                        </div>
                        <div class="col-md-3 mb-3">
                            <?= $this->Form->control('documento_responsavel', ['label' => 'CPF do Responsável', 'class' => 'form-control', 'v-mask' => "'cpf'"]); ?>
                        </div>
                        <div class="col-md-3 mb-3">
                            <?= $this->Form->control('telefone_responsavel', ['label' => 'Telefone/Celular', 'class' => 'form-control', 'v-mask' => "'celular'"]); ?>
                        </div>
                        <div class="col-md-2 mb-3">
                            <?= $this->Form->control('parentesco_responsavel', ['label' => 'Parentesco', 'class' => 'form-control']); ?>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 offset-md-10  mb-3">
                        <?= $this->Form->button(__('Salvar'), ['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>

        </div>

    </div>
</div>
</div>


<!-- vue  -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<!-- Vue e componentes programacao.js -->
<?= $this->Html->script('vue/cep-autocomplete.js?v=' . filemtime(WWW_ROOT . 'js/vue/cep-autocomplete.js')) ?>
<!-- vue-masks -->
<?= $this->Html->script('vue/vue-masks.js') ?>