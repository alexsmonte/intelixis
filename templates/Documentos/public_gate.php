<?php
/**
 * View: Documentos/public_gate.php
 * Layout: assinar (mobile-first, sem autenticação)
 * Gate de segurança — confirmação de data de nascimento.
 *
 * @var string $pacienteNome
 * @var string $docTitulo
 * @var string $estabelecimento
 * @var string $codigo
 */
$this->assign('title', 'Verificar Identidade');

$verifyUrl = $this->Url->build('/assinar/' . urlencode($codigo) . '/verificar');
$signUrl   = $this->Url->build(['controller' => 'Documentos', 'action' => 'publicSign', $codigo]);
$csrfToken = $this->request->getAttribute('csrfToken') ?? '';
?>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
    .btn-verificar .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .7s linear infinite;
        display: none;
    }
    .btn-verificar.loading .spinner  { display: inline-block; }
    .btn-verificar.loading .btn-text { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card w-100 position-relative overflow-hidden mt-2 shadow-sm border-0">

                <!-- Cabeçalho — mesmo padrão do public_sign -->
                <div class="card-header bg-primary text-white p-4 text-center border-0">
                    <div class="small opacity-75 mb-1 text-uppercase fw-bold"><?= h($estabelecimento) ?></div>
                    <h4 class="mb-1 text-white"><?= h($docTitulo) ?></h4>
                    <div class="small opacity-75 mt-1">Paciente: <?= h($pacienteNome) ?></div>
                </div>

                <div class="card-body p-4">

                    <!-- Ícone de segurança -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:72px;height:72px;">
                            <iconify-icon icon="solar:shield-keyhole-bold" class="text-primary" style="font-size:2.2rem;"></iconify-icon>
                        </div>
                        <h6 class="fw-bold mb-1">Verificação de Identidade</h6>
                        <p class="text-muted small mb-0">
                            Para acessar o documento de <strong><?= h($pacienteNome) ?></strong>,
                            confirme a <strong>data de nascimento do paciente</strong>.
                        </p>
                    </div>

                    <!-- Alerta de erro (oculto por padrão) -->
                    <div class="alert alert-danger d-none align-items-center gap-2 mb-3" id="alertErro">
                        <iconify-icon icon="solar:danger-triangle-bold" class="fs-5 flex-shrink-0"></iconify-icon>
                        <span id="alertErroMsg"></span>
                    </div>

                    <!-- Campo de data -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase" for="inputDataNascimento">
                            <iconify-icon icon="solar:calendar-bold" class="align-middle me-1"></iconify-icon>
                            Data de Nascimento
                        </label>
                        <input type="date"
                               id="inputDataNascimento"
                               class="form-control form-control-lg text-center fw-semibold"
                               autocomplete="bday" />
                    </div>

                    <!-- Aviso de tentativas -->
                    <div class="text-muted small text-center mb-3 d-none" id="tentativasBadge"></div>

                    <!-- Botão de confirmar -->
                    <div class="d-grid mb-3">
                        <button class="btn btn-primary rounded-pill fw-bold py-2 d-flex align-items-center justify-content-center gap-2 btn-verificar"
                                id="btnVerificar" type="button" disabled>
                            <div class="spinner border border-2 border-white border-top-transparent rounded-circle"
                                 style="width:20px;height:20px;display:none;" id="spinnerBtn"></div>
                            <span class="btn-text">
                                <iconify-icon icon="solar:lock-keyhole-bold" class="align-middle me-1"></iconify-icon>
                                Confirmar e Acessar
                            </span>
                        </button>
                    </div>

                    <!-- Nota de segurança -->
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 small text-primary d-flex gap-2 align-items-start">
                        <iconify-icon icon="solar:info-circle-bold" class="flex-shrink-0 fs-5 mt-1"></iconify-icon>
                        <span>Esta verificação protege o documento de acessos não autorizados. Responsáveis legais podem assinar normalmente.</span>
                    </div>

                </div>
            </div>

            <!-- Rodapé -->
            <div class="text-center mt-3 small text-muted">
                <iconify-icon icon="solar:lock-bold" class="align-middle me-1"></iconify-icon>
                Conexão segura &middot; Intelixis
            </div>

        </div>
    </div>
</div>

<script>
const VERIFY_URL = <?= json_encode($verifyUrl) ?>;
const SIGN_URL   = <?= json_encode($signUrl) ?>;
const CSRF_TOKEN = <?= json_encode($csrfToken) ?>;

const inputData    = document.getElementById('inputDataNascimento');
const btnVerificar = document.getElementById('btnVerificar');
const spinnerBtn   = document.getElementById('spinnerBtn');
const alertErro    = document.getElementById('alertErro');
const alertErroMsg = document.getElementById('alertErroMsg');
const tentBadge    = document.getElementById('tentativasBadge');

let tentativas = 0;
const MAX_TENTATIVAS = 5;

// Habilita botão apenas quando data estiver preenchida
inputData.addEventListener('input', () => {
    btnVerificar.disabled = !inputData.value;
});

// Confirmar com Enter
inputData.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !btnVerificar.disabled) btnVerificar.click();
});

btnVerificar.addEventListener('click', async () => {
    if (!inputData.value || tentativas >= MAX_TENTATIVAS) return;

    // UI loading
    btnVerificar.disabled = true;
    btnVerificar.classList.add('loading');
    spinnerBtn.style.display = 'inline-block';
    alertErro.classList.add('d-none');

    try {
        const resp = await fetch(VERIFY_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': CSRF_TOKEN,
            },
            body: JSON.stringify({ data_nascimento: inputData.value }),
        });

        const data = await resp.json();

        if (data.success) {
            btnVerificar.classList.remove('loading');
            btnVerificar.classList.add('btn-success');
            btnVerificar.classList.remove('btn-primary');
            spinnerBtn.style.display = 'none';
            btnVerificar.querySelector('.btn-text').innerHTML =
                '<iconify-icon icon="solar:check-circle-bold" class="align-middle me-1"></iconify-icon> Acesso liberado! Redirecionando...';

            setTimeout(() => { window.location.href = SIGN_URL; }, 700);
        } else {
            tentativas++;
            btnVerificar.classList.remove('loading');
            spinnerBtn.style.display = 'none';

            const restantes = MAX_TENTATIVAS - tentativas;
            mostrarErro(data.message || 'Data de nascimento incorreta. Tente novamente.');

            if (restantes > 0 && restantes <= 3) {
                tentBadge.textContent = '⚠️ ' + restantes + ' tentativa(s) restante(s)';
                tentBadge.classList.remove('d-none');
            } else if (restantes <= 0) {
                tentBadge.textContent = '🔒 Acesso bloqueado. Aguarde e tente novamente.';
                tentBadge.classList.remove('d-none');
                btnVerificar.disabled = true;
            } else {
                btnVerificar.disabled = false;
            }
        }
    } catch (err) {
        btnVerificar.classList.remove('loading');
        spinnerBtn.style.display = 'none';
        btnVerificar.disabled = false;
        mostrarErro('Erro de comunicação. Verifique sua conexão e tente novamente.');
    }
});

function mostrarErro(msg) {
    alertErroMsg.textContent = msg;
    alertErro.classList.remove('d-none');
    alertErro.classList.add('d-flex');
    if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
}
</script>
