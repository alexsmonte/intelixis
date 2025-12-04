<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pagamentos Model
 *
 * @property \App\Model\Table\FinancasTable&\Cake\ORM\Association\BelongsTo $Financas
 * @property \App\Model\Table\FormaPagamentosTable&\Cake\ORM\Association\BelongsTo $FormaPagamentos
 *
 * @method \App\Model\Entity\Pagamento newEmptyEntity()
 * @method \App\Model\Entity\Pagamento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Pagamento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pagamento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Pagamento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Pagamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Pagamento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pagamento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Pagamento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Pagamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pagamento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pagamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pagamento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pagamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pagamento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pagamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pagamento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PagamentosTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('pagamentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Financas', [
            'foreignKey' => 'financa_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FormaPagamentos', [
            'foreignKey' => 'forma_pagamento_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('financa_id');

        $validator
            ->integer('forma_pagamento_id')
            ->notEmptyString('forma_pagamento_id');

        $validator
            ->decimal('valor')
            ->requirePresence('valor', 'create')
            ->notEmptyString('valor');

        $validator
            ->decimal('taxa')
            ->allowEmptyString('taxa');

        $validator
            ->boolean('antecipar')
            ->allowEmptyString('antecipar');

        $validator
            ->date('data_antecipacao')
            ->allowEmptyDate('data_antecipacao');

        $validator
            ->integer('usuario_recebeu')
            ->allowEmptyString('usuario_recebeu');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['financa_id'], 'Financas'), ['errorField' => 'financa_id']);
        $rules->add($rules->existsIn(['forma_pagamento_id'], 'FormaPagamentos'), ['errorField' => 'forma_pagamento_id']);

        return $rules;
    }
}
