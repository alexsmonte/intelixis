<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Profissional Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property string $nome
 * @property string|null $email
 * @property string|null $telefone
 * @property string|null $celular
 * @property string|null $cep
 * @property string|null $logradouro
 * @property string|null $numero
 * @property string|null $complemento
 * @property string|null $bairro
 * @property string|null $cidade
 * @property bool|null $ativar
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\Bloqueio[] $bloqueios
 * @property \App\Model\Entity\Periodo[] $periodos
 * @property \App\Model\Entity\Convenio[] $convenios
 * @property \App\Model\Entity\Servico[] $servicos
 */
class Profissional extends Entity
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
        'email' => true,
        'telefone' => true,
        'celular' => true,
        'cep' => true,
        'logradouro' => true,
        'numero' => true,
        'complemento' => true,
        'bairro' => true,
        'cidade' => true,
        'uf' => true,
        'ativar' => true,
        'habilitar_programacao' => true,
        'data_nascimento' => true,
        'conselho' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
        'agendamentos' => true,
        'bloqueios' => true,
        'periodos' => true,
        'convenios' => true,
        'servicos' => true,
    ];
}
