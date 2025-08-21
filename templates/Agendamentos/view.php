<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Agendamento $agendamento
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Agendamento'), ['action' => 'edit', $agendamento->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Agendamento'), ['action' => 'delete', $agendamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $agendamento->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Agendamentos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Agendamento'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="agendamentos view content">
            <h3><?= h($agendamento->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $agendamento->hasValue('estabelecimento') ? $this->Html->link($agendamento->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $agendamento->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Cliente') ?></th>
                    <td><?= $agendamento->hasValue('cliente') ? $this->Html->link($agendamento->cliente->nome, ['controller' => 'Clientes', 'action' => 'view', $agendamento->cliente->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Servico') ?></th>
                    <td><?= $agendamento->hasValue('servico') ? $this->Html->link($agendamento->servico->nome, ['controller' => 'Servicos', 'action' => 'view', $agendamento->servico->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $agendamento->hasValue('status') ? $this->Html->link($agendamento->status->nome, ['controller' => 'Status', 'action' => 'view', $agendamento->status->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Profissional') ?></th>
                    <td><?= $agendamento->hasValue('profissional') ? $this->Html->link($agendamento->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $agendamento->profissional->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Local') ?></th>
                    <td><?= $agendamento->hasValue('local') ? $this->Html->link($agendamento->local->nome, ['controller' => 'Locais', 'action' => 'view', $agendamento->local->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Convenio') ?></th>
                    <td><?= $agendamento->hasValue('convenio') ? $this->Html->link($agendamento->convenio->nome, ['controller' => 'Convenios', 'action' => 'view', $agendamento->convenio->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($agendamento->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Hora Inicio') ?></th>
                    <td><?= h($agendamento->data_hora_inicio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Hora Fim') ?></th>
                    <td><?= h($agendamento->data_hora_fim) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($agendamento->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($agendamento->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Descricao') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($agendamento->descricao)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>