<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Periodo $periodo
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Periodo'), ['action' => 'edit', $periodo->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Periodo'), ['action' => 'delete', $periodo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $periodo->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Periodos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Periodo'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="periodos view content">
            <h3><?= h($periodo->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $periodo->hasValue('estabelecimento') ? $this->Html->link($periodo->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $periodo->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Profissional') ?></th>
                    <td><?= $periodo->hasValue('profissional') ? $this->Html->link($periodo->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $periodo->profissional->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Servico') ?></th>
                    <td><?= $periodo->hasValue('servico') ? $this->Html->link($periodo->servico->nome, ['controller' => 'Servicos', 'action' => 'view', $periodo->servico->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Local') ?></th>
                    <td><?= $periodo->hasValue('local') ? $this->Html->link($periodo->local->nome, ['controller' => 'Locais', 'action' => 'view', $periodo->local->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Convenio') ?></th>
                    <td><?= $periodo->hasValue('convenio') ? $this->Html->link($periodo->convenio->nome, ['controller' => 'Convenios', 'action' => 'view', $periodo->convenio->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Cadeia Id') ?></th>
                    <td><?= h($periodo->cadeia_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($periodo->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dia Semana') ?></th>
                    <td><?= $this->Number->format($periodo->dia_semana) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Hora Inicio') ?></th>
                    <td><?= h($periodo->data_hora_inicio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Hora Fim') ?></th>
                    <td><?= h($periodo->data_hora_fim) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Personalizada') ?></th>
                    <td><?= h($periodo->data_personalizada) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($periodo->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($periodo->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $periodo->status ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>