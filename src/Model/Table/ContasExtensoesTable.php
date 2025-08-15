<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ContasExtensoes Model
 *
 * @property \App\Model\Table\ContasTable&\Cake\ORM\Association\BelongsTo $Contas
 * @property \App\Model\Table\ExtensoesTable&\Cake\ORM\Association\BelongsTo $Extensaos
 *
 * @method \App\Model\Entity\ContasExtenso newEmptyEntity()
 * @method \App\Model\Entity\ContasExtenso newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ContasExtenso> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ContasExtenso get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ContasExtenso findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ContasExtenso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ContasExtenso> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ContasExtenso|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ContasExtenso saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ContasExtenso>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContasExtenso>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContasExtenso>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContasExtenso> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContasExtenso>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContasExtenso>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContasExtenso>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContasExtenso> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ContasExtensoesTable extends Table
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

        $this->setTable('contas_extensoes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Contas', [
            'foreignKey' => 'conta_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Extensaos', [
            'foreignKey' => 'extensao_id',
            'className' => 'Extensoes',
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
            ->integer('conta_id')
            ->notEmptyString('conta_id');

        $validator
            ->integer('extensao_id')
            ->notEmptyString('extensao_id');

        $validator
            ->dateTime('data_ativacao')
            ->allowEmptyDateTime('data_ativacao');

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
        $rules->add($rules->existsIn(['conta_id'], 'Contas'), ['errorField' => 'conta_id']);
        $rules->add($rules->existsIn(['extensao_id'], 'Extensaos'), ['errorField' => 'extensao_id']);

        return $rules;
    }
}
