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
?>

<style>
    /* ========== Design System ========== */
    :root {
        --blue: #2563eb;
        --blue-dark: #1d4ed8;
        --green: #16a34a;
        --red: #dc2626;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-500: #64748b;
        --gray-700: #334155;
        --gray-900: #0f172a;
        --radius: 14px;
        --shadow: 0 4px 24px rgba(0, 0, 0, .1);
    }

    /* ========== Page ========== */
    .assin-page {
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 0 48px;
        background: linear-gradient(160deg, #eff6ff 0%, #f1f5f9 60%);
    }

    /* ========== Header ========== */
    .assin-header {
        width: 100%;
        background: var(--blue);
        color: #fff;
        padding: 18px 20px 14px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(37, 99, 235, .4);
    }

    .assin-header .logo-clinica {
        font-size: .75rem;
        opacity: .85;
        margin-bottom: 2px;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .assin-header .doc-titulo {
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0;
    }

    .assin-header .paciente-nome {
        font-size: .88rem;
        opacity: .9;
        margin-top: 2px;
    }

    /* ========== Card ========== */
    .assin-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 22px 18px;
        width: 100%;
        max-width: 520px;
        margin-top: 20px;
    }

    @media (max-width: 539px) {
        .assin-card {
            margin: 16px 12px 0;
            width: calc(100% - 24px);
        }
    }

    /* ========== Section label ========== */
    .section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .8rem;
        font-weight: 700;
        color: var(--blue);
        text-transform: uppercase;
        letter-spacing: .07em;
        margin-bottom: 10px;
    }

    .section-label .num {
        width: 22px;
        height: 22px;
        background: var(--blue);
        color: #fff;
        border-radius: 50%;
        font-size: .7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        flex-shrink: 0;
    }

    /* ========== Selfie ========== */
    .selfie-area {
        text-align: center;
    }

    .selfie-preview-wrap {
        position: relative;
        width: 180px;
        height: 180px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: var(--gray-100);
        border: 3px dashed var(--gray-200);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color .2s;
    }

    .selfie-preview-wrap.captured {
        border-color: var(--green);
        border-style: solid;
    }

    .selfie-preview-wrap video,
    .selfie-preview-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .selfie-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        color: var(--gray-500);
        font-size: .8rem;
    }

    .selfie-placeholder svg {
        opacity: .4;
    }

    .btn-selfie {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--blue);
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 12px 28px;
        font-size: .95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .1s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, .35);
    }

    .btn-selfie:active {
        transform: scale(.97);
    }

    .btn-selfie.captured {
        background: var(--green);
    }

    .btn-refazer {
        background: none;
        border: 1.5px solid var(--gray-200);
        color: var(--gray-500);
        border-radius: 50px;
        padding: 8px 18px;
        font-size: .8rem;
        cursor: pointer;
        margin-top: 6px;
        transition: border-color .2s;
    }

    .btn-refazer:hover {
        border-color: var(--gray-500);
    }

    /* ========== Signature Pad ========== */
    .sig-wrap {
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        background: var(--gray-50);
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
        color: var(--gray-200);
        font-size: .85rem;
        pointer-events: none;
        transition: opacity .3s;
    }

    .btn-limpar {
        background: none;
        border: none;
        color: var(--gray-500);
        font-size: .8rem;
        cursor: pointer;
        margin-top: 6px;
        text-decoration: underline;
    }

    /* ========== Divider ========== */
    .assin-divider {
        border: none;
        border-top: 1.5px solid var(--gray-100);
        margin: 20px 0;
    }

    /* ========== Submit button ========== */
    .btn-assinar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 16px;
        background: var(--blue);
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 1.05rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(37, 99, 235, .4);
        transition: background .2s, transform .1s, opacity .2s;
    }

    .btn-assinar:hover {
        background: var(--blue-dark);
    }

    .btn-assinar:active {
        transform: scale(.98);
    }

    .btn-assinar:disabled {
        opacity: .5;
        cursor: not-allowed;
        transform: none;
    }

    /* ========== Estado loading ========== */
    .btn-assinar .spinner {
        width: 20px;
        height: 20px;
        border: 2.5px solid rgba(255, 255, 255, .4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .7s linear infinite;
        display: none;
    }

    .btn-assinar.loading .spinner {
        display: block;
    }

    .btn-assinar.loading .btn-text {
        opacity: 0;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* ========== Tela de Sucesso ========== */
    .tela-sucesso {
        display: none;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px;
    }

    .tela-sucesso .check-circle {
        width: 90px;
        height: 90px;
        background: #dcfce7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        animation: pop .4s ease;
    }

    @keyframes pop {
        0% {
            transform: scale(0);
        }

        70% {
            transform: scale(1.15);
        }

        100% {
            transform: scale(1);
        }
    }

    .tela-sucesso h2 {
        color: var(--green);
        font-size: 1.35rem;
        margin: 0 0 8px;
    }

    .tela-sucesso p {
        color: var(--gray-500);
        font-size: .9rem;
        margin: 0;
        line-height: 1.6;
    }

    /* ========== Já assinado ========== */
    .badge-assinado {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #dcfce7;
        color: #15803d;
        border-radius: 50px;
        padding: 6px 14px;
        font-size: .82rem;
        font-weight: 600;
    }

    /* ========== Info rodapé ========== */
    .assin-footer-info {
        font-size: .72rem;
        color: var(--gray-500);
        text-align: center;
        margin-top: 16px;
        line-height: 1.6;
    }

    /* ========== Alert ========== */
    .assin-alert {
        border-radius: 8px;
        padding: 12px 14px;
        font-size: .84rem;
        margin-bottom: 14px;
        display: none;
    }

    .assin-alert.error {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }

    .assin-alert.success {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #86efac;
    }
</style>

<div class="assin-page">

    <?php if ($jaAssinado): ?>
        <!-- ====== DOCUMENTO JÁ ASSINADO ====== -->
        <div class="assin-header">
            <div class="logo-clinica"><?= $estabelecimento ?></div>
            <h1 class="doc-titulo"><?= $docTitulo ?></h1>
            <div class="paciente-nome"><?= $pacienteNome ?></div>
        </div>
        <div class="assin-card" style="text-align:center;padding:40px 24px;">
            <div class="check-circle"
                style="width:80px;height:80px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <div class="badge-assinado" style="margin-bottom:14px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                Documento assinado
            </div>
            <p style="color:#334155;font-size:.9rem;line-height:1.7;margin-top:10px;">
                Este documento já foi <strong>assinado eletronicamente</strong>.<br>
                A assinatura foi registrada em
                <?= $documento->assinatura_data ? $documento->assinatura_data->format('d/m/Y \à\s H:i') : 'data registrada' ?>.
            </p>
            <p style="color:#64748b;font-size:.78rem;margin-top:12px;">
                Hash SHA-256:<br>
                <code style="font-size:.72rem;word-break:break-all;"><?= h($documento->assinatura_hash) ?></code>
            </p>
        </div>

    <?php else: ?>
        <!-- ====== FORMULÁRIO DE ASSINATURA ====== -->
        <div class="assin-header">
            <div class="logo-clinica"><?= $estabelecimento ?></div>
            <h1 class="doc-titulo"><?= $docTitulo ?></h1>
            <div class="paciente-nome">Paciente: <?= $pacienteNome ?></div>
        </div>

        <!-- Card principal -->
        <div class="assin-card" id="formCard">
            <!-- Alert de erro/sucesso -->
            <div class="assin-alert error" id="alertErro"></div>

            <!-- ===== PASSO 1: SELFIE ===== -->
            <div class="section-label">
                <span class="num">1</span>
                Tire uma foto sua (selfie) para identificação
            </div>

            <!-- ===== SE RESPONSÁVEL LEGAL ===== -->
            <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--gray-700);">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" id="chkResponsavel" style="width:16px;height:16px;">
                    <b>Assinar como Responsável Legal pelo paciente</b>
                </label>
            </div>
            
            <div id="formResponsavel" style="display:none; background:var(--gray-50); border:1px solid var(--gray-200); border-radius:8px; padding:14px; margin-bottom:20px;">
                <div style="margin-bottom:12px;">
                    <label style="display:block; font-size:0.8rem; font-weight:bold; color:var(--gray-700); margin-bottom:4px;">Nome do Responsável Legal</label>
                    <input type="text" id="respNome" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:6px; font-size:0.9rem;" placeholder="Ex: Maria da Silva">
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:bold; color:var(--gray-700); margin-bottom:4px;">CPF do Responsável Legal</label>
                    <input type="text" id="respCpf" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:6px; font-size:0.9rem;" placeholder="Ex: 000.000.000-00" oninput="this.value = this.value.replace(/\D/g,'').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2')" maxlength="14">
                </div>
            </div>

            <div class="selfie-area">
                <!-- Câmera (getUserMedia) -->
                <div class="selfie-preview-wrap" id="selfieWrap">
                    <div class="selfie-placeholder" id="selfiePlaceholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Sua foto aqui
                    </div>
                    <video id="cameraVideo" autoplay playsinline style="display:none;"></video>
                    <img id="selfieImg" style="display:none;" alt="Selfie capturada" />
                </div>

                <div id="btnsSelfie">
                    <button class="btn-selfie" id="btnAbrirCamera" type="button">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                            <circle cx="12" cy="13" r="4" />
                        </svg>
                        Abrir Câmera
                    </button>
                </div>
                <div id="btnsCaptura" style="display:none; flex-direction:column; align-items:center; gap:16px;">
                    <button class="btn-selfie" id="btnCapturar" type="button">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <circle cx="12" cy="12" r="10" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        Capturar Foto
                    </button>
                    <button class="btn-refazer" id="btnRefazer" type="button">Refazer</button>
                </div>
            </div>

            <hr class="assin-divider" />

            <!-- ===== PASSO 2: ASSINATURA ===== -->
            <div class="section-label">
                <span class="num">2</span>
                Assine com o dedo no campo abaixo
            </div>
            <div class="sig-wrap">
                <canvas id="signatureCanvas"></canvas>
                <div class="sig-hint" id="sigHint">✍️ Assine aqui</div>
            </div>
            <button class="btn-limpar" id="btnLimpar" type="button">Limpar assinatura</button>

            <hr class="assin-divider" />

            <!-- ===== PASSO 3: CONFIRMAR ===== -->
            <button class="btn-assinar" id="btnAssinar" type="button" disabled>
                <div class="spinner"></div>
                <span class="btn-text">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    Assinar Documento
                </span>
            </button>

            <div class="assin-footer-info">
                Ao assinar, você confirma que leu e concorda com o conteúdo.<br>
                Esta assinatura tem validade jurídica (MP 2.200-2/2001).
            </div>
        </div>

        <!-- Tela de sucesso (hidden) -->
        <div class="assin-card tela-sucesso" id="telaSucesso">
            <div class="check-circle">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <h2>Documento assinado!</h2>
            <p>
                Sua assinatura foi registrada com sucesso.<br>
                Um protocolo de prova foi anexado ao documento.<br><br>
                <strong>Código de validação:</strong><br>
                <code style="font-size:.8rem;word-break:break-all;"><?= $codigoValidacao ?></code>
            </p>
        </div>
    <?php endif; ?>

    <div class="assin-footer-info" style="margin-top:24px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            style="vertical-align:middle;">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
        </svg>
        Intelixis · Assinatura Eletrônica Segura
    </div>
</div>

<?php if (!$jaAssinado): ?>
    <!-- SignaturePad via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
    <script>
        (function () {
            'use strict';

            // ===== Config =====
            const SIGN_URL = window.location.href; // Garante que a requisição siga a URL atual (com ou sem /painel)
            const CSRF_TOKEN = <?= json_encode($this->request->getAttribute('csrfToken') ?? '') ?>;

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

            chkResponsavel.addEventListener('change', function() {
                formResponsavel.style.display = this.checked ? 'block' : 'none';
                checkPronto(); // Reavalia estado do botão
            });

            // ===== Estado =====
            let selfieBase64 = null;
            let stream = null;
            let signaturePad = null;

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
                    btnsSelfie.style.display = 'none';
                    btnsCaptura.style.display = 'flex';
                } catch (err) {
                    mostrarErro('Não foi possível acessar a câmera. Verifique as permissões e tente novamente.');
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

                btnsCaptura.style.display = 'none';
                btnsSelfie.style.display = 'block';
                btnAbrirCamera.textContent = '📷 Refazer selfie';
                btnAbrirCamera.classList.add('captured');

                checkPronto();
            });

            btnRefazer.addEventListener('click', function () {
                selfieBase64 = null;
                selfieImg.style.display = 'none';
                cameraVideo.style.display = 'block';
                selfieWrap.classList.remove('captured');
                checkPronto();
            });

            // Clicar na imagem tb abre câmera novamente
            btnAbrirCamera.addEventListener('click', function () {
                if (selfieBase64) {
                    selfieBase64 = null;
                    selfieImg.style.display = 'none';
                    selfiePlaceholder.style.display = 'none';
                    selfieWrap.classList.remove('captured');
                    btnAbrirCamera.classList.remove('captured');
                }
            }, true);  // capture=true para rodar antes do handler de câmera

            // ===== Limpar assinatura =====
            btnLimpar.addEventListener('click', function () {
                signaturePad.clear();
                sigHint.style.opacity = '1';
                checkPronto();
            });

            // ===== Habilitar botão somente quando pronto =====
            function checkPronto() {
                const assinado = signaturePad && !signaturePad.isEmpty();
                btnAssinar.disabled = !(selfieBase64 && assinado);
            }

            // Verificar também quando a assinatura é desenhada
            canvas.addEventListener('pointerup', checkPronto);

            // ===== Erro =====
            function mostrarErro(msg) {
                alertErro.textContent = msg;
                alertErro.style.display = 'block';
                alertErro.className = 'assin-alert error';
                alertErro.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // ===== Enviar assinatura =====
            btnAssinar.addEventListener('click', async function () {
                alertErro.style.display = 'none';

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

                const obterLocalizacao = () => new Promise((resolve) => {
                    if (!navigator.geolocation) {
                        resolve('Não suportado pelo navegador');
                        return;
                    }
                    navigator.geolocation.getCurrentPosition(
                        (pos) => resolve(`Lat ${pos.coords.latitude.toFixed(6)}, Lng ${pos.coords.longitude.toFixed(6)}`),
                        (err) => resolve('Acesso negado/indisponível'),
                        { timeout: 10000, maximumAge: 0 }
                    );
                });

                const geolocalizacao = await obterLocalizacao();

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
                            responsavel: responsavel
                        }),
                    });

                    const data = await resp.json();

                    if (data.success) {
                        formCard.style.display = 'none';
                        telaSucesso.style.display = 'flex';
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

            // ===== Init =====
            document.addEventListener('DOMContentLoaded', function () {
                initSignaturePad();
            });
            if (document.readyState !== 'loading') initSignaturePad();

        })();
    </script>
<?php endif; ?>