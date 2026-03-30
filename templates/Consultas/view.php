<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Consulta $consulta
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Consulta'), ['action' => 'edit', $consulta->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Consulta'), ['action' => 'delete', $consulta->id], ['confirm' => __('Are you sure you want to delete # {0}?', $consulta->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Consultas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Consulta'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="consultas view content">
            <h3><?= h($consulta->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $consulta->hasValue('estabelecimento') ? $this->Html->link($consulta->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $consulta->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Cliente') ?></th>
                    <td><?= $consulta->hasValue('cliente') ? $this->Html->link($consulta->cliente->nome, ['controller' => 'Clientes', 'action' => 'view', $consulta->cliente->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Profissional') ?></th>
                    <td><?= $consulta->hasValue('profissional') ? $this->Html->link($consulta->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $consulta->profissional->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($consulta->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($consulta->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($consulta->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Procedimentos') ?></h4>
                <?php if (!empty($consulta->procedimentos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Consulta Id') ?></th>
                            <th><?= __('Servico Id') ?></th>
                            <th><?= __('Profissional Id') ?></th>
                            <th><?= __('Quantidade') ?></th>
                            <th><?= __('Valor Unitario') ?></th>
                            <th><?= __('Desconto') ?></th>
                            <th><?= __('Aprovar') ?></th>
                            <th><?= __('Parcelar') ?></th>
                            <th><?= __('Parcelas') ?></th>
                            <th><?= __('Faces') ?></th>
                            <th><?= __('Dentes') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($consulta->procedimentos as $procedimento) : ?>
                        <tr>
                            <td><?= h($procedimento->id) ?></td>
                            <td><?= h($procedimento->consulta_id) ?></td>
                            <td><?= h($procedimento->servico_id) ?></td>
                            <td><?= h($procedimento->profissional_id) ?></td>
                            <td><?= h($procedimento->quantidade) ?></td>
                            <td><?= h($procedimento->valor_unitario) ?></td>
                            <td><?= h($procedimento->desconto) ?></td>
                            <td><?= h($procedimento->aprovar) ?></td>
                            <td><?= h($procedimento->parcelar) ?></td>
                            <td><?= h($procedimento->parcelas) ?></td>
                            <td><?= h($procedimento->faces) ?></td>
                            <td><?= h($procedimento->dentes) ?></td>
                            <td><?= h($procedimento->created) ?></td>
                            <td><?= h($procedimento->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Procedimentos', 'action' => 'view', $procedimento->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Procedimentos', 'action' => 'edit', $procedimento->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Procedimentos', 'action' => 'delete', $procedimento->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $procedimento->id),
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