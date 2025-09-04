<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Servico $servico
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Servico'), ['action' => 'edit', $servico->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Servico'), ['action' => 'delete', $servico->id], ['confirm' => __('Are you sure you want to delete # {0}?', $servico->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Servicos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Servico'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="servicos view content">
            <h3><?= h($servico->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Estabelecimento') ?></th>
                    <td><?= $servico->hasValue('estabelecimento') ? $this->Html->link($servico->estabelecimento->razao_social, ['controller' => 'Estabelecimentos', 'action' => 'view', $servico->estabelecimento->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($servico->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($servico->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Duracao') ?></th>
                    <td><?= $this->Number->format($servico->duracao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($servico->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($servico->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $servico->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Descricao') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($servico->descricao)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Convenios') ?></h4>
                <?php if (!empty($servico->convenios)) : ?>
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
                        <?php foreach ($servico->convenios as $convenio) : ?>
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
                <h4><?= __('Related Profissionais') ?></h4>
                <?php if (!empty($servico->profissionais)) : ?>
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
                        <?php foreach ($servico->profissionais as $profissional) : ?>
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
                <h4><?= __('Related Agendamentos') ?></h4>
                <?php if (!empty($servico->agendamentos)) : ?>
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
                        <?php foreach ($servico->agendamentos as $agendamento) : ?>
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
                <?php if (!empty($servico->bloqueios)) : ?>
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
                        <?php foreach ($servico->bloqueios as $bloqueio) : ?>
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
                <?php if (!empty($servico->periodos)) : ?>
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
                        <?php foreach ($servico->periodos as $periodo) : ?>
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