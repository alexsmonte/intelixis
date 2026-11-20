<?php
$this->BreadcrumbsPlus->add([['title' => 'Planos', 'url' => ['controller' => 'Planos', 'action' => 'index']], ['title' => 'Novo']]);
?>

<!-- 1. CDN do Iconify -->
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>

<?= $this->element('barra-titulo', ['title' => $title ?? 'Planos']); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Cadastrar Plano') ?></h4>
            </div>

            <?= $this->Form->create($plano, ['id' => 'plano-form']) ?>

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

                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('nome', ['label' => 'Nome do Plano', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('valor', ['type' => 'text', 'label' => 'Valor do plano', 'class' => 'form-control valor']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('numero_whatsapp', ['type' => 'number', 'label' => 'Número de WhatsApp', 'class' => 'form-control', 'v-mask' => "'numero'"]); ?>
                </div>

                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('resumo', ['label' => 'Resumo', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('descricao', ['label' => 'Descrição', 'class' => 'form-control']); ?>
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
<!-- vue-masks -->
<?= $this->Html->script('vue/vue-masks.js') ?>

<!-- jquery price format -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<?= $this->Html->script(['jquery.price_format.1.8.min.js'], ['fullBase' => true]); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.valor').priceFormat({
            prefix: '',
            centsSeparator: ',',
            thousandsSeparator: '.'
        });
    });
</script>