<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Financa $financa
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Financa'), ['action' => 'edit', $financa->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Financa'), ['action' => 'delete', $financa->id], ['confirm' => __('Are you sure you want to delete # {0}?', $financa->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Financas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Financa'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="financas view content">
            <h3><?= h($financa->descricao) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $financa->hasValue('estabelecimento') ? $this->Html->link($financa->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $financa->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Ccustos') ?></th>
                    <td><?= $financa->hasValue('ccustos') ? $this->Html->link($financa->ccustos->name, ['controller' => 'Ccustos', 'action' => 'view', $financa->ccustos->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Profissional') ?></th>
                    <td><?= $financa->hasValue('profissional') ? $this->Html->link($financa->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $financa->profissional->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Cliente') ?></th>
                    <td><?= $financa->hasValue('cliente') ? $this->Html->link($financa->cliente->nome, ['controller' => 'Clientes', 'action' => 'view', $financa->cliente->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Procedimento') ?></th>
                    <td><?= $financa->hasValue('procedimento') ? $this->Html->link($financa->procedimento->id, ['controller' => 'Procedimentos', 'action' => 'view', $financa->procedimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Descricao') ?></th>
                    <td><?= h($financa->descricao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($financa->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tipo') ?></th>
                    <td><?= $financa->tipo === null ? '' : $this->Number->format($financa->tipo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor') ?></th>
                    <td><?= $this->Number->format($financa->valor) ?></td>
                </tr>
                <tr>
                    <th><?= __('Desconto') ?></th>
                    <td><?= $financa->desconto === null ? '' : $this->Number->format($financa->desconto) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Baixa') ?></th>
                    <td><?= $financa->data_baixa === null ? '' : $this->Number->format($financa->data_baixa) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor Recebido') ?></th>
                    <td><?= $financa->valor_recebido === null ? '' : $this->Number->format($financa->valor_recebido) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Vencimento') ?></th>
                    <td><?= h($financa->data_vencimento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($financa->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($financa->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Pagamentos') ?></h4>
                <?php if (!empty($financa->pagamentos)) : ?>
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
                        <?php foreach ($financa->pagamentos as $pagamento) : ?>
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