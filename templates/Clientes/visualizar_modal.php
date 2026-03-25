<?php
// Apenas HTML puro, sem uso de HtmlHelper que depende do layout
?>
<div>
    <p>Nome: <?= h($cliente->nome) ?></p>
    <p><strong>CPF:</strong> <?= h($cliente->documento) ?></p>
    <p><strong>Email:</strong> <?= h($cliente->email) ?></p>
    <p><strong>Telefone:</strong> <?= h($cliente->telefone) ?></p>
    <p><strong>Celular:</strong> <?= h($cliente->celular) ?></p>
    <p><strong>Estabelecimento:</strong> <?= h($cliente->estabelecimento->razao_social ?? '') ?></p>
</div>
