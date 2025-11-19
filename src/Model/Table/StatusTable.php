<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Status Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 *
 * @method \App\Model\Entity\Status newEmptyEntity()
 * @method \App\Model\Entity\Status newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Status> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Status get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Status findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Status patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Status> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Status|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Status saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Status>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Status>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Status>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Status> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Status>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Status>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Status>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Status> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatusTable extends Table
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

        $this->setTable('status');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
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
            ->notEmptyString('estabelecimento_id');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 30)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->boolean('ativar')
            ->requirePresence('ativar', 'create')
            ->notEmptyString('ativar');

        $validator
            ->scalar('cor')
            ->maxLength('cor', 10)
            ->allowEmptyString('cor');

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
        $rules->add($rules->isUnique(['id']), ['errorField' => 'id']);
        $rules->add($rules->existsIn(['estabelecimento_id'], 'Estabelecimentos'), ['errorField' => 'estabelecimento_id']);

        return $rules;
    }

    public function findOrdenar(SelectQuery $query)
    {
        return $query->orderBy(['Status.id' => 'ASC']);
    }
}
