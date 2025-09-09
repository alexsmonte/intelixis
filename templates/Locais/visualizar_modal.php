<?php
// Apenas HTML puro, sem uso de HtmlHelper que depende do layout
?>
<div>
    <p>Nome: <?= h($local->nome) ?></p>
    <p><strong>Endereço:</strong> <?= h($local->endereco) ?></p>
    <p><strong>Status:</strong> <?= $servico->ativar == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?></p>
   
</div>
