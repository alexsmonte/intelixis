<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Servico Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property string $nome
 * @property string|null $descricao
 * @property int $duracao
 * @property bool|null $ativar
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property string|null $valor
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\Bloqueio[] $bloqueios
 * @property \App\Model\Entity\Periodo[] $periodos
 * @property \App\Model\Entity\Convenio[] $convenios
 * @property \App\Model\Entity\Profissional[] $profissionais
 */
class Servico extends Entity
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
        'nome' => true,
        'descricao' => true,
        'duracao' => true,
        'ativar' => true,
        'created' => true,
        'modified' => true,
        'valor' => true,
        'estabelecimento' => true,
        'agendamentos' => true,
        'bloqueios' => true,
        'periodos' => true,
        'convenios' => true,
        'profissionais' => true,
    ];
}
