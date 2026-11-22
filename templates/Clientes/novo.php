<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cliente $cliente
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 */

?>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Clientes', 'url' => ['controller' => 'Clientes', 'action' => 'index']], ['title' => 'Novo']]);

?>

<?= $this->element('barra-titulo', ['title' => $title]); ?>

<div class="card w-100 position-relative">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Clientes') ?></h4>
            </div>

            <?= $this->Form->create($cliente, ['id' => 'cliente-form']) ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="border-bottom pb-2">Dados pessoais:</h6>
                        </div>
                    </div>
                </div>

                <!-- Widget de Foto -->
                <div class="col-md-12 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div id="fotoPreviewContainer" onclick="document.getElementById('modalFoto').style.display='flex'" title="Clique para adicionar foto"
                            style="width:110px;height:110px;border-radius:50%;border:2px dashed #dee2e6;overflow:hidden;cursor:pointer;flex-shrink:0;background:#f8f9fa;display:flex;align-items:center;justify-content:center;transition:border-color .2s;">
                            <img id="fotoPreview" src="" alt="" style="width:100%;height:100%;object-fit:cover;display:none;" />
                            <div id="fotoPlaceholder" class="text-center text-muted" style="font-size:11px;line-height:1.4;padding:8px;">
                                <i class="ti ti-user-circle" style="font-size:40px;display:block;opacity:.4;"></i>
                                Foto
                            </div>
                        </div>
                        <div>
                            <p class="mb-2 fw-semibold text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;">Foto do cliente</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="document.getElementById('modalFoto').style.display='flex'">
                                    <i class="ti ti-camera me-1"></i>Tirar foto
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="document.getElementById('fotoFileInput').click()">
                                    <i class="ti ti-upload me-1"></i>Carregar imagem
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 d-none" id="btnRemoverFoto" onclick="removerFoto()">
                                    <i class="ti ti-trash me-1"></i>Remover
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="foto_base64" id="fotoBase64Input" />
                    <input type="file" id="fotoFileInput" accept="image/*" style="display:none;" onchange="onFotoFileSelected(this)" />
                </div>
                <!-- /Widget de Foto -->

                <div class="col-md-12 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggleNomeSocial" <?= !empty($cliente->nome_social) ? 'checked="checked"' : '' ?>
                            onchange="document.getElementById('nomeSocialContainer').classList.toggle('d-none')">
                        <label class="form-check-label text-muted fw-bold" for="toggleNomeSocial">Incluir Nome
                            Social</label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('nome', ['label' => 'Nome Completo', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-6 mb-3 <?= !empty($cliente->nome_social) ? '' : 'd-none' ?>" id="nomeSocialContainer">
                    <?= $this->Form->control('nome_social', ['label' => 'Nome Social', 'class' => 'form-control']); ?>
                </div>

                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('data_nascimento', ['label' => 'Nascimento', 'id' => 'data_nascimento', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('sexo', ['label' => 'Sexo', 'type' => 'select', 'options' => ['M' => 'Masculino', 'F' => 'Feminino', 'I' => 'Indefinido'], 'empty' => 'Selecione', 'class' => 'form-select']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('documento', ['label' => 'CPF', 'class' => 'form-control', 'v-mask' => "'documento'", 'maxlength' => 18]); ?>
                </div>

                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('email', ['label' => 'Email', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('telefone', ['label' => 'Telefone', 'id' => 'telefone', 'class' => 'form-control', 'v-mask' => "'telefone'"]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('celular', ['label' => 'Celular', 'id' => 'celular', 'class' => 'form-control', 'v-mask' => "'celular'"]); ?>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="border-bottom pb-2">Endereço:</h6>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-3" data-vue="cep-autocomplete">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('logradouro', ['label' => 'Logradouro', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('numero', ['label' => 'Número', 'class' => 'form-control', 'v-mask' => "'numero'"]); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('complemento', ['label' => 'Complemento', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('bairro', ['label' => 'Bairro', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('cidade', ['label' => 'Cidade', 'class' => 'form-control']); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('uf', ['label' => 'UF', 'class' => 'form-control', 'v-mask' => "'uf'"]); ?>
                </div>

                <div class="col-md-12 mt-4 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggleResponsavel"
                            <?= !empty($cliente->nome_responsavel) ? 'checked="checked"' : '' ?>
                            onchange="document.getElementById('responsavelContainer').classList.toggle('d-none')">
                        <label class="form-check-label fw-bold text-muted" for="toggleResponsavel">Cadastrar Responsável
                            Legal</label>
                    </div>
                </div>

                <div class="col-md-12 <?= !empty($cliente->nome_responsavel) ? '' : 'd-none' ?>" id="responsavelContainer">
                    <div class="row">
                        <div class="col-md-12 mb-3 mt-2">
                            <h6 class="border-bottom pb-2">Responsável Legal:</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?= $this->Form->control('nome_responsavel', ['label' => 'Nome do Responsável', 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-md-3 mb-3">
                            <?= $this->Form->control('documento_responsavel', ['label' => 'CPF', 'class' => 'form-control', 'v-mask' => "'documento'"]); ?>
                        </div>
                        <div class="col-md-3 mb-3">
                            <?= $this->Form->control('telefone_responsavel', ['label' => 'Telefone/Celular', 'class' => 'form-control', 'v-mask' => "'celular'"]); ?>
                        </div>
                        <div class="col-md-2 mb-3">
                            <?= $this->Form->control('parentesco_responsavel', ['label' => 'Parentesco', 'class' => 'form-control']); ?>
                        </div>
                    </div>
                </div>

            </div>

                <!-- Convênios do Cliente -->
                <div class="col-md-12 mt-4 mb-2">
                    <h6 class="border-bottom pb-2">Convênios / Planos de Saúde:</h6>
                </div>
                <div class="col-md-12 mb-3">
                    <?php
                    $conveniosOpcoes = [];
                    foreach ($convenios as $id => $nome) {
                        $conveniosOpcoes[] = ['id' => $id, 'text' => $nome];
                    }
                    ?>
                    <div
                        data-vue="convenios-cliente"
                        data-convenios-opcoes="<?= h(json_encode($conveniosOpcoes)) ?>"
                        data-convenios-iniciais="<?= h(json_encode([])) ?>"
                    ></div>
                </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 offset-md-10 mb-3">
                        <?= $this->Form->button(__('Salvar'), ['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']); ?>
                    </div>
                </div>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- Modal Webcam -->
<div id="modalFoto" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:24px;width:100%;max-width:480px;box-shadow:0 8px 32px rgba(0,0,0,.25);">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 fw-bold">Capturar foto</h6>
            <button type="button" class="btn-close" onclick="fecharModalFoto()"></button>
        </div>
        <video id="webcamVideo" autoplay playsinline style="width:100%;border-radius:8px;background:#000;display:none;"></video>
        <canvas id="webcamCanvas" style="display:none;"></canvas>
        <div id="webcamMsg" class="text-center text-muted py-4" style="font-size:13px;">
            <i class="ti ti-camera-off" style="font-size:32px;display:block;margin-bottom:8px;"></i>
            Câmera não iniciada
        </div>
        <div class="d-flex gap-2 mt-3 justify-content-center flex-wrap">
            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" id="btnIniciarCam" onclick="iniciarWebcam()">
                <i class="ti ti-camera me-1"></i>Iniciar câmera
            </button>
            <button type="button" class="btn btn-success btn-sm rounded-pill px-3 d-none" id="btnCapturar" onclick="capturarFoto()">
                <i class="ti ti-check me-1"></i>Capturar
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="fecharModalFoto()">Cancelar</button>
        </div>
    </div>
</div>

<script>
var _webcamStream = null;

function iniciarWebcam() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
        .then(function(stream) {
            _webcamStream = stream;
            var video = document.getElementById('webcamVideo');
            video.srcObject = stream;
            video.style.display = 'block';
            document.getElementById('webcamMsg').style.display = 'none';
            document.getElementById('btnIniciarCam').classList.add('d-none');
            document.getElementById('btnCapturar').classList.remove('d-none');
        })
        .catch(function() {
            document.getElementById('webcamMsg').innerHTML = '<i class="ti ti-alert-circle" style="font-size:32px;display:block;margin-bottom:8px;color:#dc3545;"></i>Sem acesso à câmera. Use "Carregar imagem".';
        });
}

function capturarFoto() {
    var video = document.getElementById('webcamVideo');
    var canvas = document.getElementById('webcamCanvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    var dataURL = canvas.toDataURL('image/jpeg', 0.85);
    aplicarFoto(dataURL);
    fecharModalFoto();
}

function fecharModalFoto() {
    if (_webcamStream) {
        _webcamStream.getTracks().forEach(function(t){ t.stop(); });
        _webcamStream = null;
    }
    var video = document.getElementById('webcamVideo');
    video.srcObject = null;
    video.style.display = 'none';
    document.getElementById('webcamMsg').style.display = 'block';
    document.getElementById('webcamMsg').innerHTML = '<i class="ti ti-camera-off" style="font-size:32px;display:block;margin-bottom:8px;"></i>Câmera não iniciada';
    document.getElementById('btnIniciarCam').classList.remove('d-none');
    document.getElementById('btnCapturar').classList.add('d-none');
    document.getElementById('modalFoto').style.display = 'none';
}

function onFotoFileSelected(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        mostrarPreview(e.target.result);
        document.getElementById('fotoBase64Input').value = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}

function aplicarFoto(dataURL) {
    // Chamado pela webcam: envia via base64
    mostrarPreview(dataURL);
    document.getElementById('fotoBase64Input').value = dataURL;
    document.getElementById('fotoFileInput').value = '';
}

function mostrarPreview(src) {
    var preview = document.getElementById('fotoPreview');
    preview.src = src;
    preview.style.display = 'block';
    document.getElementById('fotoPlaceholder').style.display = 'none';
    document.getElementById('btnRemoverFoto').classList.remove('d-none');
    document.getElementById('fotoPreviewContainer').style.borderColor = '#4caf50';
    document.getElementById('fotoPreviewContainer').style.borderStyle = 'solid';
}

function removerFoto() {
    document.getElementById('fotoPreview').src = '';
    document.getElementById('fotoPreview').style.display = 'none';
    document.getElementById('fotoPlaceholder').style.display = 'block';
    document.getElementById('fotoBase64Input').value = '';
    document.getElementById('fotoFileInput').value = '';
    document.getElementById('btnRemoverFoto').classList.add('d-none');
    document.getElementById('fotoPreviewContainer').style.borderColor = '#dee2e6';
    document.getElementById('fotoPreviewContainer').style.borderStyle = 'dashed';
}

// Fecha modal ao clicar fora
document.getElementById('modalFoto').addEventListener('click', function(e) {
    if (e.target === this) fecharModalFoto();
});
</script>

<!-- vue  -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<!-- Vue e componentes -->
<?= $this->Html->script('vue/cep-autocomplete.js?v=' . filemtime(WWW_ROOT . 'js/vue/cep-autocomplete.js')) ?>
<!-- vue-masks deve carregar antes dos componentes que usam v-mask -->
<?= $this->Html->script('vue/vue-masks.js') ?>
<?= $this->Html->script('vue/convenios-cliente.js?v=' . filemtime(WWW_ROOT . 'js/vue/convenios-cliente.js')) ?>