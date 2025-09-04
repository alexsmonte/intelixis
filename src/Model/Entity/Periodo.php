<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Periodo Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property int|null $profissional_id
 * @property int|null $servico_id
 * @property int|null $local_id
 * @property int|null $convenio_id
 * @property \Cake\I18n\DateTime $data_hora_inicio
 * @property \Cake\I18n\DateTime $data_hora_fim
 * @property int $dia_semana
 * @property \Cake\I18n\DateTime|null $data_personalizada
 * @property string|null $cadeia_id
 * @property bool $status
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Profissional $profissional
 * @property \App\Model\Entity\Servico $servico
 * @property \App\Model\Entity\Local $local
 * @property \App\Model\Entity\Convenio $convenio
 */
class Periodo extends Entity
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
        'profissional_id' => true,
        'servico_id' => true,
        'local_id' => true,
        'convenio_id' => true,
        'data_hora_inicio' => true,
        'data_hora_fim' => true,
        'dia_semana' => true,
        'data_personalizada' => true,
        'cadeia_id' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
        'profissional' => true,
        'servico' => true,
        'local' => true,
        'convenio' => true,
    ];
}
