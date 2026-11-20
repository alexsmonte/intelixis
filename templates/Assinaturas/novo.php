<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Assinatura $assinatura
 * @var \Cake\Collection\CollectionInterface|string[] $contas
 * @var \Cake\Collection\CollectionInterface|string[] $planos
 */
$this->BreadcrumbsPlus->add([
    ['title' => 'Assinaturas', 'url' => ['controller' => 'Assinaturas', 'action' => 'index']],
    ['title' => 'Novo']
]);
?>

<?= $this->element('barra-titulo', ['title' => $title ?? 'Nova Assinatura']); ?>

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
                <h4 class="card-title mb-0"><?= __('Nova Assinatura') ?></h4>
            </div>

            <?= $this->Form->create($assinatura) ?>

            <div class="col-md-3 mb-3">
                <div class="form-check form-switch">
                    <?= $this->Form->checkbox('status', [
                        'class' => 'form-check-input',
                        'id' => 'status',
                        'value' => 'Ativa',
                        'hiddenField' => 'Inativa',
                        'checked' => true
                    ]) ?>
                    <?= $this->Form->label('status', 'Status', ['class' => 'form-check-label']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h6 class="border-bottom pb-2 mb-3 mt-3">Dados da Assinatura:</h6>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('conta_id', [
                        'label' => 'Conta',
                        'class' => 'selectpicker form-select',
                        'data-live-search' => 'true',
                        'options' => $contas,
                        'empty' => 'Selecione uma conta...'
                    ]); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('plano_id', [
                        'label' => 'Plano',
                        'class' => 'selectpicker form-select',
                        'data-live-search' => 'true',
                        'options' => $planos,
                        'empty' => 'Selecione um plano...'
                    ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('data_vencimento', [
                        'label' => 'Data de Vencimento',
                        'class' => 'form-control',
                        'type' => 'date'
                    ]); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('desconto', [
                        'label' => 'Desconto (R$)',
                        'class' => 'form-control',
                        'type' => 'text',
                        'v-mask' => "'valor'"
                      ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-end mt-4">
                    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success rounded-pill']) ?>
                    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn bg-danger-subtle text-danger rounded-pill ms-2']) ?>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- vue e mascaras -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/vue-masks.js?v=' . filemtime(WWW_ROOT . 'js/vue/vue-masks.js')) ?>