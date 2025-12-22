<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Anotacoes Model
 *
 * @property \App\Model\Table\ProcedimentosTable&\Cake\ORM\Association\BelongsTo $Procedimentos
 *
 * @method \App\Model\Entity\Anotacao newEmptyEntity()
 * @method \App\Model\Entity\Anotacao newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Anotacao> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Anotacao get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Anotacao findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Anotacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Anotacao> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Anotacao|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Anotacao saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Anotacao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anotacao>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anotacao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anotacao> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anotacao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anotacao>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anotacao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anotacao> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AnotacoesTable extends Table
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

        $this->setTable('anotacoes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Procedimentos', [
            'foreignKey' => 'procedimento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
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
            ->integer('procedimento_id')
            ->notEmptyString('procedimento_id');

        $validator
            ->integer('usuario_id')
            ->notEmptyString('usuario_id');

        $validator
            ->scalar('texto')
            ->allowEmptyString('texto');

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
        $rules->add($rules->existsIn(['procedimento_id'], 'Procedimentos'), ['errorField' => 'procedimento_id']);
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'), ['errorField' => 'usuario_id']);

        return $rules;
    }
}
