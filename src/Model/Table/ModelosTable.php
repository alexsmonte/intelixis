<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Modelos Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 *
 * @method \App\Model\Entity\Modelo newEmptyEntity()
 * @method \App\Model\Entity\Modelo newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Modelo> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Modelo get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Modelo findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Modelo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Modelo> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Modelo|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Modelo saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Modelo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Modelo>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Modelo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Modelo> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Modelo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Modelo>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Modelo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Modelo> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ModelosTable extends Table
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

        $this->setTable('modelos');
        $this->setDisplayField('tipo');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
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
            ->scalar('tipo')
            ->maxLength('tipo', 30)
            ->requirePresence('tipo', 'create')
            ->notEmptyString('tipo');

        $validator
            ->scalar('categoria')
            ->maxLength('categoria', 40)
            ->allowEmptyString('categoria');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 60)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 80)
            ->requirePresence('nome', 'create','Informe o nome do modelo')
            ->notEmptyString('nome','Você deve informa o nome do modelo');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

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

        return $rules;
    }
}
