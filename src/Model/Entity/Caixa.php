<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Caixa Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property int $usuario_id
 * @property \Cake\I18n\DateTime $data_abertura
 * @property string $saldo_inicial
 * @property \Cake\I18n\DateTime|null $data_fechamento
 * @property string|null $situacao
 * @property int|null $usuario_conferiu
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Movimentacao[] $movimentacoes
 */
class Caixa extends Entity
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
        'usuario_id' => true,
        'data_abertura' => true,
        'saldo_inicial' => true,
        'saldo_final' => true,
        'data_fechamento' => true,
        'situacao' => true,
        'usuario_conferiu' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
        'usuario' => true,
        'movimentacoes' => true,
        'pagamentos' => true,
    ];
}
