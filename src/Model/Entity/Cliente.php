<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cliente Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property string $nome
 * @property string $documento
 * @property string|null $email
 * @property string|null $telefone
 * @property string|null $celular
 * @property string|null $cep
 * @property string|null $endereco
 * @property string|null $cidade
 * @property string|null $bairro
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 */
class Cliente extends Entity
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
        'documento' => true,
        'email' => true,
        'telefone' => true,
        'celular' => true,
        'cep' => true,
        'endereco' => true,
        'cidade' => true,
        'bairro' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
        'agendamentos' => true,
    ];
}
