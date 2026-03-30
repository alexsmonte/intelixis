<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Procedimento $procedimento
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Procedimento'), ['action' => 'edit', $procedimento->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Procedimento'), ['action' => 'delete', $procedimento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $procedimento->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Procedimentos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Procedimento'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="procedimentos view content">
            <h3><?= h($procedimento->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Consulta') ?></th>
                    <td><?= $procedimento->hasValue('consulta') ? $this->Html->link($procedimento->consulta->id, ['controller' => 'Consultas', 'action' => 'view', $procedimento->consulta->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Servico') ?></th>
                    <td><?= $procedimento->hasValue('servico') ? $this->Html->link($procedimento->servico->nome, ['controller' => 'Servicos', 'action' => 'view', $procedimento->servico->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Profissional') ?></th>
                    <td><?= $procedimento->hasValue('profissional') ? $this->Html->link($procedimento->profissional->nome, ['controller' => 'Profissionais', 'action' => 'view', $procedimento->profissional->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Faces') ?></th>
                    <td><?= h($procedimento->faces) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dentes') ?></th>
                    <td><?= h($procedimento->dentes) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($procedimento->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Quantidade') ?></th>
                    <td><?= $this->Number->format($procedimento->quantidade) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor Unitario') ?></th>
                    <td><?= $this->Number->format($procedimento->valor_unitario) ?></td>
                </tr>
                <tr>
                    <th><?= __('Desconto') ?></th>
                    <td><?= $procedimento->desconto === null ? '' : $this->Number->format($procedimento->desconto) ?></td>
                </tr>
                <tr>
                    <th><?= __('Parcelas') ?></th>
                    <td><?= $procedimento->parcelas === null ? '' : $this->Number->format($procedimento->parcelas) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($procedimento->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($procedimento->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Aprovar') ?></th>
                    <td><?= $procedimento->aprovar ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Parcelar') ?></th>
                    <td><?= $procedimento->parcelar ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Anamneses') ?></h4>
                <?php if (!empty($procedimento->anamneses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Procedimento Id') ?></th>
                            <th><?= __('Texto') ?></th>
                            <th><?= __('Data Registro') ?></th>
                            <th><?= __('Audio Transcricao') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($procedimento->anamneses as $anamnes) : ?>
                        <tr>
                            <td><?= h($anamnes->id) ?></td>
                            <td><?= h($anamnes->procedimento_id) ?></td>
                            <td><?= h($anamnes->texto) ?></td>
                            <td><?= h($anamnes->data_registro) ?></td>
                            <td><?= h($anamnes->audio_transcricao) ?></td>
                            <td><?= h($anamnes->created) ?></td>
                            <td><?= h($anamnes->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Anamneses', 'action' => 'view', $anamnes->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Anamneses', 'action' => 'edit', $anamnes->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Anamneses', 'action' => 'delete', $anamnes->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $anamnes->id),
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
                <h4><?= __('Related Anotacoes') ?></h4>
                <?php if (!empty($procedimento->anotacoes)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Procedimento Id') ?></th>
                            <th><?= __('Texto') ?></th>
                            <th><?= __('Data Registro') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($procedimento->anotacoes as $anotacao) : ?>
                        <tr>
                            <td><?= h($anotacao->id) ?></td>
                            <td><?= h($anotacao->procedimento_id) ?></td>
                            <td><?= h($anotacao->texto) ?></td>
                            <td><?= h($anotacao->data_registro) ?></td>
                            <td><?= h($anotacao->created) ?></td>
                            <td><?= h($anotacao->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Anotacoes', 'action' => 'view', $anotacao->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Anotacoes', 'action' => 'edit', $anotacao->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Anotacoes', 'action' => 'delete', $anotacao->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $anotacao->id),
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