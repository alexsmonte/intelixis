<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Convenio $convenio
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 * @var \Cake\Collection\CollectionInterface|string[] $convenios
 * @var \Cake\Collection\CollectionInterface|string[] $profissionais
 */
?>
<?php
$this->BreadcrumbsPlus->add([['title' => 'Convênios', 'url' => ['controller' => 'Convenios', 'action' => 'index']], ['title' => 'Novo']]);
?>




<?= $this->element('barra-titulo', ['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
  <div class="px-4 py-3 border-bottom">
    <div class="card-body p-4">
      <div class="d-flex mb-3 align-items-center">
        <h4 class="card-title mb-0"><?= __('Cadastrar Convênio') ?></h4>
      </div>

      <?= $this->Form->create($convenio) ?>
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
          <?= $this->Form->control('nome', ['label' => 'Nome do Convênio', 'class' => 'form-control',]); ?>
        </div>
        <div class="col-md-3 mb-3">
          <?= $this->Form->control('registro_ans', ['label' => 'Registro ANS', 'class' => 'form-control',]); ?>
        </div>
        <div class="col-md-3 mb-3">
          <?= $this->Form->control('cnpj', ['label' => 'CNPJ', 'class' => 'form-control']); ?>
        </div>
        <div class="col-md-3 mb-3">
          <?= $this->Form->control('telefone', ['label' => 'Telefone', 'class' => 'form-control',]); ?>
        </div>
        <div class="col-md-3 mb-3">
          <?= $this->Form->control('email', ['label' => 'Email', 'class' => 'form-control',]); ?>
        </div>
        <div class="col-md-3 mb-3">
          <?= $this->Form->control('site', ['label' => 'Site', 'class' => 'form-control',]); ?>
        </div>

      </div>

      <div class="row">

        <!-- Convênios -->

        <div class="row">
          <div class="col-md-12 mb-3">
            <?= $this->Form->control('servicos._ids', [
              'type' => 'select',
              'multiple' => 'multiple',
              'class' => 'select2-multiple form-control',
              'options' => $servicos,
              'label' => 'Serviços cobertos por este convênio.'

            ]); ?>
          </div>

        </div>
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

        <div class="row">
          <div class="col-md-12 mb-3">
            <div data-vue="programacao-semanal" ?>">
            </div>
          </div>
        </div>


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