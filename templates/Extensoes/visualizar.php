<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Extensao $extensao
 */
use Cake\I18n\Number;
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h5 class="mb-1"><?= h($extensao->nome) ?></h5>
            </div>
            <div class="mt-5">
                <div class="pb-1 mb-2 border-bottom">
                    <h6>Dados da extensão</h6>
                </div>

                <ul>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Nome:
                            <span class="fw-light ms-1"><?= h($extensao->nome) ?></span>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Valor:
                            <span class="fw-light ms-1"><?= $extensao->valor === null ? '' : 'R$ '.Number::precision($extensao->valor,2) ?></span>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Número de assinantes:
                            <span class="fw-light ms-1"><?= count($extensao->estabelecimentos); ?></span>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Ativar:
                            <?= $extensao->ativar == 1 ? '<span class="badge bg-success-subtle text-success">Sim</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Criado em:
                            <span class="fw-light ms-1"><?= $extensao->created; ?></span>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>