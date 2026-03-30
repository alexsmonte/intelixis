<?php if($documento->cabecalho == 1) { ?>
<table style="width:100%; margin-bottom:10px;">
    <tr>
        <td style="border:0px solid #000;padding:2px;">
            <strong>Paciente:</strong>
            <?= h($documento->procedimento->consulta->cliente->nome) ?>
        </td>
        <td style="border:0px solid #000;padding:2px;text-align: right;">
            <strong>Emissão:</strong>
            <?= $documento->created->format('d/m/Y H:i') ?>
        </td>
    </tr>
    <tr>
        <td style="border:0px solid #000;padding:2px;" colspan="2">
            <strong>CPF:</strong>
            <?= h($documento->procedimento->consulta->cliente->documento) ?>
        </td>
    </tr>
    <tr>
        <td style="border:0px solid #000;padding:2px;" colspan="2">
            <strong>Prontuario:</strong>
            <?= h(str_pad($documento->procedimento->consulta->cliente->id, 4, '0', STR_PAD_LEFT)) ?>
        </td>
    </tr>
</table>
<?php } ?>

<?php
    $texto = preg_replace('/\sdata-[a-zA-Z0-9\-]+="[^"]*"/', '', $documento->texto);

?>
<div style="border:0px solid #000; padding:5px; margin-top:20px; min-height:250px; font-size:10pt;">

    <?= nl2br($texto) ?>

    <div style="margin-top:80px; text-align:center;">
        <div style="border-top:1px solid #000;width:300px;margin:auto;padding-top:5px;">
            <strong><?= h($documento->profissional->nome) ?></strong><br>
        </div>
    </div>
</div>

