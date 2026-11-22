<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ClientesConveniosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('clientes_convenios');
        $this->setDisplayField('numero_carteirinha');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Clientes', [
            'foreignKey' => 'cliente_id',
        ]);
        $this->belongsTo('Convenios', [
            'foreignKey' => 'convenio_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('convenio_id')
            ->notEmptyString('convenio_id', 'Selecione um convênio.');

        $validator
            ->scalar('numero_carteirinha')
            ->maxLength('numero_carteirinha', 50)
            ->requirePresence('numero_carteirinha', 'create')
            ->notEmptyString('numero_carteirinha', 'O número da carteirinha é obrigatório.');

        $validator
            ->scalar('nome_titular')
            ->maxLength('nome_titular', 255)
            ->requirePresence('nome_titular', 'create')
            ->notEmptyString('nome_titular', 'O nome do titular é obrigatório.');

        $validator
            ->scalar('cpf_titular')
            ->add('cpf_titular', 'validLength', [
                'rule' => ['custom', '/^\d{11}$/'],
                'message' => 'O CPF do titular deve conter 11 dígitos.',
            ])
            ->requirePresence('cpf_titular', 'create')
            ->notEmptyString('cpf_titular', 'O CPF do titular é obrigatório.');

        $validator
            ->boolean('is_titular')
            ->allowEmptyString('is_titular');

        $validator
            ->scalar('grau_dependencia')
            ->maxLength('grau_dependencia', 50)
            ->allowEmptyString('grau_dependencia');

        $validator
            ->scalar('plano')
            ->maxLength('plano', 100)
            ->allowEmptyString('plano');

        $validator
            ->date('data_inicio')
            ->allowEmptyDate('data_inicio');

        $validator
            ->date('data_validade')
            ->allowEmptyDate('data_validade');

        $validator
            ->scalar('acomodacao')
            ->allowEmptyString('acomodacao')
            ->inList('acomodacao', ['ENFERMARIA', 'QUARTO_INDIVIDUAL'], 'Acomodação inválida.');

        $validator
            ->scalar('abrangencia')
            ->allowEmptyString('abrangencia')
            ->inList('abrangencia', ['MUNICIPAL', 'ESTADUAL', 'NACIONAL', 'INTERNACIONAL'], 'Abrangência inválida.');

        $validator
            ->scalar('status')
            ->inList('status', ['ATIVO', 'SUSPENSO', 'CANCELADO'], 'Status inválido.')
            ->notEmptyString('status');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        return $validator;
    }

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options): void
    {
        $caracterInvalido = [' ', '.', '-', '/', ')', '(', ','];

        if (isset($data['cpf_titular'])) {
            $data['cpf_titular'] = str_replace($caracterInvalido, '', $data['cpf_titular']);
        }
    }
}
