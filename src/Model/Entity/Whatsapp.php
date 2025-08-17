<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Whatsapp Entity
 *
 * @property int $id
 * @property int $conta_id
 * @property int $estabelecimento_id
 * @property string $phone_number
 * @property string $business_account
 * @property string $display_phone_number
 * @property string|null $access_token
 * @property string|null $nome
 * @property string|null $pin
 * @property string|null $senha_chatwoot
 * @property string|null $token_empresa
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Conta $conta
 * @property \App\Model\Entity\Assinatura $assinatura
 * @property \App\Model\Entity\Usuario[] $usuarios
 */
class Whatsapp extends Entity
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
        'estabelecimento_id' => true,
        'phone_number' => true,
        'business_account' => true,
        'display_phone_number' => true,
        'access_token' => true,
        'nome' => true,
        'pin' => true,
        'senha_chatwoot' => true,
        'token_empresa' => true,
        'created' => true,
        'modified' => true,
        'conta' => true,
        'assinatura' => true,
        'usuarios' => true,
    ];
}
