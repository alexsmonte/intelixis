<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FormaPagamento $formaPagamento
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Forma Pagamento'), ['action' => 'edit', $formaPagamento->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Forma Pagamento'), ['action' => 'delete', $formaPagamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $formaPagamento->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Forma Pagamentos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Forma Pagamento'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="formaPagamentos view content">
            <h3><?= h($formaPagamento->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $formaPagamento->hasValue('estabelecimento') ? $this->Html->link($formaPagamento->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $formaPagamento->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($formaPagamento->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($formaPagamento->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tipo Taxa') ?></th>
                    <td><?= $formaPagamento->tipo_taxa === null ? '' : $this->Number->format($formaPagamento->tipo_taxa) ?></td>
                </tr>
                <tr>
                    <th><?= __('Taxas') ?></th>
                    <td><?= $formaPagamento->taxas === null ? '' : $this->Number->format($formaPagamento->taxas) ?></td>
                </tr>
                <tr>
                    <th><?= __('Taxa Antecipacao') ?></th>
                    <td><?= $formaPagamento->taxa_antecipacao === null ? '' : $this->Number->format($formaPagamento->taxa_antecipacao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dias') ?></th>
                    <td><?= $formaPagamento->dias === null ? '' : $this->Number->format($formaPagamento->dias) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($formaPagamento->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($formaPagamento->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $formaPagamento->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Movimentacoes') ?></h4>
                <?php if (!empty($formaPagamento->movimentacoes)) : ?>
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
                        <?php foreach ($formaPagamento->movimentacoes as $movimentacao) : ?>
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
            <div class="related">
                <h4><?= __('Related Pagamentos') ?></h4>
                <?php if (!empty($formaPagamento->pagamentos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Financa Id') ?></th>
                            <th><?= __('Forma Pagamento Id') ?></th>
                            <th><?= __('Valor') ?></th>
                            <th><?= __('Taxa') ?></th>
                            <th><?= __('Antecipar') ?></th>
                            <th><?= __('Data Antecipacao') ?></th>
                            <th><?= __('Usuario Recebeu') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($formaPagamento->pagamentos as $pagamento) : ?>
                        <tr>
                            <td><?= h($pagamento->id) ?></td>
                            <td><?= h($pagamento->financa_id) ?></td>
                            <td><?= h($pagamento->forma_pagamento_id) ?></td>
                            <td><?= h($pagamento->valor) ?></td>
                            <td><?= h($pagamento->taxa) ?></td>
                            <td><?= h($pagamento->antecipar) ?></td>
                            <td><?= h($pagamento->data_antecipacao) ?></td>
                            <td><?= h($pagamento->usuario_recebeu) ?></td>
                            <td><?= h($pagamento->created) ?></td>
                            <td><?= h($pagamento->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Pagamentos', 'action' => 'view', $pagamento->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Pagamentos', 'action' => 'edit', $pagamento->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Pagamentos', 'action' => 'delete', $pagamento->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $pagamento->id),
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