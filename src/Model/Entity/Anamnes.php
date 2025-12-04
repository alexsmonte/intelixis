<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Anamnes Entity
 *
 * @property int $id
 * @property int $procedimento_id
 * @property string|null $texto
 * @property \Cake\I18n\Date|null $data_registro
 * @property string|null $audio_transcricao
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Procedimento $procedimento
 */
class Anamnes extends Entity
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
        'texto' => true,
        'data_registro' => true,
        'audio_transcricao' => true,
        'created' => true,
        'modified' => true,
        'procedimento' => true,
    ];
}
