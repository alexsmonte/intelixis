<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EstabelecimentosExtensoes Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\ExtensoesTable&\Cake\ORM\Association\BelongsTo $Extensoes
 *
 * @method \App\Model\Entity\EstabelecimentosExtensao newEmptyEntity()
 * @method \App\Model\Entity\EstabelecimentosExtensao newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\EstabelecimentosExtensao> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EstabelecimentosExtensao get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\EstabelecimentosExtensao findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\EstabelecimentosExtensao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\EstabelecimentosExtensao> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EstabelecimentosExtensao|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\EstabelecimentosExtensao saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\EstabelecimentosExtensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EstabelecimentosExtensao>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EstabelecimentosExtensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EstabelecimentosExtensao> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EstabelecimentosExtensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EstabelecimentosExtensao>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EstabelecimentosExtensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EstabelecimentosExtensao> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EstabelecimentosExtensoesTable extends Table
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

        $this->setTable('estabelecimentos_extensoes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Extensoes', [
            'foreignKey' => 'extensao_id',
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
            ->integer('extensao_id')
            ->notEmptyString('extensao_id');

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
        $rules->add($rules->existsIn(['extensao_id'], 'Extensoes'), ['errorField' => 'extensao_id']);

        return $rules;
    }
}
