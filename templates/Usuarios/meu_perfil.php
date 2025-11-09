<?php

$this->BreadcrumbsPlus->add([['title' => 'Meu perfil', 'url'=>['controller' => 'Usuarios', 'action' => 'meuPerfil']]]);

$usuario->password  =   '';
?>

<?= $this->element('barra-titulo',['title' => $title]); ?>


<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0">Meus dados</h4>
            </div>
            <?= $this->Form->create($usuario,['type' => 'file']) ?>
            <div class="row">
                <div class="col-lg-6 d-flex align-items-stretch">
                    <div class="card w-100 border position-relative overflow-hidden">
                        <div class="card-body p-4">
                            <h4 class="card-title">Minha foto</h4>
                            <p class="card-subtitle mb-4">Altere sua foto de perfil aqui</p>

                            <div class="text-center">
                                <?php if (empty($usuario->foto)) { ?>
                                    <?= $this->Html->image(
                                        'profile/usuario-padrao.png',
                                        ['fullBase' => true, 'alt' => 'Escolha uma foto', 'class' => 'img-fluid', 'width' => '120']
                                    ) ?>
                                <?php } else { ?>
                                    <?= $this->Html->image(
                                        '/img/' . $usuario->foto,
                                        ['fullBase' => true, 'alt' => $usuario->nome, 'class' => 'rounded-circle', 'width' => '120']
                                    ) ?>
                                <?php } ?>

                                <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                                    <a title="Escolher foto" alt="Escolher foto" class="btn btn-primary file-wrapper" tabindex="-1" href="#upload">
                                        <i class="fa fa-upload"></i> Escolher foto
                                        <?= $this->Form->control('foto', [
                                            'label' => false,
                                            'templates' => ['inputContainer' => '{{content}}'],
                                            'type' => 'file',
                                            'required' => false
                                        ]) ?>
                                    </a>

                                    <!-- Botão remover -->
                                    <button type="button" id="btn-remover-foto" class="btn bg-danger-subtle text-danger">
                                        <i class="fa fa-trash"></i>&nbsp;Remover
                                    </button>
                                </div>

                                <!-- Campo oculto para sinalizar remoção -->
                                <?= $this->Form->hidden('remover_foto', ['value' => 0, 'id' => 'remover-foto-input']) ?>

                                <p class="mb-0">Somente fotos em JPG ou PNG. Máximo de 800Kb</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-stretch">
                    <div class="card w-100 border position-relative overflow-hidden">
                        <div class="card-body p-4">
                            <h4 class="card-title">Alterar dados</h4>
                            <p class="card-subtitle mb-4">Para alterar seus dados e sua senha, confirme abaixo</p>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <?= $this->Form->control('nome',['label' => 'Nome','class' => 'form-control',]); ?>
                                </div>

                                <div class="col-md-8 mb-3">
                                    <?= $this->Form->control('email',['label' => 'E-mail','class' => 'form-control',]); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <?= $this->Form->control('password',['label' => 'Senha','class' => 'form-control','required'=>'false']); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <?= $this->Form->control('password_confirm',['type'=>'password','label' => 'Confirme sua senha','class' => 'form-control',]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 offset-md-10  mb-3">
                        <?= $this->Form->button(__('Salvar'),['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
                    </div>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>

    </div>
</div>
</div></div>


<style type="text/css">
    .file-wrapper { cursor: pointer; display: inline-block; overflow: hidden; position: relative; }
    .file-wrapper input { cursor: pointer; font-size: 100px; height: 100%; filter: alpha(opacity=1); -moz-opacity: 0.01; opacity: 0.01; position: absolute; right: 0; top: 0; } .file-wrapper .button { background: #79130e; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; color: #fff; cursor: pointer; display: inline-block; font-size: 11px; font-weight: bold; margin-right: 5px; padding: 4px 18px; text-transform: uppercase; }
</style>

<script>
    document.getElementById('btn-remover-foto').addEventListener('click', function() {
        if (confirm('Tem certeza que deseja remover sua foto de perfil?')) {
            document.getElementById('remover-foto-input').value = 1;
            // Limpa a seleção do input file, se houver
            const fileInput = document.querySelector('input[type=file][name=foto]');
            if (fileInput) fileInput.value = '';
            // Submete o formulário automaticamente
            this.closest('form').submit();
        }
    });
</script>