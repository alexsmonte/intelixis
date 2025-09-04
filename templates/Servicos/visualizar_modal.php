<?php
// Apenas HTML puro, sem uso de HtmlHelper que depende do layout
?>
<div>
    <p>Nome: <?= h($servico->nome) ?></p>
    <p><strong>Descricao:</strong> <?= h($servico->descricao) ?></p>
    <p><strong>Duração:</strong> <?= h($servico->duracao) ?></p>
    <p><strong>Status:</strong> <?= $servico->ativar == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?></p>
    <p><strong>Estabelecimento:</strong> <?= h($servico->estabelecimento->razao_social ?? '') ?></p>
    
</div>
