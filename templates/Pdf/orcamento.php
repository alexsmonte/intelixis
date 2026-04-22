<style>
    body {
        font-family: sans-serif;
        font-size: 10pt;
    }

    .titulo {
        font-size: 16pt;
        font-weight: bold;
        margin-bottom: 40px;
        
        text-align:center;
        width: 100%;
    }

    .box {
        border: 1px solid #000;
        padding: 8px;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #000;
        padding: 6px;
    }

    th {
        background: #eee;
    }

    .text-right {
        text-align: right;
    }
</style>

<div class="titulo">ORÇAMENTO</div>

<!-- DADOS CLIENTE -->
<div class="box">
    <strong>Cliente:</strong> <?= h($orcamento->cliente->nome) ?><br>
    <strong>Validade:</strong> <?= $orcamento->validade ?><br>    
    <strong>Emitido em:</strong> <?= date('d/m/Y') ?><br>
</div>

<!-- TABELA -->
<table>
    <thead>
        <tr>
            <th>Serviço</th>
            <th>Profissional</th>
            <th>Qtd</th>
            <th>Valor</th>
            <th>Desconto</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $totalBruto = 0;
        $totalDesconto = 0;
        ?>

        <?php foreach ($orcamento->orcamentos_servicos as $item): 

            $totalItem = ($item->quantidade * $item->valor_unitario) - $item->desconto;

            $totalBruto += ($item->quantidade * $item->valor_unitario);
            $totalDesconto += $item->desconto;
        ?>
        <tr>
            <td><?= h($item->servico->nome ?? '-') ?></td>
            <td><?= h($item->profissional->nome ?? '-') ?></td>
            <td><?= $item->quantidade ?></td>
            <td class="text-right"><?= number_format($item->valor_unitario, 2, ',', '.') ?></td>
            <td class="text-right"><?= number_format($item->desconto, 2, ',', '.') ?></td>
            <td class="text-right"><?= number_format($totalItem, 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="5" class="text-right"><strong>Total Bruto</strong></td>
            <td class="text-right"><?= number_format($totalBruto, 2, ',', '.') ?></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right"><strong>Desconto</strong></td>
            <td class="text-right"><?= number_format($totalDesconto, 2, ',', '.') ?></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right"><strong>Total Geral</strong></td>
            <td class="text-right"><?= number_format($totalBruto - $totalDesconto, 2, ',', '.') ?></td>
        </tr>
    </tfoot>
</table>

<!-- OBSERVAÇÕES -->
<?php if (!empty($orcamento->observacoes)): ?>
<div class="box" style="margin-top:10px;">
    <strong>Observações:</strong><br>
    <?= nl2br(h($orcamento->observacoes)) ?>
</div>
<?php endif; ?>
