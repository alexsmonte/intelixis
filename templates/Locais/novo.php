<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Local $local
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 */
?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Locais', 'url' => ['controller' => 'Locais', 'action' => 'index']], ['title' => 'Novo']]);

?>

<?= $this->element('barra-titulo', ['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Locais') ?></h4>
            </div>

            <?= $this->Form->create($local) ?>
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

                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('nome', ['label' => 'Nome do Local', 'class' => 'form-control',]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('endereco', ['label' => 'Endereço Completo', 'class' => 'form-control',]); ?>
                </div>
            </div>

            <div class="row">
                                <div class="col-md-12 mb-3">
                                        <div data-vue="programacao-semanal"></div>
                                </div>
                        </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2   mb-3">
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
<?= $this->Html->script('vue/programacao-semanal.js') ?>