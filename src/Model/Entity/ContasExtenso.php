<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContasExtenso Entity
 *
 * @property int $id
 * @property int $conta_id
 * @property int $extensao_id
 * @property \Cake\I18n\DateTime|null $data_ativacao
 *
 * @property \App\Model\Entity\Conta $conta
 * @property \App\Model\Entity\Extensao $extensao
 */
class ContasExtenso extends Entity
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
        'conta_id' => true,
        'extensao_id' => true,
        'data_ativacao' => true,
        'conta' => true,
        'extensao' => true,
    ];
}
