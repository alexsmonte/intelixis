<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plano $plano
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Plano'), ['action' => 'edit', $plano->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Plano'), ['action' => 'delete', $plano->id], ['confirm' => __('Are you sure you want to delete # {0}?', $plano->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Planos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Plano'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="planos view content">
            <h3><?= h($plano->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($plano->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Resumo') ?></th>
                    <td><?= h($plano->resumo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($plano->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor') ?></th>
                    <td><?= $plano->valor === null ? '' : $this->Number->format($plano->valor) ?></td>
                </tr>
                <tr>
                    <th><?= __('Numero Whatsapp') ?></th>
                    <td><?= $plano->numero_whatsapp === null ? '' : $this->Number->format($plano->numero_whatsapp) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($plano->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($plano->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativar') ?></th>
                    <td><?= $plano->ativar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Descricao') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($plano->descricao)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Assinaturas') ?></h4>
                <?php if (!empty($plano->assinaturas)) : ?>
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
                        <?php foreach ($plano->assinaturas as $assinatura) : ?>
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
        </div>
    </div>
</div>