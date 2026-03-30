<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Financa Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property int|null $ccustos_id
 * @property int|null $profissional_id
 * @property int|null $cliente_id
 * @property int|null $procedimento_id
 * @property int|null $tipo
 * @property string $descricao
 * @property \Cake\I18n\Date $data_vencimento
 * @property string $valor
 * @property string|null $desconto
 * @property string|null $data_baixa
 * @property string|null $valor_recebido
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Ccusto $ccustos
 * @property \App\Model\Entity\Profissional $profissional
 * @property \App\Model\Entity\Cliente $cliente
 * @property \App\Model\Entity\Procedimento $procedimento
 * @property \App\Model\Entity\Pagamento[] $pagamentos
 */
class Financa extends Entity
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
        'ccustos_id' => true,
        'profissional_id' => true,
        'cliente_id' => true,
        'procedimento_id' => true,
        'tipo' => true,
        'descricao' => true,
        'data_vencimento' => true,
        'valor' => true,
        'desconto' => true,
        'data_baixa' => true,
        'valor_recebido' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
        'ccustos' => true,
        'profissional' => true,
        'cliente' => true,
        'procedimento' => true,
        'pagamentos' => true,
    ];
}
