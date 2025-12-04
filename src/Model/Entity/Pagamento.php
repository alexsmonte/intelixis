<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pagamento Entity
 *
 * @property int $id
 * @property int $financa_id
 * @property int $forma_pagamento_id
 * @property string $valor
 * @property string|null $taxa
 * @property bool|null $antecipar
 * @property \Cake\I18n\Date|null $data_antecipacao
 * @property int|null $usuario_recebeu
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Financa $financa
 * @property \App\Model\Entity\FormaPagamento $forma_pagamento
 */
class Pagamento extends Entity
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
        'financa_id' => true,
        'forma_pagamento_id' => true,
        'valor' => true,
        'taxa' => true,
        'antecipar' => true,
        'data_antecipacao' => true,
        'usuario_recebeu' => true,
        'created' => true,
        'modified' => true,
        'financa' => true,
        'forma_pagamento' => true,
    ];
}
