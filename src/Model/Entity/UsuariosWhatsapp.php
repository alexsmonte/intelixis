<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuariosWhatsapp Entity
 *
 * @property int $id
 * @property int $usuarios_id
 * @property int $whatsapps_id
 *
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Whatsapp $whatsapp
 */
class UsuariosWhatsapp extends Entity
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
        'usuarios_id' => true,
        'whatsapps_id' => true,
        'usuario' => true,
        'whatsapp' => true,
    ];
}
