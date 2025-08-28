<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plano $plano
 */

use Cake\I18n\Number;
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h5 class="mb-1"><?= h($plano->nome) ?></h5>
            </div>
            <div class="mt-5">
                <div class="pb-1 mb-2 border-bottom">
                    <h6>Dados do plano</h6>
                </div>

                <ul>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Nome:
                            <span class="fw-light ms-1"><?= h($plano->nome) ?></span>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Valor:
                            <span class="fw-light ms-1"><?= $plano->valor === null ? '' : 'R$ '.Number::precision($plano->valor,2) ?></span>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Número de whatsapp:
                            <span class="fw-light ms-1"><?= $plano->numero_whatsapp === null ? '' : $this->Number->format($plano->numero_whatsapp) ?></span>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Número de assinantes:
                            <span class="fw-light ms-1"><?= count($plano->assinaturas); ?></span>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Status:
                            <?= $plano->ativar == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?>
                        </p>
                    </li>
                    <li class="py-2">
                        <p class="fw-normal text-dark mb-0">
                            Criado em:
                            <span class="fw-light ms-1"><?= $plano->created; ?></span>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>