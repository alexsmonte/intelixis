<?php
// Apenas HTML puro, sem uso de HtmlHelper que depende do layout
?>
<div>
    <p>Nome: <?= h($profissional->nome) ?></p>
    <p><strong>Email:</strong> <?= h($profissional->email) ?></p>
    <p><strong>Celular:</strong> <?= h($profissional->celular) ?></p>
    <p><strong>Telefone:</strong> <?= h($profissional->telefone) ?></p>
    <p><strong>Estabelecimento:</strong> <?= h($profissional->estabelecimento->razao_social ?? '') ?></p>
</div>
