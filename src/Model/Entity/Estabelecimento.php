<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Estabelecimento Entity
 *
 * @property int $id
 * @property int $conta_id
 * @property int $assinatura_id
 * @property string $razao_social
 * @property string $nome_fantasia
 * @property string $cpf_cnpj
 * @property string|null $logradouro
 * @property string|null $numero
 * @property string|null $complemento
 * @property string $email
 * @property string|null $telefone
 * @property string|null $celular
 * @property bool|null $ativar
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Conta $conta
 * @property \App\Model\Entity\Assinatura $assinatura
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\Bloqueio[] $bloqueios
 * @property \App\Model\Entity\Cliente[] $clientes
 * @property \App\Model\Entity\Convenio[] $convenios
 * @property \App\Model\Entity\Local[] $locais
 * @property \App\Model\Entity\Periodo[] $periodos
 * @property \App\Model\Entity\Profissional[] $profissionais
 * @property \App\Model\Entity\Servico[] $servicos
 * @property \App\Model\Entity\Status[] $status
 * @property \App\Model\Entity\Whatsapp[] $whatsapps
 * @property \App\Model\Entity\Extensao[] $extensoes
 * @property \App\Model\Entity\Usuario[] $usuarios
 */
class Estabelecimento extends Entity
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
        'assinatura_id' => true,
        'razao_social' => true,
        'logo' => true,
        'nome_fantasia' => true,
        'cpf_cnpj' => true,
        'cep' => true,
        'logradouro' => true,
        'cidade' => true,
        'uf' => true,
        'bairro' => true,
        'numero' => true,
        'complemento' => true,
        'email' => true,
        'telefone' => true,
        'telefone_opcional' => true,
        'celular' => true,
        'ativar' => true,
        'habilitar_programacao' => true,
        'intervalo_atendimento'=> true,
        'retorno_atendimento'=> true,
        'created' => true,
        'modified' => true,
        'conta' => true,
        'assinatura' => true,
        'agendamentos' => true,
        'bloqueios' => true,
        'clientes' => true,
        'convenios' => true,
        'locais' => true,
        'periodos' => true,
        'profissionais' => true,
        'servicos' => true,
        'status' => true,
        'whatsapps' => true,
        'extensoes' => true,
        'usuarios' => true,
    ];
}
