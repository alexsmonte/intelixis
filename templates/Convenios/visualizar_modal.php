<?php
// Apenas HTML puro, sem uso de HtmlHelper que depende do layout
?>
<div>
    <p><strong>Nome:</strong> <?= h($convenio->nome) ?></p>
    <p><strong>Registro ANS:</strong> <?= h($convenio->registro_ans) ?></p>
    <p><strong>CNPJ:</strong> <?= h($convenio->cnpj) ?></p>
    <p><strong>Telefone:</strong> <?= h($convenio->telefone) ?></p>
    <p><strong>Email:</strong> <?= h($convenio->email) ?></p>
    <p><strong>Site:</strong> <?= h($convenio->site) ?></p>

    <p><strong>Status:</strong> <?= $convenio->ativar == 1 ? '<span class="badge bg-success-subtle text-success">Ativo</span>' : '<span class="badge bg-warning-subtle text-warning">Não</span>'; ?></p>
</div>
