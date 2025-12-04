<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pagamento $pagamento
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Pagamento'), ['action' => 'edit', $pagamento->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Pagamento'), ['action' => 'delete', $pagamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pagamento->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Pagamentos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Pagamento'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="pagamentos view content">
            <h3><?= h($pagamento->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Financa') ?></th>
                    <td><?= $pagamento->hasValue('financa') ? $this->Html->link($pagamento->financa->descricao, ['controller' => 'Financas', 'action' => 'view', $pagamento->financa->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Forma Pagamento') ?></th>
                    <td><?= $pagamento->hasValue('forma_pagamento') ? $this->Html->link($pagamento->forma_pagamento->nome, ['controller' => 'FormaPagamentos', 'action' => 'view', $pagamento->forma_pagamento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($pagamento->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor') ?></th>
                    <td><?= $this->Number->format($pagamento->valor) ?></td>
                </tr>
                <tr>
                    <th><?= __('Taxa') ?></th>
                    <td><?= $pagamento->taxa === null ? '' : $this->Number->format($pagamento->taxa) ?></td>
                </tr>
                <tr>
                    <th><?= __('Usuario Recebeu') ?></th>
                    <td><?= $pagamento->usuario_recebeu === null ? '' : $this->Number->format($pagamento->usuario_recebeu) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Antecipacao') ?></th>
                    <td><?= h($pagamento->data_antecipacao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($pagamento->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($pagamento->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Antecipar') ?></th>
                    <td><?= $pagamento->antecipar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>