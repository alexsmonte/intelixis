<?php
use Cake\I18n\Number;
?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 10%;padding: 2px;2px;2px;2px;">Cliente: </td>
        <td><?= $consulta->cliente->nome?></td>
    </tr>
    <tr>
        <td style="width: 10%;padding: 2px;2px;2px;2px;">Emissão: </td>
        <td><?= date("d/m/Y H:i:s")?></td>
    </tr>
</table>

<br>
<br>
<?php
$subTotal   =   $subTotalDesconto   =   $totalGeral =   0;

foreach ($consulta->procedimentos as $procedimento){


    $valorTotal =   ($procedimento->valor_unitario * $procedimento->quantidade) - $procedimento->desconto;

    $subTotal +=    $procedimento->valor_unitario * $procedimento->quantidade;
    $subTotalDesconto   += $procedimento->desconto;

    $totalGeral += $valorTotal;
?>

<table style="width: 100%; margin-bottom: 10px;" cellpadding="0" cellspacing="0">
    <tr style="background-color: #cccccc;">
        <td style="width: 30%;text-align: center;">Procedimento</td>
        <td style="width: 8%;text-align: center;">Quant.</td>
        <td style="width: 10%; text-align: center;">Valor</td>
        <td style="width: 5%; text-align: center;">Desc.</td>
        <?php if($procedimento->parcelar){ ?>
            <td style="width: 15%;text-align: center;">Parcelas</td>
        <?php } ?>
        <td style="width: 10%; text-align: center;">Total</td>
    </tr>
    <tr>
        <td><?= $procedimento->servico->nome?></td>
        <td style="text-align: center;"><?= $procedimento->quantidade?></td>
        <td style="text-align: center;">R$ <?= Number::precision($procedimento->valor_unitario, 2);?></td>
        <td style="text-align: center;">R$ <?= Number::precision($procedimento->desconto,2); ?></td>
        <?php if($procedimento->parcelar){ ?>
            <td  style="text-align: center;"><?= $procedimento->parcelas."x de R$ ".Number::precision(((($procedimento->valor_unitario - $procedimento->desconto)* $procedimento->quantidade)/$procedimento->parcelas),2); ?></td>
        <?php } ?>
        <td style="text-align: center;">R$ <?= Number::precision($valorTotal,2); ?></td>
    </tr>
</table>
<?php } ?>

<br>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 50%;">&nbsp;</td>
        <td style="width: 30%;padding: 2px;2px;2px;2px; text-align: right; border: 1px solid #000000;  border-bottom: 0;">Subtotal </td>
        <td style="width: 20%;text-align: right;border: 1px solid #000000; border-left: 0; border-bottom: 0;">R$ <?= Number::precision($subTotal,2); ?></td>
    </tr>
    <tr>
        <td style="width: 50%;">&nbsp;</td>
        <td style="width: 30%;padding: 2px;2px;2px;2px; text-align: right; border: 1px solid #000000; border-bottom: 0;">Desconto </td>
        <td style="width: 20%;text-align: right;border: 1px solid #000000; border-left: 0; border-bottom: 0;padding-right: 2px">R$ <?= Number::precision($subTotalDesconto,2); ?></td>
    </tr>
    <tr>
        <td style="width: 50%;">&nbsp;</td>
        <td style="width: 30%;padding: 2px;2px;2px;2px; text-align: right; border: 1px solid #000000;">Total a pagar</td>
        <td style="width: 20%;text-align: right;border: 1px solid #000000; border-left: 0; padding-right: 2px">R$<?= Number::precision($totalGeral,2); ?></td>
    </tr>
</table>
<br>
<br>
<br>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
    <tr>
        <td>&nbsp;</td>
        <td style="width: 20%;">&nbsp;</td>
        <td style="border-top: 1px solid #000000; text-align: center;">
            <?= $consulta->cliente->nome?>
        </td>
    </tr>
</table>