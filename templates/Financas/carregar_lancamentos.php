<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Cliente</th>
            <th>Vencimento</th>
            <th>Valor</th>
            <th>Pago em</th>
            <th>Valor Pago</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($lancamentos->count()): ?>
            <?php foreach ($lancamentos as $lan): ?>
                <tr>
                    <td><?= h($lan->descricao) ?></td>
                    <td><?= h($lan->cliente->nome ?? '') ?></td>
                    <td><?= $lan->data_vencimento?->format('d/m/Y') ?></td>
                    <td>R$ <?= number_format($lan->valor, 2, ',', '.') ?></td>
                    <td><?= $lan->data_baixa?->format('d/m/Y') ?></td>
                    <td><?= $lan->valor_recebido ? 'R$ ' . number_format($lan->valor_recebido, 2, ',', '.') : '' ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Nenhum lançamento encontrado</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="mt-3">
    <?= $this->Paginator->numbers() ?>
</div>
