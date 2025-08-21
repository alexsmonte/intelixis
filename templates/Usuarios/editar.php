<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 * @var string[]|\Cake\Collection\CollectionInterface $contas
 * @var string[]|\Cake\Collection\CollectionInterface $estabelecimentos
 */

$this->BreadcrumbsPlus->add([['title' => 'Usuarios', 'url'=>['controller' => 'Usuarios', 'action' => 'index']],['title'=> 'Novo']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0">Dados do usuário</h4>
            </div>

            <?= $this->Form->create($usuario) ?>
            <div class="row">
                <div class="col-md-5 mb-3">
                    <?= $this->Form->control('nome',['label' => 'Nome','class' => 'form-control',]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('email',['label' => 'E-mail','class' => 'form-control',]); ?>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <?= $this->Form->control('password',['label' => 'Senha','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                <?= $this->Form->control('password_confirm',['type'=>'password','label' => 'Confirme sua senha','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-3 mb-3 form-check form-check-inline">
                <?= $this->Form->control('ativar',['label' => 'Ativar','class' => 'form-check-input',]); ?>
            </div>
            <div class="col-md-3 mb-3">
                <?php $this->Form->control('estabelecimentos._ids', ['label' => 'Unidades','class' => 'form-control','options' => $estabelecimentos]); ?>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 offset-md-10  mb-3">
                        <?= $this->Form->button(__('Salvar'),['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>

        </div>

    </div>
</div>
</div></div>