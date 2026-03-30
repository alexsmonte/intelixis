<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Local $local
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Local'), ['action' => 'edit', $local->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Local'), ['action' => 'delete', $local->id], ['confirm' => __('Are you sure you want to delete # {0}?', $local->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Locais'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Local'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="locais view content">
            <h3><?= h($local->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $local->hasValue('estabelecimento') ? $this->Html->link($local->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $local->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($local->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($local->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($local->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($local->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $local->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Endereco') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($local->endereco)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Agendamentos') ?></h4>
                <?php if (!empty($local->agendamentos)) : ?>
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
                        <?php foreach ($local->agendamentos as $agendamento) : ?>
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
            <div class="related">
                <h4><?= __('Related Bloqueios') ?></h4>
                <?php if (!empty($local->bloqueios)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Profissional Id') ?></th>
                            <th><?= __('Servico Id') ?></th>
                            <th><?= __('Local Id') ?></th>
                            <th><?= __('Data Inicio') ?></th>
                            <th><?= __('Data Fim') ?></th>
                            <th><?= __('Dia Inteiro') ?></th>
                            <th><?= __('Hora Inicio') ?></th>
                            <th><?= __('Hora Fim') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($local->bloqueios as $bloqueio) : ?>
                        <tr>
                            <td><?= h($bloqueio->id) ?></td>
                            <td><?= h($bloqueio->estabelecimento_id) ?></td>
                            <td><?= h($bloqueio->profissional_id) ?></td>
                            <td><?= h($bloqueio->servico_id) ?></td>
                            <td><?= h($bloqueio->local_id) ?></td>
                            <td><?= h($bloqueio->data_inicio) ?></td>
                            <td><?= h($bloqueio->data_fim) ?></td>
                            <td><?= h($bloqueio->dia_inteiro) ?></td>
                            <td><?= h($bloqueio->hora_inicio) ?></td>
                            <td><?= h($bloqueio->hora_fim) ?></td>
                            <td><?= h($bloqueio->created) ?></td>
                            <td><?= h($bloqueio->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Bloqueios', 'action' => 'view', $bloqueio->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Bloqueios', 'action' => 'edit', $bloqueio->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Bloqueios', 'action' => 'delete', $bloqueio->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $bloqueio->id),
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
                <h4><?= __('Related Periodos') ?></h4>
                <?php if (!empty($local->periodos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Profissional Id') ?></th>
                            <th><?= __('Servico Id') ?></th>
                            <th><?= __('Local Id') ?></th>
                            <th><?= __('Convenio Id') ?></th>
                            <th><?= __('Data Hora Inicio') ?></th>
                            <th><?= __('Data Hora Fim') ?></th>
                            <th><?= __('Dia Semana') ?></th>
                            <th><?= __('Data Personalizada') ?></th>
                            <th><?= __('Cadeia Id') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($local->periodos as $periodo) : ?>
                        <tr>
                            <td><?= h($periodo->id) ?></td>
                            <td><?= h($periodo->estabelecimento_id) ?></td>
                            <td><?= h($periodo->profissional_id) ?></td>
                            <td><?= h($periodo->servico_id) ?></td>
                            <td><?= h($periodo->local_id) ?></td>
                            <td><?= h($periodo->convenio_id) ?></td>
                            <td><?= h($periodo->data_hora_inicio) ?></td>
                            <td><?= h($periodo->data_hora_fim) ?></td>
                            <td><?= h($periodo->dia_semana) ?></td>
                            <td><?= h($periodo->data_personalizada) ?></td>
                            <td><?= h($periodo->cadeia_id) ?></td>
                            <td><?= h($periodo->status) ?></td>
                            <td><?= h($periodo->created) ?></td>
                            <td><?= h($periodo->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Periodos', 'action' => 'view', $periodo->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Periodos', 'action' => 'edit', $periodo->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Periodos', 'action' => 'delete', $periodo->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $periodo->id),
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