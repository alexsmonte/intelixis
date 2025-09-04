<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bloqueio $bloqueio
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Bloqueio'), ['action' => 'edit', $bloqueio->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Bloqueio'), ['action' => 'delete', $bloqueio->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bloqueio->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Bloqueios'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Bloqueio'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="bloqueios view content">
            <h3><?= h($bloqueio->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $bloqueio->hasValue('estabelecimento') ? $this->Html->link($bloqueio->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $bloqueio->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Profissional') ?></th>
                    <td><?= $bloqueio->hasValue('profissional') ? $this->Html->link($bloqueio->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $bloqueio->profissional->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Servico') ?></th>
                    <td><?= $bloqueio->hasValue('servico') ? $this->Html->link($bloqueio->servico->nome, ['controller' => 'Servicos', 'action' => 'view', $bloqueio->servico->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Local') ?></th>
                    <td><?= $bloqueio->hasValue('local') ? $this->Html->link($bloqueio->local->nome, ['controller' => 'Locais', 'action' => 'view', $bloqueio->local->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($bloqueio->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Hora Inicio') ?></th>
                    <td><?= $bloqueio->hora_inicio === null ? '' : $this->Number->format($bloqueio->hora_inicio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Hora Fim') ?></th>
                    <td><?= $bloqueio->hora_fim === null ? '' : $this->Number->format($bloqueio->hora_fim) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Inicio') ?></th>
                    <td><?= h($bloqueio->data_inicio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Fim') ?></th>
                    <td><?= h($bloqueio->data_fim) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($bloqueio->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($bloqueio->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dia Inteiro') ?></th>
                    <td><?= $bloqueio->dia_inteiro ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>