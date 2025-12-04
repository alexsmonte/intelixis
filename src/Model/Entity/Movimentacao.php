<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Movimentacao Entity
 *
 * @property int $id
 * @property int $caixa_id
 * @property int $forma_pagamento_id
 * @property string|null $valor_apurado
 * @property string $situacao
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Caixa $caixa
 * @property \App\Model\Entity\FormaPagamento $forma_pagamento
 */
class Movimentacao extends Entity
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
        'caixa_id' => true,
        'forma_pagamento_id' => true,
        'valor_apurado' => true,
        'situacao' => true,
        'created' => true,
        'modified' => true,
        'caixa' => true,
        'forma_pagamento' => true,
    ];
}
