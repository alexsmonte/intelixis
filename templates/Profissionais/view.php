<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Profissional $profissional
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Profissional'), ['action' => 'edit', $profissional->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Profissional'), ['action' => 'delete', $profissional->id], ['confirm' => __('Are you sure you want to delete # {0}?', $profissional->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Profissionais'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Profissional'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="profissionais view content">
            <h3><?= h($profissional->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $profissional->hasValue('estabelecimento') ? $this->Html->link($profissional->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $profissional->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($profissional->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($profissional->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Telefone') ?></th>
                    <td><?= h($profissional->telefone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Celular') ?></th>
                    <td><?= h($profissional->celular) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cep') ?></th>
                    <td><?= h($profissional->cep) ?></td>
                </tr>
                <tr>
                    <th><?= __('Logradouro') ?></th>
                    <td><?= h($profissional->logradouro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Numero') ?></th>
                    <td><?= h($profissional->numero) ?></td>
                </tr>
                <tr>
                    <th><?= __('Complemento') ?></th>
                    <td><?= h($profissional->complemento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bairro') ?></th>
                    <td><?= h($profissional->bairro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cidade') ?></th>
                    <td><?= h($profissional->cidade) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($profissional->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($profissional->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($profissional->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $profissional->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Convenios') ?></h4>
                <?php if (!empty($profissional->convenios)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Registro Ans') ?></th>
                            <th><?= __('Cnpj') ?></th>
                            <th><?= __('Telefone') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Site') ?></th>
                            <th><?= __('Ativar') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($profissional->convenios as $convenio) : ?>
                        <tr>
                            <td><?= h($convenio->id) ?></td>
                            <td><?= h($convenio->estabelecimento_id) ?></td>
                            <td><?= h($convenio->nome) ?></td>
                            <td><?= h($convenio->registro_ans) ?></td>
                            <td><?= h($convenio->cnpj) ?></td>
                            <td><?= h($convenio->telefone) ?></td>
                            <td><?= h($convenio->email) ?></td>
                            <td><?= h($convenio->site) ?></td>
                            <td><?= h($convenio->ativar) ?></td>
                            <td><?= h($convenio->created) ?></td>
                            <td><?= h($convenio->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Convenios', 'action' => 'view', $convenio->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Convenios', 'action' => 'edit', $convenio->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Convenios', 'action' => 'delete', $convenio->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $convenio->id),
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
                <?php if (!empty($profissional->servicos)) : ?>
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
                        <?php foreach ($profissional->servicos as $servico) : ?>
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
                <?php if (!empty($profissional->agendamentos)) : ?>
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
                        <?php foreach ($profissional->agendamentos as $agendamento) : ?>
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
                <?php if (!empty($profissional->bloqueios)) : ?>
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
                        <?php foreach ($profissional->bloqueios as $bloqueio) : ?>
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
                <?php if (!empty($profissional->periodos)) : ?>
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
                        <?php foreach ($profissional->periodos as $periodo) : ?>
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