<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Orcamento Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property int $usuario_id
 * @property int $cliente_id
 * @property int|null $consulta_id
 * @property \Cake\I18n\Date|null $validade
 * @property string $status
 * @property string $valor_total
 * @property string|null $desconto
 * @property string|null $observacoes
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Cliente $cliente
 * @property \App\Model\Entity\Consulta $consulta
 * @property \App\Model\Entity\Servico[] $servicos
 */
class Orcamento extends Entity
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
    '*' => true,
    'id' => false
];
}


