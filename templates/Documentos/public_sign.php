<?php
/**
 * View: Documentos/public_sign.php
 * Layout: assinar (mobile-first, sem autenticação)
 *
 * Variáveis disponíveis:
 *  @var \App\Model\Entity\Documento $documento
 *  @var bool $jaAssinado
 */
$this->assign('title', 'Assinar Documento');

$pacienteNome = h($documento->procedimento->consulta->cliente->nome ?? '');
$docTitulo = h($documento->titulo ?? $documento->tipo ?? 'Documento');
$profNome = h($documento->profissional->nome ?? '');
$estabelecimento = h($documento->estabelecimento->nome_fantasia ?? $documento->estabelecimento->razao_social ?? '');
$codigoValidacao = $documento->codigo_validacao;
$csrfToken = $this->request->getAttribute('csrfToken') ?? '';
?>

<!-- Importação do Iconify caso não exista no layout -->
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
    /* Estilos mínimos para os componentes de câmera e canvas */
    .selfie-preview-wrap {
        position: relative;
        width: 180px;
        height: 180px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: #f8fafc;
        border: 3px dashed #e2e8f0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color .2s;
    }

    .selfie-preview-wrap.captured {
        border-color: #198754;
        border-style: solid;
    }

    .selfie-preview-wrap video,
    .selfie-preview-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .sig-wrap {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        overflow: hidden;
        position: relative;
        touch-action: none;
    }

    .sig-wrap canvas {
        display: block;
        width: 100% !important;
        height: 180px !important;
        touch-action: none;
        cursor: crosshair;
    }

    .sig-hint {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #cbd5e1;
        font-size: .85rem;
        pointer-events: none;
        transition: opacity .3s;
    }

    .btn-assinar .spinner {
        width: 20px;
        height: 20px;
        border: 2.5px solid rgba(255, 255, 255, .4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .7s linear infinite;
        display: none;
    }

    .btn-assinar.loading .spinner { display: inline-block; }
    .btn-assinar.loading .btn-text { display: none; }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <?php if ($jaAssinado): ?>
                <!-- ====== DOCUMENTO JÁ ASSINADO ====== -->
                <div class="card w-100 position-relative overflow-hidden mt-2 shadow-sm border-0">
                    <div class="card-header bg-primary text-white p-4 text-center border-0">
                        <div class="small opacity-75 mb-1 text-uppercase fw-bold"><?= $estabelecimento ?></div>
                        <h4 class="mb-1 text-white"><?= $docTitulo ?></h4>
                        <div class="small opacity-75 mt-1"><?= $pacienteNome ?></div>
                    </div>
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center">   
                        <iconify-icon icon="mingcute:lock-fill" class="text-success mb-3" style="font-size: 5rem;"></iconify-icon>
                        
                        <div class="badge bg-success-subtle text-success rounded-pill px-3 py-2 mb-3 fs-6">
                            <iconify-icon icon="solar:check-read-linear" class="align-middle me-1"></iconify-icon> Documento assinado
                        </div>
                        
                        <p class="text-muted mb-2">
                            Este documento já foi <strong>assinado eletronicamente</strong>.
                        </p>
                        <p class="text-muted small mb-3">
                            Registrado em: <?= $documento->assinatura_data ? $documento->assinatura_data->format('d/m/Y \à\s H:i') : 'Data registrada' ?>
                        </p>
                        
                        <div class="bg-light p-3 rounded-3 text-start">
                            <label class="form-label text-muted small text-uppercase fw-bold mb-1">Hash SHA-256</label>
                            <code class="d-block text-dark small" style="word-break:break-all;"><?= h($documento->assinatura_hash) ?></code>
                        </div>

                        <div class="mt-4">
                            <a href="<?= $this->Url->build(['action' => 'publicPdf', $codigoValidacao]) ?>" target="_blank" class="btn btn-outline-primary rounded-pill fw-bold w-100">
                                <iconify-icon icon="solar:document-text-bold" class="align-middle me-1"></iconify-icon> Visualizar Documento
                            </a>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- ====== FORMULÁRIO DE ASSINATURA ====== -->
                <div class="card w-100 position-relative overflow-hidden mt-2 shadow-sm border-0" id="formCard">
                    <div class="card-header bg-primary text-white p-4 text-center border-0">
                        <div class="small opacity-75 mb-1 text-uppercase fw-bold"><?= $estabelecimento ?></div>
                        <h4 class="mb-1 text-white"><?= $docTitulo ?></h4>
                        <div class="small opacity-75 mt-1">Paciente: <?= $pacienteNome ?></div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Alert de erro -->
                        <div class="alert alert-danger d-none align-items-center gap-2 mb-4" id="alertErro"></div>

                        <!-- Passo 0: LER DOCUMENTO -->
                        <div class="text-center mb-4">
                            <p class="text-muted small mb-2">Antes de assinar, você deve ler o documento clicando no botão abaixo:</p>
                            <button type="button" class="btn btn-outline-primary rounded-pill fw-bold py-2 px-4 w-100 position-relative" id="btnLerDocumento">
                                <span class="btn-text"><iconify-icon icon="solar:document-text-bold" class="fs-5 align-middle me-1"></iconify-icon> Ler Documento</span>
                                <div class="spinner border border-2 border-primary border-top-transparent rounded-circle position-absolute top-50 start-50 translate-middle" style="width: 20px; height: 20px; display: none;" id="spinnerLer"></div>
                            </button>
                        </div>

                        <hr class="my-4">

                        <!-- PASSO 1: SELFIE -->
                        <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">1</span>
                            Tire uma foto (selfie)
                        </h6>

                        <!-- Responsável Legal -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="chkResponsavel">
                            <label class="form-check-label text-muted small fw-bold" for="chkResponsavel">Assinar como Responsável Legal</label>
                        </div>
                        
                        <div id="formResponsavel" class="bg-light border rounded p-3 mb-4 d-none">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Nome do Responsável Legal</label>
                                <input type="text" id="respNome" class="form-control" placeholder="Ex: Maria da Silva">
                            </div>
                            <div class="mb-0">
                                <label class="form-label small fw-bold text-muted">CPF do Responsável Legal</label>
                                <input type="text" id="respCpf" class="form-control" placeholder="Ex: 000.000.000-00" oninput="this.value = this.value.replace(/\D/g,'').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2')" maxlength="14">
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <div class="selfie-preview-wrap" id="selfieWrap">
                                <div class="text-muted small d-flex flex-column align-items-center gap-2" id="selfiePlaceholder">
                                    <iconify-icon icon="solar:camera-bold" class="fs-1 text-secondary opacity-50"></iconify-icon>
                                    Sua foto aqui
                                </div>
                                <video id="cameraVideo" autoplay playsinline style="display:none;"></video>
                                <img id="selfieImg" style="display:none;" alt="Selfie capturada" />
                            </div>

                            <div id="btnsSelfie">
                                <button class="btn btn-primary rounded-pill fw-bold" id="btnAbrirCamera" type="button">
                                    <iconify-icon icon="solar:camera-line-duotone" class="fs-5 align-middle me-1"></iconify-icon> Abrir Câmera
                                </button>
                            </div>
                            <div id="btnsCaptura" class="d-none flex-column align-items-center gap-2">
                                <button class="btn btn-success rounded-pill fw-bold" id="btnCapturar" type="button">
                                    <iconify-icon icon="solar:record-circle-bold" class="fs-5 align-middle me-1"></iconify-icon> Capturar Foto
                                </button>
                                <button class="btn btn-link text-muted text-decoration-none small" id="btnRefazer" type="button">Refazer</button>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- PASSO 2: ASSINATURA -->
                        <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">2</span>
                            Assine no campo abaixo
                        </h6>
                        <div class="sig-wrap mb-2">
                            <canvas id="signatureCanvas"></canvas>
                            <div class="sig-hint" id="sigHint">✍️ Assine com o dedo</div>
                        </div>
                        <div class="text-end mb-4">
                            <button class="btn btn-link text-muted text-decoration-none p-0 small" id="btnLimpar" type="button">Limpar assinatura</button>
                        </div>

                        <hr class="my-4">

                        <!-- PASSO 3: CONFIRMAR -->
                        <div class="d-grid mb-3">
                            <button class="btn btn-primary rounded-pill py-2 fw-bold btn-assinar d-flex justify-content-center align-items-center" id="btnAssinar" type="button" disabled>
                                <div class="spinner"></div>
                                <span class="btn-text">
                                    <iconify-icon icon="solar:check-read-bold" class="fs-5 align-middle me-2"></iconify-icon> Concluir e Assinar
                                </span>
                            </button>
                        </div>

                        <div class="text-center text-muted small lh-sm">
                            Ao assinar, você confirma que leu e concorda com o conteúdo.<br>
                            Esta assinatura tem validade jurídica (MP 2.200-2/2001).
                        </div>
                    </div>
                </div>

                <!-- Tela de sucesso (hidden) -->
                <div class="card w-100 position-relative overflow-hidden mt-2 shadow-sm border-0 d-none" id="telaSucesso">
                   <div class="card-body p-5 text-center d-flex flex-column align-items-center">
                        <iconify-icon icon="solar:check-circle-bold" class="text-success mb-3" style="font-size: 5rem;"></iconify-icon>
                        <h3 class="text-success mb-3 fw-bold">Documento assinado!</h3>
                        <p class="text-muted mb-4">
                            Sua assinatura foi registrada com sucesso.<br>
                            Um protocolo de prova foi anexado ao documento.
                        </p>
                        
                        <div class="bg-light p-3 rounded-3 text-start mb-4">
                            <label class="form-label text-muted small text-uppercase fw-bold mb-1">Código de validação</label>
                            <code class="d-block text-dark small" style="word-break:break-all;"><?= h($codigoValidacao) ?></code>
                        </div>

                        <a href="<?= $this->Url->build(['action' => 'publicPdf', $codigoValidacao]) ?>" target="_blank" class="btn btn-outline-primary rounded-pill fw-bold w-100">
                            <iconify-icon icon="solar:document-text-bold" class="align-middle me-1"></iconify-icon> Baixar / Visualizar Cópia
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-center text-muted small mt-4 opacity-75">
                <iconify-icon icon="solar:shield-check-bold" class="align-middle"></iconify-icon>
                Intelixis · Assinatura Eletrônica Segura
            </div>
        </div>
    </div>
</div>

<?php if (!$jaAssinado): ?>
    <!-- SignaturePad via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
    <script>
        (function () {
            'use strict';

            // ===== Config =====
            const BASE_URL = window.location.origin; // Opcional, depende do router
            const SIGN_URL = window.location.href; 
            const READ_LOG_URL = "<?= $this->Url->build(['action' => 'logLeitura', $codigoValidacao]) ?>";
            const READ_PDF_URL = "<?= $this->Url->build(['action' => 'publicPdf', $codigoValidacao]) ?>";
            const CSRF_TOKEN = "<?= h($csrfToken) ?>";

            // ===== Elementos =====
            const selfieWrap = document.getElementById('selfieWrap');
            const selfiePlaceholder = document.getElementById('selfiePlaceholder');
            const cameraVideo = document.getElementById('cameraVideo');
            const selfieImg = document.getElementById('selfieImg');
            const btnAbrirCamera = document.getElementById('btnAbrirCamera');
            const btnsSelfie = document.getElementById('btnsSelfie');
            const btnsCaptura = document.getElementById('btnsCaptura');
            const btnCapturar = document.getElementById('btnCapturar');
            const btnRefazer = document.getElementById('btnRefazer');
            const canvas = document.getElementById('signatureCanvas');
            const sigHint = document.getElementById('sigHint');
            const btnLimpar = document.getElementById('btnLimpar');
            const btnAssinar = document.getElementById('btnAssinar');
            const alertErro = document.getElementById('alertErro');
            const formCard = document.getElementById('formCard');
            const telaSucesso = document.getElementById('telaSucesso');

            const chkResponsavel = document.getElementById('chkResponsavel');
            const formResponsavel = document.getElementById('formResponsavel');
            const respNome = document.getElementById('respNome');
            const respCpf = document.getElementById('respCpf');

            const btnLerDocumento = document.getElementById('btnLerDocumento');
            const spinnerLer = document.getElementById('spinnerLer');

            chkResponsavel.addEventListener('change', function() {
                if(this.checked) {
                    formResponsavel.classList.remove('d-none');
                } else {
                    formResponsavel.classList.add('d-none');
                }
                checkPronto();
            });

            // ===== Estado =====
            let selfieBase64 = null;
            let stream = null;
            let signaturePad = null;
            let leuDocumento = false; // Só deixa habilitar se já abriu

            // ===== Erro =====
            function mostrarErro(msg) {
                alertErro.innerHTML = `<iconify-icon icon="solar:danger-circle-bold" class="fs-5"></iconify-icon> <div>${msg}</div>`;
                alertErro.classList.remove('d-none');
                alertErro.classList.add('d-flex');
                alertErro.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // ===== Fetch IP Público =====
            const obterIpPublico = async () => {
                try {
                    const resp = await fetch('https://api.ipify.org?format=json', { timeout: 3000 });
                    const data = await resp.json();
                    return data.ip;
                } catch (e) {
                    return null;
                }
            };

            // ===== Ler Documento (Log) =====
            btnLerDocumento.addEventListener('click', async function() {
                // UI state
                btnLerDocumento.disabled = true;
                btnLerDocumento.querySelector('.btn-text').style.opacity = '0';
                spinnerLer.style.display = 'block';

                const geolocalizacao = await obterLocalizacao();
                const ipPublico = await obterIpPublico();

                try {
                    const resp = await fetch(READ_LOG_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-Token': CSRF_TOKEN
                        },
                        body: JSON.stringify({ 
                            geolocalizacao: geolocalizacao,
                            ip_publico: ipPublico
                        }),
                    });

                    const data = await resp.json();
                    
                    // Independentemente de dar erro na gravação do log, o paciente tem que poder ler
                    window.open(READ_PDF_URL, '_blank');
                    
                    leuDocumento = true;
                    btnLerDocumento.classList.remove('btn-outline-primary');
                    btnLerDocumento.classList.add('btn-success');
                    btnLerDocumento.querySelector('.btn-text').innerHTML = '<iconify-icon icon="solar:check-circle-bold" class="fs-5 align-middle me-1"></iconify-icon> Documento Lido';
                    checkPronto();

                } catch (err) {
                    // Falha de internet, vamos liberar pra ler mesmo assim
                    window.open(READ_PDF_URL, '_blank');
                    leuDocumento = true;
                    checkPronto();
                } finally {
                    btnLerDocumento.disabled = false;
                    btnLerDocumento.querySelector('.btn-text').style.opacity = '1';
                    spinnerLer.style.display = 'none';
                }
            });


            // ===== SignaturePad =====
            function initSignaturePad() {
                canvas.width = canvas.offsetWidth;
                canvas.height = 180;
                signaturePad = new SignaturePad(canvas, {
                    penColor: '#1e293b',
                    minWidth: 1.5,
                    maxWidth: 3.5,
                });
                signaturePad.addEventListener('beginStroke', function () {
                    sigHint.style.opacity = '0';
                    checkPronto();
                });
            }

            window.addEventListener('resize', function () {
                if (!signaturePad) return;
                const data = signaturePad.toData();
                canvas.width = canvas.offsetWidth;
                canvas.height = 180;
                signaturePad.clear();
                signaturePad.fromData(data);
            });
            setTimeout(initSignaturePad, 200); // init delay to assure canvas gets width

            // ===== CÂMERA ======
            btnAbrirCamera.addEventListener('click', async function () {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                        audio: false,
                    });
                    cameraVideo.srcObject = stream;
                    cameraVideo.style.display = 'block';
                    selfiePlaceholder.style.display = 'none';
                    selfieImg.style.display = 'none';
                    
                    btnsSelfie.classList.add('d-none');
                    btnsCaptura.classList.remove('d-none');
                    btnsCaptura.classList.add('d-flex');
                } catch (err) {
                    mostrarErro('Não foi possível acessar a câmera. Verifique as permissões do seu navegador.');
                }
            });

            btnCapturar.addEventListener('click', function () {
                const offscreen = document.createElement('canvas');
                offscreen.width = cameraVideo.videoWidth || 640;
                offscreen.height = cameraVideo.videoHeight || 480;
                offscreen.getContext('2d').drawImage(cameraVideo, 0, 0);

                selfieBase64 = offscreen.toDataURL('image/jpeg', 0.85);

                // Parar câmera
                if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
                cameraVideo.style.display = 'none';

                // Mostrar preview
                selfieImg.src = selfieBase64;
                selfieImg.style.display = 'block';
                selfiePlaceholder.style.display = 'none';
                selfieWrap.classList.add('captured');

                btnsCaptura.classList.add('d-none');
                btnsCaptura.classList.remove('d-flex');
                btnsSelfie.classList.remove('d-none');
                
                btnAbrirCamera.innerHTML = '<iconify-icon icon="solar:camera-line-duotone" class="fs-5 align-middle me-1"></iconify-icon> Refazer selfie';
                btnAbrirCamera.classList.replace('btn-primary', 'btn-outline-primary');

                checkPronto();
            });

            btnRefazer.addEventListener('click', function () {
                selfieBase64 = null;
                selfieImg.style.display = 'none';
                cameraVideo.style.display = 'block';
                selfieWrap.classList.remove('captured');
                checkPronto();
            });

            // ===== Limpar assinatura =====
            btnLimpar.addEventListener('click', function () {
                signaturePad.clear();
                sigHint.style.opacity = '1';
                checkPronto();
            });

            // ===== Habilitar botão somente quando pronto =====
            function checkPronto() {
                const assinado = signaturePad && !signaturePad.isEmpty();
                // Agora tb exige leuDocumento
                btnAssinar.disabled = !(selfieBase64 && assinado && leuDocumento);
            }

            canvas.addEventListener('pointerup', checkPronto);

            const obterLocalizacao = () => new Promise((resolve) => {
                if (!navigator.geolocation) {
                    resolve('Não suportado pelo navegador');
                    return;
                }
                navigator.geolocation.getCurrentPosition(
                    (pos) => resolve(`Lat ${pos.coords.latitude.toFixed(6)}, Lng ${pos.coords.longitude.toFixed(6)}`),
                    (err) => resolve('Acesso negado/indisponível'),
                    { timeout: 5000, maximumAge: 0 }
                );
            });

            // ===== Enviar assinatura =====
            btnAssinar.addEventListener('click', async function () {
                alertErro.classList.add('d-none');
                alertErro.classList.remove('d-flex');

                if (!leuDocumento) { mostrarErro('Você precisa ler o documento primeiro.'); return; }
                if (!selfieBase64) { mostrarErro('Por favor, tire a selfie primeiro.'); return; }
                if (!signaturePad || signaturePad.isEmpty()) { mostrarErro('Por favor, assine no campo de assinatura.'); return; }

                let responsavel = { eh_responsavel: false };
                if (chkResponsavel.checked) {
                    if (!respNome.value.trim() || !respCpf.value.trim()) {
                        mostrarErro('Por favor, preencha o Nome e o CPF do Responsável Legal.');
                        return;
                    }
                    if (respCpf.value.replace(/\D/g, '').length !== 11) {
                        mostrarErro('O CPF do Responsável Legal deve ter 11 dígitos.');
                        return;
                    }
                    responsavel.eh_responsavel = true;
                    responsavel.nome = respNome.value.trim();
                    responsavel.cpf  = respCpf.value.trim();
                }

                const assinaturaBase64 = signaturePad.toDataURL('image/png');

                // Loading state
                btnAssinar.disabled = true;
                btnAssinar.classList.add('loading');

                const geolocalizacao = await obterLocalizacao();
                const ipPublico = await obterIpPublico();

                try {
                    const resp = await fetch(SIGN_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-Token': CSRF_TOKEN
                        },
                        body: JSON.stringify({ 
                            assinatura_base64: assinaturaBase64, 
                            selfie_base64: selfieBase64,
                            geolocalizacao: geolocalizacao,
                            ip_publico: ipPublico,
                            responsavel: responsavel
                        }),
                    });

                    const data = await resp.json();

                    if (data.success) {
                        formCard.classList.add('d-none');
                        telaSucesso.classList.remove('d-none');
                        telaSucesso.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        mostrarErro(data.message || 'Erro ao processar a assinatura. Tente novamente.');
                        btnAssinar.disabled = false;
                        btnAssinar.classList.remove('loading');
                    }
                } catch (err) {
                    mostrarErro('Falha de conexão. Verifique sua internet e tente novamente.');
                    btnAssinar.disabled = false;
                    btnAssinar.classList.remove('loading');
                }
            });
        })();
    </script>
<?php endif; ?>