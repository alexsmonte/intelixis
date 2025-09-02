<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Convenio Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property string $nome
 * @property string|null $registro_ans
 * @property string|null $cnpj
 * @property string|null $telefone
 * @property string|null $email
 * @property string|null $site
 * @property bool|null $ativar
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\Periodo[] $periodos
 * @property \App\Model\Entity\Profissional[] $profissionais
 * @property \App\Model\Entity\Servico[] $servicos
 */
class Convenio extends Entity
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
        'registro_ans' => true,
        'cnpj' => true,
        'telefone' => true,
        'email' => true,
        'site' => true,
        'ativar' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
        'agendamentos' => true,
        'periodos' => true,
        'profissionais' => true,
        'servicos' => true,
    ];
}
