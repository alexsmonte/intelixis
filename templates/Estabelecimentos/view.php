<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Estabelecimento $estabelecimento
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Estabelecimento'), ['action' => 'edit', $estabelecimento->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Estabelecimento'), ['action' => 'delete', $estabelecimento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estabelecimento->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Estabelecimentos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Estabelecimento'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="estabelecimentos view content">
            <h3><?= h($estabelecimento->razao_social) ?></h3>
            <table>
                <tr>
                    <th><?= __('Conta') ?></th>
                    <td><?= $estabelecimento->hasValue('conta') ? $this->Html->link($estabelecimento->conta->cpf_cnpj, ['controller' => 'Contas', 'action' => 'view', $estabelecimento->conta->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Assinatura') ?></th>
                    <td><?= $estabelecimento->hasValue('assinatura') ? $this->Html->link($estabelecimento->assinatura->id, ['controller' => 'Assinaturas', 'action' => 'view', $estabelecimento->assinatura->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Razao Social') ?></th>
                    <td><?= h($estabelecimento->razao_social) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome Fantasia') ?></th>
                    <td><?= h($estabelecimento->nome_fantasia) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cpf Cnpj') ?></th>
                    <td><?= h($estabelecimento->cpf_cnpj) ?></td>
                </tr>
                <tr>
                    <th><?= __('Logradouro') ?></th>
                    <td><?= h($estabelecimento->logradouro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Numero') ?></th>
                    <td><?= h($estabelecimento->numero) ?></td>
                </tr>
                <tr>
                    <th><?= __('Complemento') ?></th>
                    <td><?= h($estabelecimento->complemento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($estabelecimento->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Telefone') ?></th>
                    <td><?= h($estabelecimento->telefone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Celular') ?></th>
                    <td><?= h($estabelecimento->celular) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($estabelecimento->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($estabelecimento->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($estabelecimento->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $estabelecimento->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Extensoes') ?></h4>
                <?php if (!empty($estabelecimento->extensoes)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Descricao') ?></th>
                            <th><?= __('Valor') ?></th>
                            <th><?= __('Ativar') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($estabelecimento->extensoes as $extensao) : ?>
                        <tr>
                            <td><?= h($extensao->id) ?></td>
                            <td><?= h($extensao->nome) ?></td>
                            <td><?= h($extensao->descricao) ?></td>
                            <td><?= h($extensao->valor) ?></td>
                            <td><?= h($extensao->ativar) ?></td>
                            <td><?= h($extensao->created) ?></td>
                            <td><?= h($extensao->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Extensoes', 'action' => 'view', $extensao->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Extensoes', 'action' => 'edit', $extensao->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Extensoes', 'action' => 'delete', $extensao->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $extensao->id),
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
                <h4><?= __('Related Usuarios') ?></h4>
                <?php if (!empty($estabelecimento->usuarios)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Conta Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Password') ?></th>
                            <th><?= __('Ativar') ?></th>
                            <th><?= __('Tipo') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($estabelecimento->usuarios as $usuario) : ?>
                        <tr>
                            <td><?= h($usuario->id) ?></td>
                            <td><?= h($usuario->conta_id) ?></td>
                            <td><?= h($usuario->nome) ?></td>
                            <td><?= h($usuario->email) ?></td>
                            <td><?= h($usuario->password) ?></td>
                            <td><?= h($usuario->ativar) ?></td>
                            <td><?= h($usuario->tipo) ?></td>
                            <td><?= h($usuario->created) ?></td>
                            <td><?= h($usuario->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Usuarios', 'action' => 'view', $usuario->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Usuarios', 'action' => 'edit', $usuario->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Usuarios', 'action' => 'delete', $usuario->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $usuario->id),
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
                <?php if (!empty($estabelecimento->agendamentos)) : ?>
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
                        <?php foreach ($estabelecimento->agendamentos as $agendamento) : ?>
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
                <?php if (!empty($estabelecimento->bloqueios)) : ?>
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
                        <?php foreach ($estabelecimento->bloqueios as $bloqueio) : ?>
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
                <h4><?= __('Related Clientes') ?></h4>
                <?php if (!empty($estabelecimento->clientes)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Documento') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Telefone') ?></th>
                            <th><?= __('Celular') ?></th>
                            <th><?= __('Cep') ?></th>
                            <th><?= __('Endereco') ?></th>
                            <th><?= __('Cidade') ?></th>
                            <th><?= __('Bairro') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($estabelecimento->clientes as $cliente) : ?>
                        <tr>
                            <td><?= h($cliente->id) ?></td>
                            <td><?= h($cliente->estabelecimento_id) ?></td>
                            <td><?= h($cliente->nome) ?></td>
                            <td><?= h($cliente->documento) ?></td>
                            <td><?= h($cliente->email) ?></td>
                            <td><?= h($cliente->telefone) ?></td>
                            <td><?= h($cliente->celular) ?></td>
                            <td><?= h($cliente->cep) ?></td>
                            <td><?= h($cliente->endereco) ?></td>
                            <td><?= h($cliente->cidade) ?></td>
                            <td><?= h($cliente->bairro) ?></td>
                            <td><?= h($cliente->created) ?></td>
                            <td><?= h($cliente->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Clientes', 'action' => 'view', $cliente->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Clientes', 'action' => 'edit', $cliente->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Clientes', 'action' => 'delete', $cliente->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $cliente->id),
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
                <h4><?= __('Related Convenios') ?></h4>
                <?php if (!empty($estabelecimento->convenios)) : ?>
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
                        <?php foreach ($estabelecimento->convenios as $convenio) : ?>
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
                <h4><?= __('Related Locais') ?></h4>
                <?php if (!empty($estabelecimento->locais)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Endereco') ?></th>
                            <th><?= __('Ativar') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($estabelecimento->locais as $local) : ?>
                        <tr>
                            <td><?= h($local->id) ?></td>
                            <td><?= h($local->estabelecimento_id) ?></td>
                            <td><?= h($local->nome) ?></td>
                            <td><?= h($local->endereco) ?></td>
                            <td><?= h($local->ativar) ?></td>
                            <td><?= h($local->created) ?></td>
                            <td><?= h($local->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Locais', 'action' => 'view', $local->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Locais', 'action' => 'edit', $local->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Locais', 'action' => 'delete', $local->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $local->id),
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
                <?php if (!empty($estabelecimento->periodos)) : ?>
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
                        <?php foreach ($estabelecimento->periodos as $periodo) : ?>
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
            <div class="related">
                <h4><?= __('Related Profissionais') ?></h4>
                <?php if (!empty($estabelecimento->profissionais)) : ?>
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
                        <?php foreach ($estabelecimento->profissionais as $profissional) : ?>
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
                <?php if (!empty($estabelecimento->servicos)) : ?>
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
                        <?php foreach ($estabelecimento->servicos as $servico) : ?>
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
                <h4><?= __('Related Status') ?></h4>
                <?php if (!empty($estabelecimento->status)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Ativar') ?></th>
                            <th><?= __('Cor') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($estabelecimento->status as $status) : ?>
                        <tr>
                            <td><?= h($status->id) ?></td>
                            <td><?= h($status->estabelecimento_id) ?></td>
                            <td><?= h($status->nome) ?></td>
                            <td><?= h($status->ativar) ?></td>
                            <td><?= h($status->cor) ?></td>
                            <td><?= h($status->created) ?></td>
                            <td><?= h($status->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Status', 'action' => 'view', $status->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Status', 'action' => 'edit', $status->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Status', 'action' => 'delete', $status->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $status->id),
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
                <h4><?= __('Related Whatsapps') ?></h4>
                <?php if (!empty($estabelecimento->whatsapps)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Conta Id') ?></th>
                            <th><?= __('Estabelecimento Id') ?></th>
                            <th><?= __('Phone Number') ?></th>
                            <th><?= __('Business Account') ?></th>
                            <th><?= __('Display Phone Number') ?></th>
                            <th><?= __('Access Token') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Pin') ?></th>
                            <th><?= __('Senha Chatwoot') ?></th>
                            <th><?= __('Token Empresa') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($estabelecimento->whatsapps as $whatsapp) : ?>
                        <tr>
                            <td><?= h($whatsapp->id) ?></td>
                            <td><?= h($whatsapp->conta_id) ?></td>
                            <td><?= h($whatsapp->estabelecimento_id) ?></td>
                            <td><?= h($whatsapp->phone_number) ?></td>
                            <td><?= h($whatsapp->business_account) ?></td>
                            <td><?= h($whatsapp->display_phone_number) ?></td>
                            <td><?= h($whatsapp->access_token) ?></td>
                            <td><?= h($whatsapp->nome) ?></td>
                            <td><?= h($whatsapp->pin) ?></td>
                            <td><?= h($whatsapp->senha_chatwoot) ?></td>
                            <td><?= h($whatsapp->token_empresa) ?></td>
                            <td><?= h($whatsapp->created) ?></td>
                            <td><?= h($whatsapp->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Whatsapps', 'action' => 'view', $whatsapp->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Whatsapps', 'action' => 'edit', $whatsapp->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Whatsapps', 'action' => 'delete', $whatsapp->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $whatsapp->id),
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