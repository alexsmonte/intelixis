<?php
    $this->layout = 'entrada';
?>
<div class="auth-login-shape-box position-relative">
    <div class="d-flex align-items-center justify-content-center w-100 z-1 position-relative">
        <div class="card auth-card mb-0 mx-3">
            <div class="card-body pt-5">
                <a href="index.html" class="text-nowrap logo-img text-center d-flex align-items-center justify-content-center mb-5 w-100">
                    <?= $this->Html->image('/img/logos/logo-dark.svg',['class'=>'light-logo', 'alt'=>'JSC']); ?>
                    <?= $this->Html->image('/img/logos/logo-light.svg',['class'=>'dark-logo', 'alt'=>'JSC']); ?>
                </a>
                <?= $this->Form->create(null) ?>
                    <div class="mb-3">
                        <?= $this->Form->control('email',['label'=>'Nome de usuário', 'class'=>'form-control']);?>
                    </div>
                    <div class="mb-4">
                        <?= $this->Form->control('password',['label'=>'Senha', 'class'=>'form-control']);?>
                    </div>
                    <div class="d-md-flex align-items-center justify-content-between mb-4">
                        <?= $this->Html->link(__('Esqueci minha senha'), ['action' => 'esqueciMinhaSenha'],['class'=>'text-primary fw-medium']) ?>
                    </div>
                    <?= $this->Form->button(__('Acessar'),['class'=>'btn btn-primary w-100 mb-4 rounded-pill']) ?>
                    <?= $this->Flash->render() ?>
                    <div class="d-flex align-items-center justify-content-center">
                        <p class="fs-4 mb-0 fw-medium">Ainda não tem conta?</p>
                        <?= $this->Html->link(__('Criar uma conta'), ['controller'=>'Contas','action' => 'criar_conta'],['class'=>'text-primary fw-medium ms-2']) ?>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
