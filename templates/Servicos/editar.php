<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Servico $servico
 * @var string[]|\Cake\Collection\CollectionInterface $estabelecimentos
 * @var string[]|\Cake\Collection\CollectionInterface $convenios
 * @var string[]|\Cake\Collection\CollectionInterface $profissionais
 * @var string[]|\Cake\Collection\CollectionInterface $periodos
 */
$estabelecimento_id = $servico->estabelecimento_id;



$this->BreadcrumbsPlus->add([
    ['title' => 'Serviços', 'url' => ['controller' => 'Servicos', 'action' => 'index']],
    ['title' => 'Editar']
]);
?>
<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Serviços') ?></h4>
            </div>

            <?= $this->Form->create($servico) ?>

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
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('estabelecimento_id', [
                        'label' => 'Estabelecimento',
                        'class' => 'form-control',
                        'options' => $estabelecimentos
                    ]); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('nome', [
                        'label' => 'Nome do Serviço',
                        'class' => 'form-control',
                    ]); ?>
                </div>

                <div class="col-md-2 mb-3">
                    <div class="position-relative">
                        <?= $this->Form->control('duracao', [
                            'type' => 'number',
                            'min' => 5,
                            'step' => 5,
                            'class' => 'form-control',
                            'placeholder' => 'Ex: 30',
                            'label' => 'Duração'
                        ]) ?>
                        <span style="position: absolute; top: 50%; align: center; right: 70px; transform: translateY(0%); pointer-events: none; color: #6c757d;">Minutos</span>
                    </div>
                </div>
            </div>

            <div class="row"> 
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('descricao', ['label' => 'Descrição','class' => 'form-control']); ?>
                </div> 
            </div>

            <!-- Convênios -->                            
            <div class="row"> 
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('convenios._ids', [
                        'type' => 'select',
                        'multiple' => 'multiple',
                        'class' => 'select2-multiple form-control',
                        'options' => $convenios,
                        'label' => 'Convênios que cobrem este serviço'
                    ]); ?>
                </div>
            </div>

            <!-- Profissionais -->
            <div class="row"> 
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('profissionais._ids', [
                        'type' => 'select',
                        'multiple' => 'multiple',
                        'class' => 'select2-multiple form-control',
                        'options' => $profissionais,
                        'label' => 'Profissionais que atendem este serviço'
                    ]); ?>
                </div>
            </div>

            

            <div 
  data-vue="programacao-semanal" 
  data-estabelecimento-id="<?= h($estabelecimento_id) ?>" 
  data-programacao-inicial='<?= json_encode($servico->periodos ?? []) ?>'>
 
</div>

           

            <div class="row">
                <div class="col-md-2 offset-md-10 mb-3">
                    <?= $this->Form->button(__('Salvar'), ['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
                </div>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- Vue e componente programacao-semanal.js -->

<?= $this->Html->script('vue/programacao-semanal.js') ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const el = document.querySelector('[data-vue="programacao-semanal"]');
    const app = Vue.createApp(ProgramacaoSemanal, {
        estabelecimentoId: el.dataset.estabelecimentoId,
        programacaoInicial: <?= json_encode($servico->periodos ?? []) ?>
    });
    app.mount(el);
});
</script>
