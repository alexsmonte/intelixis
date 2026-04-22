<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Orcamento $orcamento
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Orcamento'), ['action' => 'edit', $orcamento->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Orcamento'), ['action' => 'delete', $orcamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orcamento->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Orcamentos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Orcamento'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="orcamentos view content">
            <h3><?= h($orcamento->status) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $orcamento->hasValue('estabelecimento') ? $this->Html->link($orcamento->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $orcamento->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Usuario') ?></th>
                    <td><?= $orcamento->hasValue('usuario') ? $this->Html->link($orcamento->usuario->nome, ['controller' => 'Usuarios', 'action' => 'view', $orcamento->usuario->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Cliente') ?></th>
                    <td><?= $orcamento->hasValue('cliente') ? $this->Html->link($orcamento->cliente->nome, ['controller' => 'Clientes', 'action' => 'view', $orcamento->cliente->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Consulta') ?></th>
                    <td><?= $orcamento->hasValue('consulta') ? $this->Html->link($orcamento->consulta->id, ['controller' => 'Consultas', 'action' => 'view', $orcamento->consulta->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($orcamento->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($orcamento->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor Total') ?></th>
                    <td><?= $this->Number->format($orcamento->valor_total) ?></td>
                </tr>
                <tr>
                    <th><?= __('Desconto') ?></th>
                    <td><?= $orcamento->desconto === null ? '' : $this->Number->format($orcamento->desconto) ?></td>
                </tr>
                <tr>
                    <th><?= __('Validade') ?></th>
                    <td><?= h($orcamento->validade) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($orcamento->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($orcamento->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Observacoes') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($orcamento->observacoes)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Servicos') ?></h4>
                <?php if (!empty($orcamento->servicos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Descricao') ?></th>
                            <th><?= __('Duracao') ?></th>
                            <th><?= __('Ativar') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Valor') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($orcamento->servicos as $servico) : ?>
                        <tr>
                            <td><?= h($servico->id) ?></td>
                            <td><?= h($servico->estabelecimento_id) ?></td>
                            <td><?= h($servico->nome) ?></td>
                            <td><?= h($servico->descricao) ?></td>
                            <td><?= h($servico->duracao) ?></td>
                            <td><?= h($servico->ativar) ?></td>
                            <td><?= h($servico->created) ?></td>
                            <td><?= h($servico->modified) ?></td>
                            <td><?= h($servico->valor) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Servicos', 'action' => 'view', $servico->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Servicos', 'action' => 'edit', $servico->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Servicos', 'action' => 'delete', $servico->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $servico->id),
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