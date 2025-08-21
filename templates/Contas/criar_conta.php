<?php
$this->layout = 'entrada';
?>


<div class="auth-login-shape position-relative w-100">
    <div class="auth-login-wrapper card mb-0 container position-relative z-1 h-100 mh-n100" data-simplebar>
        <div class="card-body">
            <a href="index.html" class="">
                <?= $this->Html->image('/img/logos/logo-dark.svg',['class'=>'light-logo', 'alt'=>'JSC']); ?>
                <?= $this->Html->image('/img/logos/logo-light.svg',['class'=>'dark-logo', 'alt'=>'JSC']); ?>
            </a>
            <?= $this->Form->create($conta) ?>
            <div class="row justify-content-around pt-6 pb-5">
                <div class="col-lg-12 col-xl-12">
                    <h2 class="mb-6 fs-8 fw-bolder">Seja bem-vindo</h2>
                    <p class="text-dark fs-4 mb-7">Preencha os campos abaixo para criar sua conta</p>
                </div>

                <div class="col-lg-6 col-xl-6">
                    <h3>Dados da conta</h3>
                    <?= $this->Form->control('nome', ['class'=>'form-control','label' => 'Nome completo']) ?>
                    <?= $this->Form->control('cpf_cnpj', ['label' => 'CPF/CNPJ','class'=>'form-control']) ?>
                    <?= $this->Form->control('email', ['label' => 'E-Mail','class'=>'form-control']) ?>
                </div>
                <div class="col-lg-6 col-xl-6">
                    <h3>Dados do Usuário</h3>
                    <?= $this->Form->control('usuarios.0.password', ['label' => 'Senha', 'type' => 'password','class'=>'form-control']) ?>
                    <?= $this->Form->control('usuarios.0.password_confirm', ['label' => 'Confirma a senha', 'type' => 'password','class'=>'form-control']) ?>
                </div>
                <div class="col-lg-12 col-xl-12 mt-4">
                    <?= $this->Form->button(__('Finalizar cadastro'),['class'=>'btn btn-primary w-100 mb-7 rounded-pill']) ?>
                </div>
                <div class="d-flex align-items-center">
                    <p class="fs-3 mb-0 fw-medium">Já tem uma conta?</p>
                    <?= $this->Html->link(__('Acessar'), ['controller'=>'Usuarios','action' => 'login'],['class'=>'text-primary fw-bold ms-2 fs-3']) ?>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
</div>

