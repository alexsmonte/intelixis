<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ccusto $ccusto
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Ccusto'), ['action' => 'edit', $ccusto->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Ccusto'), ['action' => 'delete', $ccusto->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ccusto->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Ccustos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Ccusto'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="ccustos view content">
            <h3><?= h($ccusto->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $ccusto->hasValue('estabelecimento') ? $this->Html->link($ccusto->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $ccusto->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($ccusto->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Parent Ccusto') ?></th>
                    <td><?= $ccusto->hasValue('parent_ccusto') ? $this->Html->link($ccusto->parent_ccusto->name, ['controller' => 'Ccustos', 'action' => 'view', $ccusto->parent_ccusto->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($ccusto->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Lft') ?></th>
                    <td><?= $ccusto->lft === null ? '' : $this->Number->format($ccusto->lft) ?></td>
                </tr>
                <tr>
                    <th><?= __('Rght') ?></th>
                    <td><?= $ccusto->rght === null ? '' : $this->Number->format($ccusto->rght) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Ccustos') ?></h4>
                <?php if (!empty($ccusto->child_ccustos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Parent Id') ?></th>
                            <th><?= __('Lft') ?></th>
                            <th><?= __('Rght') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($ccusto->child_ccustos as $childCcusto) : ?>
                        <tr>
                            <td><?= h($childCcusto->id) ?></td>
                            <td><?= h($childCcusto->estabelecimento_id) ?></td>
                            <td><?= h($childCcusto->name) ?></td>
                            <td><?= h($childCcusto->parent_id) ?></td>
                            <td><?= h($childCcusto->lft) ?></td>
                            <td><?= h($childCcusto->rght) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Ccustos', 'action' => 'view', $childCcusto->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Ccustos', 'action' => 'edit', $childCcusto->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Ccustos', 'action' => 'delete', $childCcusto->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $childCcusto->id),
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