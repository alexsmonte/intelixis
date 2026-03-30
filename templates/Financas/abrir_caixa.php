<?php
$this->assign('title', 'Abrir Caixa');
?>

<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>

<style>
    .input-pill {
        border-radius: 50px !important;
        border: 1.5px solid #dee2e6 !important;
        background: #fff !important;
        transition: border-color .2s;
    }
    .input-pill:hover, .input-pill:focus {
        border-color: #0d6efd !important;
        box-shadow: none !important;
    }
    .input-pill[readonly]:hover { border-color: #dee2e6 !important; }

    .input-group-pill {
        border-radius: 50px;
        border: 1.5px solid #dee2e6;
        background: #fff;
        display: flex;
        align-items: center;
        overflow: hidden;
        transition: border-color .2s;
    }
    .input-group-pill:hover,
    .input-group-pill:focus-within { border-color: #0d6efd; }

    .input-group-pill .pg-prefix {
        padding: 0 14px;
        color: #6c757d;
        font-size: .875rem;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .input-group-pill input {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
        flex: 1;
    }
    .input-group-pill button {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0 14px;
        color: #6c757d;
        cursor: pointer;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        transition: color .15s;
    }
    .input-group-pill button:hover { color: #0d6efd; }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
                <div class="breadcrumb-title pe-3">Caixa</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Abrir Caixa</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                        <div class="card-header border-0 py-4 px-4 bg-primary-subtle">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 bg-white border border-primary-subtle"
                                     style="width:52px; height:52px;">
                                    <span class="iconify text-primary" data-icon="solar:lock-bold-duotone" style="font-size:26px;"></span>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-semibold text-primary-emphasis">Abrir Caixa</h5>
                                    <small class="text-primary-emphasis opacity-75">Preencha os dados para iniciar o caixa</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <?= $this->Form->create(null, ['url' => ['action' => 'caixa']]) ?>

                            <div class="row g-3">

                                <!-- Usuário -->
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-muted mb-1">Usuário</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg input-pill"
                                        value="<?= h($this->request->getSession()->read('Auth.nome')) ?>"
                                        readonly
                                    >
                                </div>

                                <!-- Data de Abertura -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">Data de Abertura</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-lg input-pill"
                                        value="<?= date('d/m/Y H:i:s') ?>"
                                        readonly
                                    >
                                </div>

                                <!-- Saldo Inicial -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">
                                        Saldo Inicial <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group-pill">
                                        <span class="pg-prefix">R$</span>
                                        <?= $this->Form->text('saldo_inicial', [
                                            'class'       => 'form-control form-control-lg',
                                            'value' => '0,00',
                                            'required'    => true,
                                            'oninput'     => 'this.value = this.value.replace(/[^0-9,]/g, "")'
                                        ]) ?>
                                    </div>
                                </div>

                                <!-- Senha -->
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-muted mb-1">
                                        Senha do Usuário <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group-pill">
                                        <?= $this->Form->password('senha_usuario', [
                                            'id'          => 'senha_usuario',
                                            'class'       => 'form-control form-control-lg',
                                            'placeholder' => 'Senha',
                                            'required'    => true,
                                        ]) ?>
                                        <button type="button" onclick="toggleSenha()" tabindex="-1">
                                            <span class="iconify" id="icon-show" data-icon="solar:eye-bold-duotone" style="font-size:20px;"></span>
                                            <span class="iconify" id="icon-hide" data-icon="solar:eye-closed-bold-duotone" style="font-size:20px; display:none;"></span>
                                        </button>
                                    </div>
                                </div>

                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit"
                                    class="btn btn-primary btn-lg fw-semibold rounded-pill d-flex align-items-center justify-content-center gap-2">
                                    <span class="iconify" data-icon="solar:lock-unlocked-bold-duotone" style="font-size:20px;"></span>
                                    Abrir Caixa
                                </button>
                            </div>

                            <?= $this->Form->end() ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleSenha() {
    const input    = document.getElementById('senha_usuario');
    const iconShow = document.getElementById('icon-show');
    const iconHide = document.getElementById('icon-hide');
    if (input.type === 'password') {
        input.type             = 'text';
        iconShow.style.display = 'none';
        iconHide.style.display = 'block';
    } else {
        input.type             = 'password';
        iconShow.style.display = 'block';
        iconHide.style.display = 'none';
    }
}
</script>