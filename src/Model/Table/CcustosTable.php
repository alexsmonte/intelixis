<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ccustos Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\CcustosTable&\Cake\ORM\Association\BelongsTo $ParentCcustos
 * @property \App\Model\Table\CcustosTable&\Cake\ORM\Association\HasMany $ChildCcustos
 *
 * @method \App\Model\Entity\Ccusto newEmptyEntity()
 * @method \App\Model\Entity\Ccusto newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Ccusto> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ccusto get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Ccusto findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Ccusto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Ccusto> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ccusto|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Ccusto saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Ccusto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Ccusto>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Ccusto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Ccusto> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Ccusto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Ccusto>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Ccusto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Ccusto> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class CcustosTable extends Table
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

        $this->setTable('ccustos');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->belongsTo('ParentCcustos', [
            'className' => 'Ccustos',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildCcustos', [
            'className' => 'Ccustos',
            'foreignKey' => 'parent_id',
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
            ->allowEmptyString('estabelecimento_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->allowEmptyString('name');

        $validator
            ->integer('parent_id')
            ->allowEmptyString('parent_id');

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
        $rules->add($rules->existsIn(['estabelecimento_id'], 'Estabelecimentos'), ['errorField' => 'estabelecimento_id']);
        $rules->add($rules->existsIn(['parent_id'], 'ParentCcustos'), ['errorField' => 'parent_id']);

        return $rules;
    }
}
