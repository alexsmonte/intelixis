<?php
// Extrai os dados do documento
$estabelecimento = $documento->estabelecimento;
$paciente = $documento->procedimento->consulta->cliente;
$profissional = $documento->profissional;
$texto = $documento->texto;
$usoRestrito = $documento->uso_restrito;

$footer = "Endereço: " . $estabelecimento->logradouro;

if (!empty($estabelecimento->numero))
    $footer .= ", " . $estabelecimento->numero;

if (!empty($estabelecimento->complemento))
    $footer .= " - " . $estabelecimento->complemento;

$footer .= '<br>';
$footer .= $estabelecimento->bairro . ' - ' . $estabelecimento->cidade . ' - ' . $estabelecimento->uf . '<br>';
$footer .= 'Telefone: ' . $estabelecimento->telefone;

if (!empty($estabelecimento->telefone_opcional))
    $footer .= ' / ' . $estabelecimento->telefone_opcional;

$footer .= ' - Celular: ' . $estabelecimento->celular;
?>

<div style="font-size:8pt;text-align:center">
    <?= $footer; ?>
</div>
