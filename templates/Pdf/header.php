<?php
// Extrai os dados do documento
$estabelecimento = $documento->estabelecimento;
$paciente = $documento->procedimento->consulta->cliente;
$profissional = $documento->profissional;
$texto = $documento->texto;
$usoRestrito = $documento->uso_restrito;

?>
<table style="border-bottom: 1px solid #000000; text-align: left; width: 100%;">
    <tr>
        <td style="background-color: #ffffff; border: 0; text-align: left;">
            <?php if (!empty($estabelecimento->logo)): ?>
                <img src="<?= WWW_ROOT . 'img/' . $estabelecimento->logo ?>" style="height:100px;">
            <?php endif; ?>
        </td>

        <td style="border: 0; text-align: right;">
            <h2 style="text-align: center;"><?= $estabelecimento->nome_fantasia; ?></h1>
<p><?= $estabelecimento->logradouro ?>, <?= $estabelecimento->numero ?> <?= $estabelecimento->complemento ?></p>
        <p>
            Tel: <?= $estabelecimento->telefone ?>
            <?php if (!empty($estabelecimento->celular)): ?>
                | Cel: <?= $estabelecimento->celular ?>
            <?php endif; ?>
            <?php if (!empty($estabelecimento->cpf_cnpj)): ?>
                | CNPJ: <?= $estabelecimento->cpf_cnpj ?>
            <?php endif; ?>
        </p>
        </td>
    </tr>
</table>
