<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Movimentacoes Model
 *
 * @property \App\Model\Table\CaixasTable&\Cake\ORM\Association\BelongsTo $Caixas
 * @property \App\Model\Table\FormaPagamentosTable&\Cake\ORM\Association\BelongsTo $FormaPagamentos
 *
 * @method \App\Model\Entity\Movimentacao newEmptyEntity()
 * @method \App\Model\Entity\Movimentacao newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Movimentacao> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Movimentacao get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Movimentacao findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Movimentacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Movimentacao> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Movimentacao|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Movimentacao saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Movimentacao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Movimentacao>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Movimentacao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Movimentacao> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Movimentacao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Movimentacao>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Movimentacao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Movimentacao> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MovimentacoesTable extends Table
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

        $this->setTable('movimentacoes');
        $this->setDisplayField('situacao');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Caixas', [
            'foreignKey' => 'caixa_id',
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
            ->integer('caixa_id')
            ->notEmptyString('caixa_id');

        $validator
            ->integer('forma_pagamento_id')
            ->notEmptyString('forma_pagamento_id');

        $validator
            ->decimal('valor_apurado')
            ->allowEmptyString('valor_apurado');

        $validator
            ->scalar('situacao')
            ->requirePresence('situacao', 'create')
            ->notEmptyString('situacao');

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
        $rules->add($rules->existsIn(['caixa_id'], 'Caixas'), ['errorField' => 'caixa_id']);
        $rules->add($rules->existsIn(['forma_pagamento_id'], 'FormaPagamentos'), ['errorField' => 'forma_pagamento_id']);

        return $rules;
    }
}
