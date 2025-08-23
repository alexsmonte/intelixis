<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 */
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <img src="img/profile/user-2.jpg" width="110" class="rounded-3 mb-3" alt="">
                <h5 class="mb-1"><?= h($usuario->nome) ?></h5>
                <span class="badge bg-primary-subtle text-primary fw-light rounded-pill">Admin</span>
            </div>
            <div class="mt-5">
                <div class="pb-1 mb-2 border-bottom">
                    <h6>Dados</h6>
                </div>

                <ul>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Nome:
                            <span class="fw-light ms-1"><?= h($usuario->nome) ?></span>
                        </p>
                    </li>

                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            E-mail:
                            <span class="fw-light ms-1"><?= h($usuario->email) ?></span>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Status:
                            <?= $usuario->ativar == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>