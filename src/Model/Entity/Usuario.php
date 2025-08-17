<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usuario Entity
 *
 * @property int $id
 * @property int $conta_id
 * @property string $nome
 * @property string $email
 * @property string|null $password
 * @property bool|null $ativar
 * @property string $tipo
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Conta $conta
 * @property \App\Model\Entity\Whatsapp[] $whatsapps
 */
class Usuario extends Entity
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
        'nome' => true,
        'email' => true,
        'password' => true,
        'ativar' => true,
        'tipo' => true,
        'created' => true,
        'modified' => true,
        'conta' => true,
        'whatsapps' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'password',
    ];
}
