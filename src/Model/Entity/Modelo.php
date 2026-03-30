<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Modelo Entity
 *
 * @property int $id
 * @property int|null $estabelecimento_id
 * @property string $tipo
 * @property string|null $categoria
 * @property string $titulo
 * @property string|null $descricao
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 */
class Modelo extends Entity
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
        'tipo' => true,
        'categoria' => true,
        'titulo' => true,
        'nome' => true,
        'cabecalho' =>true,
        'descricao' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
    ];
}
