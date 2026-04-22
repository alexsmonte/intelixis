<?php 
$footer =   "Endereço: ".$estabelecimento->logradouro;

    if(!empty($estabelecimento->numero))
        $footer .= ", ".$estabelecimento->numero;

$footer .= $estabelecimento->complemento.'<br>';

$footer .=  $estabelecimento->bairro.' - '. $estabelecimento->cidade.' - '. $estabelecimento->uf.'<br>';
$footer .= 'Telefone: '.$estabelecimento->telefone;

    if(!empty($estabelecimento->telefone_opcional))
        $footer .= ' / '.$estabelecimento->telefone_opcional;

$footer .= ' Celular: '.$estabelecimento->celular;

?>

<div style="font-size:8pt;text-align:center">
    <?= $footer; ?>
</div>
