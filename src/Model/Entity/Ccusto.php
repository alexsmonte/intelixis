<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ccusto Entity
 *
 * @property int $id
 * @property int|null $estabelecimento_id
 * @property string|null $name
 * @property int|null $parent_id
 * @property int|null $lft
 * @property int|null $rght
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Ccusto $parent_ccusto
 * @property \App\Model\Entity\Ccusto[] $child_ccustos
 */
class Ccusto extends Entity
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
        'name' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'estabelecimento' => true,
        'parent_ccusto' => true,
        'child_ccustos' => true,
    ];
}
