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

$this->BreadcrumbsPlus->add([['title' => 'Estabelecimentos', 'url' => ['controller' => 'Estabelecimentos', 'action' => 'index']], ['title' => 'Novo']]);

?>

<!-- 1. CDN do Iconify -->
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>

<?= $this->element('barra-titulo', ['title' => $title]); ?>

<!-- Assets para Selectpicker -->
<?= $this->Html->css([
    'plugins/bootstrap-select.css'
]) ?>
<?= $this->Html->script([
    'plugins/bootstrap-select-min.js'
]) ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Estabelecimentos') ?></h4>
            </div>

            <?= $this->Form->create($estabelecimento, ['id' => 'estabelecimento-form']) ?>

            <div class="col-md-3 mb-3">
                <div class="form-check form-switch">
                    <?= $this->Form->checkbox('ativar', [
                        'class' => 'form-check-input',
                        'id' => 'ativar',
                        'default' => true,
                        'hiddenField' => true,
                    ]) ?>
                    <?= $this->Form->label('ativar', 'Ativar', ['class' => 'form-check-label']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h6 class="border-bottom pb-2 mb-3 mt-3">Dados Principais:</h6>
                </div>
                <div class="col-md-5 mb-3">
                    <?= $this->Form->control('razao_social', ['label' => 'Razão Social', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-5 mb-3">
                    <?= $this->Form->control('nome_fantasia', ['label' => 'Nome Fantasia', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('cpf_cnpj', ['label' => 'CNPJ / CPF', 'class' => 'form-control', 'v-mask' => "'documento'"]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email', ['label' => 'Email', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('telefone', ['label' => 'Telefone', 'class' => 'form-control', 'v-mask' => "'telefone'"]); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('celular', ['label' => 'Celular', 'class' => 'form-control', 'v-mask' => "'celular'"]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h6 class="border-bottom pb-2 mb-3 mt-3">Endereço:</h6>
                </div>


                <div class="col-md-3" data-vue="cep-autocomplete" data-cep="<?= h($estabelecimento->cep ?? '') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('logradouro', ['label' => 'Logradouro', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-1 mb-3">
                    <?= $this->Form->control('numero', ['label' => 'Número', 'class' => 'form-control', 'v-mask' => "'numero'"]); ?>
                </div>
                <div class="col-md-5 mb-3">
                    <?= $this->Form->control('complemento', ['label' => 'Complemento', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('bairro', ['label' => 'Bairro', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('cidade', ['label' => 'Cidade', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-1 mb-3">
                    <?= $this->Form->control('uf', ['label' => 'UF', 'class' => 'form-control', 'v-mask' => "'uf'"]); ?>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <h6 class="border-bottom pb-2 mb-3 mt-3">Administração e Vínculos:</h6>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('conta_id', ['label' => 'Conta (Tenant)', 'class' => 'selectpicker form-select', 'data-live-search' => 'true', 'options' => $contas]); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('assinatura_id', ['label' => 'Assinatura', 'class' => 'selectpicker form-select', 'data-live-search' => 'true', 'options' => $assinaturas]); ?>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="d-flex justify-content-between align-items-end mb-1">
                        <label class="form-label mb-0" for="extensoes-ids">Extensões</label>
                        <div>
                            <button type="button" class="btn btn-sm btn-link text-primary p-0 me-2"
                                onclick="$('#extensoes-ids > option').prop('selected', true); $('#extensoes-ids').trigger('change');">Selecionar
                                Todos</button>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0"
                                onclick="$('#extensoes-ids').val(null).trigger('change');">Desmarcar Todos</button>
                        </div>
                    </div>
                    <?= $this->Form->control('extensoes._ids', [
                        'type' => 'select',
                        'multiple' => 'multiple',
                        'class' => 'select2-multiple form-control',
                        'options' => $extensoes,
                        'label' => false,
                        'id' => 'extensoes-ids'
                    ]); ?>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="d-flex justify-content-between align-items-end mb-1">
                        <label class="form-label mb-0" for="usuarios-ids">Usuários</label>
                        <div>
                            <button type="button" class="btn btn-sm btn-link text-primary p-0 me-2"
                                onclick="$('#usuarios-ids > option').prop('selected', true); $('#usuarios-ids').trigger('change');">Selecionar
                                Todos</button>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0"
                                onclick="$('#usuarios-ids').val(null).trigger('change');">Desmarcar Todos</button>
                        </div>
                    </div>
                    <?= $this->Form->control('usuarios._ids', [
                        'type' => 'select',
                        'multiple' => 'multiple',
                        'class' => 'select2-multiple form-control',
                        'options' => $usuarios,
                        'label' => false,
                        'id' => 'usuarios-ids'
                    ]); ?>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3 mt-4 text-end">
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
<!-- Vue e componentes -->
<?= $this->Html->script('vue/cep-autocomplete.js?v=' . filemtime(WWW_ROOT . 'js/vue/cep-autocomplete.js')) ?>
<!-- vue-masks -->
<?= $this->Html->script('vue/vue-masks.js?v=' . filemtime(WWW_ROOT . 'js/vue/vue-masks.js')) ?>

<script>
    $(document).ready(function () {
        if ($.fn.selectpicker) {
            $('.selectpicker').selectpicker();
        }
    });
</script>