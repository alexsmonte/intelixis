<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Extensao $extensao
 * @var string[]|\Cake\Collection\CollectionInterface $estabelecimentos
 */
?>

<?php

$this->BreadcrumbsPlus->add([['title' => 'Extensões', 'url'=>['controller' => 'Extensoes', 'action' => 'index']],['title'=> 'Editar']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Extensões') ?></h4>
            </div>

            <?= $this->Form->create($extensao) ?>
            <div class="row">
                <div class="col-md-5 mb-3">
                    <?= $this->Form->control('nome',['label' => 'Nome','class' => 'form-control',]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 mb-3">
                    <?= $this->Form->control('descricao',['label' => 'Descrição','class' => 'form-control',]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('valor',['type'=>'text','label' => 'Valor','class' => 'form-control valor',]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="form-check form-switch py-2">
                        <?= $this->Form->control('ativar',['label' => 'Ativar','class' => 'form-check-input ','id'=>'flexSwitchCheckChecked']); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2 offset-md-10  mb-3">
                            <?= $this->Form->button(__('Salvar'),['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
                        </div>

                    </div>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<?= $this->Html->script(['jquery.price_format.1.8.min.js'],['fullBase'=>true]); ?>
<script type="text/javascript">
    $(document).ready( function() {
        $('.valor').priceFormat({
            prefix: '',
            centsSeparator: ',',
            thousandsSeparator: '.'
        });
    });

</script>