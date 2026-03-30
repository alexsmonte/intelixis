<?php

use Cake\I18n\Number;

?>

<div class="text-center mb-4 mt-3">
    <h2 class="fs-16 mb-3 fw-bolder">
        Preços justo para todos.
    </h2>
    <p class="fs-5 mb-0">
        Com a Intelixis sua empresa nunca mais perderá uma oportunidade de negócio por falta de resposta.
    </p>
</div>

<div class="row">
    <?php foreach ($planos as $plano) {

        $descricao  =   explode(";", $plano->descricao);

        ?>
    <div class="col-xl-4 col-sm-12">
        <div class="card plan-cards border rounded-4 p-4 d-flex flex-column gap-7">
            <div class="">
                <h3 class="fs-6 fw-semibold mb-3">
                    <?= $plano->nome; ?>
                </h3>
                <p class="fs-3 mb-0">
                    <?= $plano->resumo; ?>
                </p>
            </div>
            <h3 class="fs-3 fw-normal mb-0 text-muted">
                <span class="fs-10 fw-bolder text-primary">R$ <?= Number::precision($plano->valor,0); ?></span>/
                por mẽs
            </h3>
            <h4></h4>
            <div class="d-flex flex-column gap-3 mb-2" style="min-height: 380px;">
                <?php foreach ($descricao as $texto){ ?>
                <div class="d-flex gap-2">
                    <?= $this->Html->image('frontend-pages/icon-circle-check.svg') ?>
                    <p class="fs-3 fw-bold text-dark mb-0"><?= $texto; ?></p>
                </div>
                <?php } ?>
            </div>
            <div class="d-flex flex-column gap-3 mb-2">
                A maioria das mensagens de whatsapp é gratuita, e a Intelixis ajuda você a reduzir custos quando houver tarifação da Meta.
            </div>
            <?= $this->Form->create(null, ['url' => ['controller'=>'Assinaturas','action' => 'novo','id'=>'frmPlano'.$plano->id]]);
            echo $this->Form->control('plano_id',['type' => 'hidden', 'value'=>$plano->id]);
            ?>
            <button type="submit" class="btn btn-dark">Comprar agora</button>
            <?= $this->Form->end(); ?>
        </div>
    </div>
    <?php } ?>
</div>

<div class="mt-10">
    <p class="fs-4 text-muted text-center mb-5">Pagamento 100% seguro</p>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between gap-4 flex-wrap">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Visa">
                    <?= $this->Html->image('frontend-pages/visa.png', ['alt' => 'Visa']) ?>
                </a>
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Mastercard">
                    <?= $this->Html->image('frontend-pages/mastercard.png', ['alt' => 'Mastercard']) ?>
                </a>
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Americanexpress">
                    <?= $this->Html->image('frontend-pages/americanexpress.png', ['alt' => 'American Express']) ?>
                </a>
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Maestro">
                    <?= $this->Html->image('frontend-pages/maestro.png', ['alt' => 'maestro']) ?>
                </a>
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="JCB">
                    <?= $this->Html->image('frontend-pages/jcb.png', ['alt' => 'JCB']) ?>
                </a>
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Dinersclub">
                    <?= $this->Html->image('frontend-pages/dinersclub.png', ['alt' => 'Diners Club']) ?>
                </a>
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Discover">
                    <?= $this->Html->image('frontend-pages/discover.png', ['alt' => 'Discover']) ?>
                </a>
            </div>
        </div>
    </div>
</div>