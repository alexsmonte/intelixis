<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Caixa $caixa
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Caixa'), ['action' => 'edit', $caixa->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Caixa'), ['action' => 'delete', $caixa->id], ['confirm' => __('Are you sure you want to delete # {0}?', $caixa->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Caixas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Caixa'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="caixas view content">
            <h3><?= h($caixa->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $caixa->hasValue('estabelecimento') ? $this->Html->link($caixa->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $caixa->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Usuario') ?></th>
                    <td><?= $caixa->hasValue('usuario') ? $this->Html->link($caixa->usuario->nome, ['controller' => 'Usuarios', 'action' => 'view', $caixa->usuario->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Situacao') ?></th>
                    <td><?= h($caixa->situacao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($caixa->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Saldo Inicial') ?></th>
                    <td><?= $this->Number->format($caixa->saldo_inicial) ?></td>
                </tr>
                <tr>
                    <th><?= __('Usuario Conferiu') ?></th>
                    <td><?= $caixa->usuario_conferiu === null ? '' : $this->Number->format($caixa->usuario_conferiu) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Abertura') ?></th>
                    <td><?= h($caixa->data_abertura) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Fechamento') ?></th>
                    <td><?= h($caixa->data_fechamento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($caixa->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($caixa->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Movimentacoes') ?></h4>
                <?php if (!empty($caixa->movimentacoes)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Caixa Id') ?></th>
                            <th><?= __('Forma Pagamento Id') ?></th>
                            <th><?= __('Valor Apurado') ?></th>
                            <th><?= __('Situacao') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($caixa->movimentacoes as $movimentacao) : ?>
                        <tr>
                            <td><?= h($movimentacao->id) ?></td>
                            <td><?= h($movimentacao->caixa_id) ?></td>
                            <td><?= h($movimentacao->forma_pagamento_id) ?></td>
                            <td><?= h($movimentacao->valor_apurado) ?></td>
                            <td><?= h($movimentacao->situacao) ?></td>
                            <td><?= h($movimentacao->created) ?></td>
                            <td><?= h($movimentacao->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Movimentacoes', 'action' => 'view', $movimentacao->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Movimentacoes', 'action' => 'edit', $movimentacao->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Movimentacoes', 'action' => 'delete', $movimentacao->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $movimentacao->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>