<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Usuario $usuario
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Usuario'), ['action' => 'edit', $usuario->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Usuario'), ['action' => 'delete', $usuario->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usuario->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Usuarios'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Usuario'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="usuarios view content">
            <h3><?= h($usuario->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Conta') ?></th>
                    <td><?= $usuario->hasValue('conta') ? $this->Html->link($usuario->conta->cpf_cnpj, ['controller' => 'Contas', 'action' => 'view', $usuario->conta->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($usuario->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($usuario->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($usuario->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($usuario->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($usuario->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $usuario->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Whatsapps') ?></h4>
                <?php if (!empty($usuario->whatsapps)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Conta Id') ?></th>
                            <th><?= __('Assinatura Id') ?></th>
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
                        <?php foreach ($usuario->whatsapps as $whatsapp) : ?>
                        <tr>
                            <td><?= h($whatsapp->id) ?></td>
                            <td><?= h($whatsapp->conta_id) ?></td>
                            <td><?= h($whatsapp->assinatura_id) ?></td>
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