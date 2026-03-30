<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Anexo Entity
 *
 * @property int $id
 * @property int $estabelecimento_id
 * @property string $entidade
 * @property int $entidade_id
 * @property string $nome_arquivo
 * @property string $caminho_arquivo
 * @property string|null $tipo_mime
 * @property int|null $tamanho
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Estabelecimento $estabelecimento
 */
class Anexo extends Entity
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
        'entidade' => true,
        'entidade_id' => true,
        'nome_arquivo' => true,
        'caminho_arquivo' => true,
        'tipo_mime' => true,
        'tamanho' => true,
        'created' => true,
        'modified' => true,
        'estabelecimento' => true,
    ];
}
