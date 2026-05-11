<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .pagina {
        width: 100%;
    }

    .topo {
        border-bottom: 2px solid #000;
    }

    .clinica {
        text-align: center;
    }

    .clinica h1 {
        margin: 0;
        font-size: 20px;
    }

    .clinica p {
        margin: 2px 0;
        font-size: 11px;
    }

    .titulo {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin: 15px 0;
        text-transform: uppercase;
    }

    .box {
        border: 1px solid #000;
        padding: 10px;
        margin-bottom: 10px;
    }

    .linha {
        margin-bottom: 8px;
    }

    .label {
        font-weight: bold;
    }

    .medicamentos {
        min-height: 220px;
        line-height: 1.8;
    }

    .rodape {
        margin-top: 40px;
    }

    .assinatura {
        width: 300px;
        text-align: center;
        margin: 0 auto;
        margin-top: 60px;
    }

    .assinatura .linha-ass {
        border-top: 1px solid #000;
        margin-bottom: 5px;
    }

    .duas-vias {
        border-top: 2px dashed #000;
        margin-top: 25px;
        padding-top: 25px;
    }

    .observacao {
        font-size: 10px;
        margin-top: 15px;
    }

    .uso-restrito {
        color: #dc3545;
        font-weight: bold;
        text-align: center;
        margin: 10px 0;
        text-transform: uppercase;
    }
</style>

<div class="pagina">

    <?php 
    // Extrai os dados do documento
    $estabelecimento = $documento->estabelecimento;
    $paciente = $documento->procedimento->consulta->cliente;
    $profissional = $documento->profissional;
    $texto = $documento->texto;
    $usoRestrito = $documento->uso_restrito;
    ?>

    <!-- VIA 1 -->
    <div class="receita">
       
        <div class="titulo">
            Receita <?= $usoRestrito ? 'de Controle Especial' : 'Médica' ?>
        </div>

        <?php if ($usoRestrito): ?>
            <div class="uso-restrito">
                 MEDICAMENTO DE USO RESTRITO 
            </div>
        <?php endif; ?>

        <div class="box">
            <div class="linha">
                <span class="label">Paciente:</span> <?= $paciente->nome ?>
            </div>
            <div class="linha">
                <span class="label">CPF:</span> <?= $paciente->documento ?>
                &nbsp;&nbsp;&nbsp;
                <span class="label">Data Nasc.:</span> <?= $paciente->data_nascimento ? date('d/m/Y', strtotime($paciente->data_nascimento)) : 'Não informada' ?>
            </div>
            <div class="linha">
                <span class="label">Endereço:</span> <?= $paciente->endereco ?: $paciente->logradouro ?>, <?= $paciente->bairro ?> - <?= $paciente->cidade ?>/<?= $paciente->uf ?>
            </div>
        </div>

        <div class="box medicamentos">
            <div class="linha">
                <strong>Prescrição:</strong>
            </div>
            <div class="linha">
                <?= $texto ?>
            </div>
        </div>

        <div class="linha">
            <span class="label">Data:</span> <?= date('d/m/Y') ?>
        </div>

        <div class="assinatura">
            <div class="linha-ass"></div>
            <strong><?= $profissional->nome ?></strong><br>
            <?= $profissional->conselho ?: 'CRM' ?>: <?= $profissional->documento ?? 'Informar' ?>
        </div>

        <div class="observacao">
            1ª VIA - FARMÁCIA
        </div>

    </div>
<page_break>
    <!-- VIA 2 -->
    <div>
        <div class="titulo">
            Receita <?= $usoRestrito ? 'de Controle Especial' : 'Médica' ?>
        </div>

        <?php if ($usoRestrito): ?>
            <div class="uso-restrito">
                 MEDICAMENTO DE USO RESTRITO 
            </div>
        <?php endif; ?>

        <div class="box">
            <div class="linha">
                <span class="label">Paciente:</span> <?= $paciente->nome ?>
            </div>
            <div class="linha">
                <span class="label">CPF:</span> <?= $paciente->documento ?>
                &nbsp;&nbsp;&nbsp;
                <span class="label">Data Nasc.:</span> <?= $paciente->data_nascimento ? date('d/m/Y', strtotime($paciente->data_nascimento)) : 'Não informada' ?>
            </div>
            <div class="linha">
                <span class="label">Endereço:</span> <?= $paciente->endereco ?: $paciente->logradouro ?>, <?= $paciente->bairro ?> - <?= $paciente->cidade ?>/<?= $paciente->uf ?>
            </div>
        </div>

        <div class="box medicamentos">
            <?= $texto ?>
        </div>

        <div class="linha">
            <span class="label">Data:</span> <?= date('d/m/Y') ?>
        </div>

        <div class="assinatura">
            <div class="linha-ass"></div>
            <strong><?= $profissional->nome ?></strong><br>
            <?= $profissional->conselho ?: 'CRM' ?>: <?= $profissional->documento ?? 'Informar' ?>
        </div>
        <div class="observacao">
            2ª VIA - PACIENTE
        </div>
    </div>
</div>
