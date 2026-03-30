<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Anamneses Model
 *
 * @property \App\Model\Table\ProcedimentosTable&\Cake\ORM\Association\BelongsTo $Procedimentos
 *
 * @method \App\Model\Entity\Anamnes newEmptyEntity()
 * @method \App\Model\Entity\Anamnes newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Anamnes> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Anamnes get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Anamnes findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Anamnes patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Anamnes> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Anamnes|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Anamnes saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Anamnes>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anamnes>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anamnes>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anamnes> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anamnes>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anamnes>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anamnes>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anamnes> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AnamnesesTable extends Table
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

        $this->setTable('anamneses');
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

        $validator
            ->scalar('audio_transcricao')
            ->allowEmptyString('audio_transcricao');

        $validator
            ->scalar('audio_path')
            ->maxLength('audio_path', 255)
            ->allowEmptyString('audio_path');

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
