<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Conta $conta
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Conta'), ['action' => 'edit', $conta->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Conta'), ['action' => 'delete', $conta->id], ['confirm' => __('Are you sure you want to delete # {0}?', $conta->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Contas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Conta'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="contas view content">
            <h3><?= h($conta->cpf_cnpj) ?></h3>
            <table>
                <tr>
                    <th><?= __('Cpf Cnpj') ?></th>
                    <td><?= h($conta->cpf_cnpj) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($conta->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Razao Social') ?></th>
                    <td><?= h($conta->razao_social) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($conta->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Logradouro') ?></th>
                    <td><?= h($conta->logradouro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cep') ?></th>
                    <td><?= h($conta->cep) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bairro') ?></th>
                    <td><?= h($conta->bairro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cidade') ?></th>
                    <td><?= h($conta->cidade) ?></td>
                </tr>
                <tr>
                    <th><?= __('Uf') ?></th>
                    <td><?= h($conta->uf) ?></td>
                </tr>
                <tr>
                    <th><?= __('Complemento') ?></th>
                    <td><?= h($conta->complemento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($conta->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($conta->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($conta->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $conta->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Assinaturas') ?></h4>
                <?php if (!empty($conta->assinaturas)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Conta Id') ?></th>
                            <th><?= __('Plano Id') ?></th>
                            <th><?= __('Data Vencimento') ?></th>
                            <th><?= __('Desconto') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($conta->assinaturas as $assinatura) : ?>
                        <tr>
                            <td><?= h($assinatura->id) ?></td>
                            <td><?= h($assinatura->conta_id) ?></td>
                            <td><?= h($assinatura->plano_id) ?></td>
                            <td><?= h($assinatura->data_vencimento) ?></td>
                            <td><?= h($assinatura->desconto) ?></td>
                            <td><?= h($assinatura->status) ?></td>
                            <td><?= h($assinatura->created) ?></td>
                            <td><?= h($assinatura->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Assinaturas', 'action' => 'view', $assinatura->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Assinaturas', 'action' => 'edit', $assinatura->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Assinaturas', 'action' => 'delete', $assinatura->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $assinatura->id),
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
                <h4><?= __('Related Estabelecimentos') ?></h4>
                <?php if (!empty($conta->estabelecimentos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Conta Id') ?></th>
                            <th><?= __('Assinatura Id') ?></th>
                            <th><?= __('Razao Social') ?></th>
                            <th><?= __('Nome Fantasia') ?></th>
                            <th><?= __('Cpf Cnpj') ?></th>
                            <th><?= __('Logradouro') ?></th>
                            <th><?= __('Numero') ?></th>
                            <th><?= __('Complemento') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Telefone') ?></th>
                            <th><?= __('Celular') ?></th>
                            <th><?= __('Ativar') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($conta->estabelecimentos as $estabelecimento) : ?>
                        <tr>
                            <td><?= h($estabelecimento->id) ?></td>
                            <td><?= h($estabelecimento->conta_id) ?></td>
                            <td><?= h($estabelecimento->assinatura_id) ?></td>
                            <td><?= h($estabelecimento->razao_social) ?></td>
                            <td><?= h($estabelecimento->nome_fantasia) ?></td>
                            <td><?= h($estabelecimento->cpf_cnpj) ?></td>
                            <td><?= h($estabelecimento->logradouro) ?></td>
                            <td><?= h($estabelecimento->numero) ?></td>
                            <td><?= h($estabelecimento->complemento) ?></td>
                            <td><?= h($estabelecimento->email) ?></td>
                            <td><?= h($estabelecimento->telefone) ?></td>
                            <td><?= h($estabelecimento->celular) ?></td>
                            <td><?= h($estabelecimento->ativar) ?></td>
                            <td><?= h($estabelecimento->created) ?></td>
                            <td><?= h($estabelecimento->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Estabelecimentos', 'action' => 'view', $estabelecimento->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Estabelecimentos', 'action' => 'edit', $estabelecimento->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Estabelecimentos', 'action' => 'delete', $estabelecimento->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $estabelecimento->id),
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
                <?php if (!empty($conta->usuarios)) : ?>
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
                        <?php foreach ($conta->usuarios as $usuario) : ?>
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
                <h4><?= __('Related Whatsapps') ?></h4>
                <?php if (!empty($conta->whatsapps)) : ?>
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
                        <?php foreach ($conta->whatsapps as $whatsapp) : ?>
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