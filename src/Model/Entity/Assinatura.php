<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Assinatura Entity
 *
 * @property int $id
 * @property int $conta_id
 * @property int $plano_id
 * @property \Cake\I18n\Date $data_vencimento
 * @property string|null $desconto
 * @property string|null $status
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Conta $conta
 * @property \App\Model\Entity\Plano $plano
 * @property \App\Model\Entity\Whatsapp[] $whatsapps
 */
class Assinatura extends Entity
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
        'plano_id' => true,
        'data_vencimento' => true,
        'desconto' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'conta' => true,
        'plano' => true,
        'whatsapps' => true,
    ];
}
