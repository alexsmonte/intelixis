<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Consulta Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property int $cliente_id
 * @property int $profissional_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Cliente $cliente
 * @property \App\Model\Entity\Profissional $profissional
 * @property \App\Model\Entity\Procedimento[] $procedimentos
 */
class Consulta extends Entity
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
        'estabelecimento_id' => true,
        'cliente_id' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
        'cliente' => true,
        'procedimentos' => true,
    ];
}
