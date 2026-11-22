<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClienteConvenio Entity
 *
 * @property int $id
 * @property int $cliente_id
 * @property int $convenio_id
 * @property string $numero_carteirinha
 * @property string $nome_titular
 * @property string $cpf_titular
 * @property bool $is_titular
 * @property string|null $grau_dependencia
 * @property string|null $plano
 * @property \Cake\I18n\Date|null $data_inicio
 * @property \Cake\I18n\Date|null $data_validade
 * @property string|null $acomodacao
 * @property string|null $abrangencia
 * @property string $status
 * @property string|null $observacoes
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Cliente $cliente
 * @property \App\Model\Entity\Convenio $convenio
 */
class ClienteConvenio extends Entity
{
    protected array $_accessible = [
        'cliente_id' => true,
        'convenio_id' => true,
        'numero_carteirinha' => true,
        'nome_titular' => true,
        'cpf_titular' => true,
        'is_titular' => true,
        'grau_dependencia' => true,
        'plano' => true,
        'data_inicio' => true,
        'data_validade' => true,
        'acomodacao' => true,
        'abrangencia' => true,
        'status' => true,
        'observacoes' => true,
        'created' => true,
        'modified' => true,
        'cliente' => true,
        'convenio' => true,
    ];
}
