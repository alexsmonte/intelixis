<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Movimentacao $movimentacao
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Movimentacao'), ['action' => 'edit', $movimentacao->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Movimentacao'), ['action' => 'delete', $movimentacao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $movimentacao->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Movimentacoes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Movimentacao'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="movimentacoes view content">
            <h3><?= h($movimentacao->situacao) ?></h3>
            <table>
                <tr>
                    <th><?= __('Caixa') ?></th>
                    <td><?= $movimentacao->hasValue('caixa') ? $this->Html->link($movimentacao->caixa->id, ['controller' => 'Caixas', 'action' => 'view', $movimentacao->caixa->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Forma Pagamento') ?></th>
                    <td><?= $movimentacao->hasValue('forma_pagamento') ? $this->Html->link($movimentacao->forma_pagamento->nome, ['controller' => 'FormaPagamentos', 'action' => 'view', $movimentacao->forma_pagamento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Situacao') ?></th>
                    <td><?= h($movimentacao->situacao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($movimentacao->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor Apurado') ?></th>
                    <td><?= $movimentacao->valor_apurado === null ? '' : $this->Number->format($movimentacao->valor_apurado) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($movimentacao->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($movimentacao->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>