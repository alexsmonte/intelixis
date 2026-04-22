<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Procedimento Entity
 *
 * @property int $id
 * @property int $consulta_id
 * @property int $servico_id
 * @property int $profissional_id
 * @property int $quantidade
 * @property string $valor_unitario
 * @property string|null $desconto
 * @property bool|null $aprovar
 * @property bool|null $parcelar
 * @property int|null $parcelas
 * @property string|null $faces
 * @property string|null $dentes
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Consulta $consulta
 * @property \App\Model\Entity\Servico $servico
 * @property \App\Model\Entity\Profissional $profissional
 * @property \App\Model\Entity\Anexo[] $anexos
 * @property \App\Model\Entity\Documento[] $documentos
 */
class Procedimento extends Entity
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
        'consulta_id' => true,
        'servico_id' => true,
        'profissional_id' => true,
        'quantidade' => true,
        'valor_unitario' => true,
        'desconto' => true,
        'aprovar' => true,
        'parcelar' => true,
        'parcelas' => true,
        'faces' => true,
        'dentes' => true,
        'created' => true,
        'modified' => true,
        'consulta' => true,
        'servico' => true,
        'profissional' => true,
        'anexos' => true,
        'documentos' => true,
    ];
}
