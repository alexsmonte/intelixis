<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Extensao $extensao
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Extensao'), ['action' => 'edit', $extensao->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Extensao'), ['action' => 'delete', $extensao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $extensao->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Extensoes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Extensao'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="extensoes view content">
            <h3><?= h($extensao->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($extensao->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($extensao->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor') ?></th>
                    <td><?= $extensao->valor === null ? '' : $this->Number->format($extensao->valor) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($extensao->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($extensao->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $extensao->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Descricao') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($extensao->descricao)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Estabelecimentos') ?></h4>
                <?php if (!empty($extensao->estabelecimentos)) : ?>
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
                        <?php foreach ($extensao->estabelecimentos as $estabelecimento) : ?>
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
        </div>
    </div>
</div>