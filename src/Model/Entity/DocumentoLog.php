<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DocumentoLog Entity
 *
 * @property int $id
 * @property int $documento_id
 * @property int|null $usuario_id
 * @property string $acao
 * @property string|null $hash_antes
 * @property string|null $hash_depois
 * @property string $ip
 * @property array|null $detalhes
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\Documento $documento
 * @property \App\Model\Entity\Usuario $usuario
 */
class DocumentoLog extends Entity
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
        'documento_id' => true,
        'usuario_id' => true,
        'acao' => true,
        'hash_antes' => true,
        'hash_depois' => true,
        'ip' => true,
        'detalhes' => true,
        'created' => true,
        'documento' => true,
        'usuario' => true,
    ];
}
