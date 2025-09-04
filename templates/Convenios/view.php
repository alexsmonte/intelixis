<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Convenio $convenio
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Convenio'), ['action' => 'edit', $convenio->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Convenio'), ['action' => 'delete', $convenio->id], ['confirm' => __('Are you sure you want to delete # {0}?', $convenio->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Convenios'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Convenio'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="convenios view content">
            <h3><?= h($convenio->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $convenio->hasValue('estabelecimento') ? $this->Html->link($convenio->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $convenio->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($convenio->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Registro Ans') ?></th>
                    <td><?= h($convenio->registro_ans) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cnpj') ?></th>
                    <td><?= h($convenio->cnpj) ?></td>
                </tr>
                <tr>
                    <th><?= __('Telefone') ?></th>
                    <td><?= h($convenio->telefone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($convenio->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Site') ?></th>
                    <td><?= h($convenio->site) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($convenio->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($convenio->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($convenio->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $convenio->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Profissionais') ?></h4>
                <?php if (!empty($convenio->profissionais)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Telefone') ?></th>
                            <th><?= __('Celular') ?></th>
                            <th><?= __('Cep') ?></th>
                            <th><?= __('Logradouro') ?></th>
                            <th><?= __('Numero') ?></th>
                            <th><?= __('Complemento') ?></th>
                            <th><?= __('Bairro') ?></th>
                            <th><?= __('Cidade') ?></th>
                            <th><?= __('Ativar') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($convenio->profissionais as $profissional) : ?>
                        <tr>
                            <td><?= h($profissional->id) ?></td>
                            <td><?= h($profissional->estabelecimento_id) ?></td>
                            <td><?= h($profissional->nome) ?></td>
                            <td><?= h($profissional->email) ?></td>
                            <td><?= h($profissional->telefone) ?></td>
                            <td><?= h($profissional->celular) ?></td>
                            <td><?= h($profissional->cep) ?></td>
                            <td><?= h($profissional->logradouro) ?></td>
                            <td><?= h($profissional->numero) ?></td>
                            <td><?= h($profissional->complemento) ?></td>
                            <td><?= h($profissional->bairro) ?></td>
                            <td><?= h($profissional->cidade) ?></td>
                            <td><?= h($profissional->ativar) ?></td>
                            <td><?= h($profissional->created) ?></td>
                            <td><?= h($profissional->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Profissionais', 'action' => 'view', $profissional->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Profissionais', 'action' => 'edit', $profissional->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Profissionais', 'action' => 'delete', $profissional->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $profissional->id),
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
                <h4><?= __('Related Servicos') ?></h4>
                <?php if (!empty($convenio->servicos)) : ?>
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
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($convenio->servicos as $servico) : ?>
                        <tr>
                            <td><?= h($servico->id) ?></td>
                            <td><?= h($servico->estabelecimento_id) ?></td>
                            <td><?= h($servico->nome) ?></td>
                            <td><?= h($servico->descricao) ?></td>
                            <td><?= h($servico->duracao) ?></td>
                            <td><?= h($servico->ativar) ?></td>
                            <td><?= h($servico->created) ?></td>
                            <td><?= h($servico->modified) ?></td>
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
            <div class="related">
                <h4><?= __('Related Agendamentos') ?></h4>
                <?php if (!empty($convenio->agendamentos)) : ?>
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
                        <?php foreach ($convenio->agendamentos as $agendamento) : ?>
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
                <h4><?= __('Related Periodos') ?></h4>
                <?php if (!empty($convenio->periodos)) : ?>
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
                        <?php foreach ($convenio->periodos as $periodo) : ?>
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