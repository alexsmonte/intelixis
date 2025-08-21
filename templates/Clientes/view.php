<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cliente $cliente
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Cliente'), ['action' => 'edit', $cliente->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Cliente'), ['action' => 'delete', $cliente->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cliente->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Clientes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Cliente'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="clientes view content">
            <h3><?= h($cliente->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $cliente->hasValue('estabelecimento') ? $this->Html->link($cliente->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $cliente->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($cliente->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Documento') ?></th>
                    <td><?= h($cliente->documento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($cliente->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Telefone') ?></th>
                    <td><?= h($cliente->telefone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Celular') ?></th>
                    <td><?= h($cliente->celular) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cep') ?></th>
                    <td><?= h($cliente->cep) ?></td>
                </tr>
                <tr>
                    <th><?= __('Endereco') ?></th>
                    <td><?= h($cliente->endereco) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cidade') ?></th>
                    <td><?= h($cliente->cidade) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bairro') ?></th>
                    <td><?= h($cliente->bairro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($cliente->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($cliente->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($cliente->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Agendamentos') ?></h4>
                <?php if (!empty($cliente->agendamentos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Cliente Id') ?></th>
                            <th><?= __('Servico Id') ?></th>
                            <th><?= __('Statu Id') ?></th>
                            <th><?= __('Profissional Id') ?></th>
                            <th><?= __('Local Id') ?></th>
                            <th><?= __('Convenio Id') ?></th>
                            <th><?= __('Data Hora Inicio') ?></th>
                            <th><?= __('Data Hora Fim') ?></th>
                            <th><?= __('Descricao') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($cliente->agendamentos as $agendamento) : ?>
                        <tr>
                            <td><?= h($agendamento->id) ?></td>
                            <td><?= h($agendamento->estabelecimento_id) ?></td>
                            <td><?= h($agendamento->cliente_id) ?></td>
                            <td><?= h($agendamento->servico_id) ?></td>
                            <td><?= h($agendamento->statu_id) ?></td>
                            <td><?= h($agendamento->profissional_id) ?></td>
                            <td><?= h($agendamento->local_id) ?></td>
                            <td><?= h($agendamento->convenio_id) ?></td>
                            <td><?= h($agendamento->data_hora_inicio) ?></td>
                            <td><?= h($agendamento->data_hora_fim) ?></td>
                            <td><?= h($agendamento->descricao) ?></td>
                            <td><?= h($agendamento->created) ?></td>
                            <td><?= h($agendamento->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Agendamentos', 'action' => 'view', $agendamento->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Agendamentos', 'action' => 'edit', $agendamento->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Agendamentos', 'action' => 'delete', $agendamento->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $agendamento->id),
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