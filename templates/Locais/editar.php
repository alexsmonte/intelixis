<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Servico $local
 * @var string[]|\Cake\Collection\CollectionInterface $estabelecimentos
 * @var string[]|\Cake\Collection\CollectionInterface $convenios
 * @var string[]|\Cake\Collection\CollectionInterface $profissionais
 * @var string[]|\Cake\Collection\CollectionInterface $periodos
 */
$this->BreadcrumbsPlus->add([
    ['title' => 'Locais', 'url' => ['controller' => 'Locais', 'action' => 'index']],
    ['title' => 'Editar']
]);
?>
<?= $this->element('barra-titulo',['title' => $title]); ?>

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
                        'hiddenField' => true
                    ]) ?>
                    <?= $this->Form->label('ativar', 'Ativar', ['class' => 'form-check-label']) ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('nome', [
                        'label' => 'Nome do Local',
                        'class' => 'form-control',
                    ]); ?>
                </div>

                <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('endereco', [
                        'label' => 'Endereço completo',
                        'class' => 'form-control',
                    ]); ?>
                </div>
                
            </div>    
            
            <!-- Programação semanal Vue -->
            <div data-vue="programacao-semanal" 
                 data-programacao-inicial='<?= json_encode($local->periodos ?? []) ?>'>
                <!-- Inputs hidden gerados pelo Vue ficarão aqui dentro do form -->
            </div>

            <div data-vue="programacao-personalizada" 
                data-programacao-inicial='<?= json_encode($local->periodos ?? []) ?>'>
                 <!-- Inputs hidden gerados pelo Vue ficarão aqui dentro do form -->
            </div>

            <div data-vue="dias-indisponiveis" 
                data-periodos-iniciais='<?= json_encode($local->periodos ?? []) ?>'>
            </div>


            <div class="row mt-3">
                <div class="col-md-2 offset-md-10 mb-3">
                    <?= $this->Form->button(__('Salvar'), ['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
                </div>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- vue  -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<!-- Vue e componentes programacao.js -->
<?= $this->Html->script('vue/programacao-semanal.js') ?>
<?= $this->Html->script('vue/programacao-personalizada.js') ?>
<?= $this->Html->script('vue/programacao-indisponivel.js') ?>