<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FormaPagamento Entity
 *
 * @property int $id
 * @property int|null $estabelecimento_id
 * @property string $nome
 * @property int|null $tipo_taxa
 * @property string|null $taxas
 * @property string|null $taxa_antecipacao
 * @property int|null $dias
 * @property bool|null $ativar
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Movimentacao[] $movimentacoes
 * @property \App\Model\Entity\Pagamento[] $pagamentos
 */
class FormaPagamento extends Entity
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
        'tipo_taxa' => true,
        'taxas' => true,
        'taxa_antecipacao' => true,
        'dias' => true,
        'ativar' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
        'movimentacoes' => true,
        'pagamentos' => true,
    ];
}
