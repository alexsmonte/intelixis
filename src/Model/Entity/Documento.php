<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Documento Entity
 *
 * @property int $id
 * @property int $procedimento_id
 * @property int|null $estabelecimento_id
 * @property int|null $usuario_id
 * @property int|null $profissional_id
 * @property string $tipo
 * @property string|null $texto
 * @property string|null $audio_transcricao
 * @property string|null $audio_path
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Procedimento $procedimento
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Profissional $profissional
 */
class Documento extends Entity
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
        'procedimento_id' => true,
        'estabelecimento_id' => true,
        'usuario_id' => true,
        'profissional_id' => true,
        'tipo' => true,
        'titulo' => true,
        'cabecalho' => true,
        'codigo_validacao' => true,
        'texto' => true,
        'audio_transcricao' => true,
        'audio_path' => true,
        'created' => true,
        'modified' => true,
        'procedimento' => true,
        'estabelecimento' => true,
        'usuario' => true,
        'profissional' => true,
    ];
}
