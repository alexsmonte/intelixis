<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Receituario $receituario
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Receituario'), ['action' => 'edit', $receituario->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Receituario'), ['action' => 'delete', $receituario->id], ['confirm' => __('Are you sure you want to delete # {0}?', $receituario->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Receituarios'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Receituario'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="receituarios view content">
            <h3><?= h($receituario->categoria) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $receituario->hasValue('estabelecimento') ? $this->Html->link($receituario->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $receituario->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Categoria') ?></th>
                    <td><?= h($receituario->categoria) ?></td>
                </tr>
                <tr>
                    <th><?= __('Titulo') ?></th>
                    <td><?= h($receituario->titulo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($receituario->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($receituario->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($receituario->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Descricao') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($receituario->descricao)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>