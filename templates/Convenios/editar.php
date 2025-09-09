<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Convenio $convenio
 * @var string[]|\Cake\Collection\CollectionInterface $estabelecimentos
 * @var string[]|\Cake\Collection\CollectionInterface $profissionais
 * @var string[]|\Cake\Collection\CollectionInterface $servicos
 */
?>

<?php

$this->BreadcrumbsPlus->add([['title' => 'Convênios', 'url' => ['controller' => 'Convenios', 'action' => 'index']], ['title' => 'Editar']]);

?>

<?= $this->element('barra-titulo', ['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Editar Convênio') ?></h4>
            </div>

            <?= $this->Form->create($convenio) ?>
            <div class="row">
                <div class="form-check form-switch col-md-3 mb-3">
                    <?= $this->Form->checkbox('ativar', [
                        'class' => 'form-check-input',
                        'id' => 'ativar',
                        'default' => true,
                        'hiddenField' => true
                    ]) ?>
                    <?= $this->Form->label('ativar', 'Ativar', ['class' => 'form-check-label']) ?>
                </div>
            </div>
            <div class="row">

                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('nome', ['label' => 'Nome', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('registro_ans', ['label' => 'Registro ANS', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('cnpj', ['label' => 'CNPJ', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('telefone', ['label' => 'Telefone', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email', ['label' => 'Email', 'class' => 'form-control',]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('site', ['label' => 'Website', 'class' => 'form-control',]); ?>
                </div>

                <!-- Profissionais -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <?= $this->Form->control('profissionais._ids', [
                            'type' => 'select',
                            'multiple' => 'multiple',
                            'class' => 'select2-multiple form-control',
                            'options' => $profissionais,
                            'label' => 'Profissionais que atendem este convênio'
                        ]); ?>
                    </div>
                </div>
                <!-- Servicos -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <?= $this->Form->control('servicos._ids', [
                            'type' => 'select',
                            'multiple' => 'multiple',
                            'class' => 'select2-multiple form-control',
                            'options' => $servicos,
                            'label' => ' Serviços cobertos por este convênio'
                        ]); ?>
                    </div>
                </div>

                <!-- Programação semanal Vue -->
                <div data-vue="programacao-semanal"
                    data-programacao-inicial='<?= json_encode($convenio->periodos ?? []) ?>'>
                    <!-- Inputs hidden gerados pelo Vue ficarão aqui dentro do form -->
                </div>

                <div data-vue="programacao-personalizada"
                    data-programacao-inicial='<?= json_encode($convenio->periodos ?? []) ?>'>
                    <!-- Inputs hidden gerados pelo Vue ficarão aqui dentro do form -->
                </div>

                <div data-vue="dias-indisponiveis"
                    data-periodos-iniciais='<?= json_encode($convenio->periodos ?? []) ?>'>
                    <!-- Inputs hidden gerados pelo Vue ficarão aqui dentro do form -->
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
<?= $this->Html->script('vue/programacao-semanal.js') ?>
<?= $this->Html->script('vue/programacao-personalizada.js') ?>
<?= $this->Html->script('vue/programacao-indisponivel.js') ?>