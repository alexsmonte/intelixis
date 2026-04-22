<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcamentosServico Entity
 *
 * @property int $id
 * @property int $orcamento_id
 * @property int $servico_id
 * @property int|null $profissional_id
 * @property int|null $quantidade
 * @property string|null $valor_unitario
 * @property string|null $desconto
 * @property string|null $observacao
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Orcamento $orcamento
 * @property \App\Model\Entity\Servico $servico
 * @property \App\Model\Entity\Profissional $profissional
 */
class OrcamentosServico extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
    '*' => true,
    'id' => false
];
}
