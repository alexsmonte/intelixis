<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Conta Entity
 *
 * @property int $id
 * @property string $cpf_cnpj
 * @property string $nome
 * @property string|null $razao_social
 * @property string $email
 * @property string|null $logradouro
 * @property string|null $cep
 * @property string|null $bairro
 * @property string|null $cidade
 * @property string|null $uf
 * @property string|null $complemento
 * @property bool|null $ativar
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Assinatura[] $assinaturas
 * @property \App\Model\Entity\Usuario[] $usuarios
 * @property \App\Model\Entity\Whatsapp[] $whatsapps
 * @property \App\Model\Entity\Extenso[] $extensoes
 */
class Conta extends Entity
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
        'cpf_cnpj' => true,
        'nome' => true,
        'razao_social' => true,
        'email' => true,
        'logradouro' => true,
        'cep' => true,
        'bairro' => true,
        'cidade' => true,
        'uf' => true,
        'complemento' => true,
        'ativar' => true,
        'created' => true,
        'modified' => true,
        'assinaturas' => true,
        'usuarios' => true,
        'whatsapps' => true,
        'extensoes' => true,
    ];
}
