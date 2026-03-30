<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Anexos Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 *
 * @method \App\Model\Entity\Anexo newEmptyEntity()
 * @method \App\Model\Entity\Anexo newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Anexo> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Anexo get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Anexo findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Anexo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Anexo> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Anexo|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Anexo saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Anexo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anexo>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anexo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anexo> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anexo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anexo>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Anexo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Anexo> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AnexosTable extends Table
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

        $this->setTable('anexos');
        $this->setDisplayField('entidade');
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
            ->scalar('entidade')
            ->maxLength('entidade', 255)
            ->requirePresence('entidade', 'create')
            ->notEmptyString('entidade');

        $validator
            ->integer('entidade_id')
            ->requirePresence('entidade_id', 'create')
            ->notEmptyString('entidade_id');

        $validator
            ->scalar('nome_arquivo')
            ->maxLength('nome_arquivo', 255)
            ->requirePresence('nome_arquivo', 'create')
            ->notEmptyString('nome_arquivo');

        $validator
            ->scalar('caminho_arquivo')
            ->requirePresence('caminho_arquivo', 'create')
            ->notEmptyString('caminho_arquivo');

        $validator
            ->scalar('tipo_mime')
            ->maxLength('tipo_mime', 50)
            ->allowEmptyString('tipo_mime');

        $validator
            ->integer('tamanho')
            ->allowEmptyString('tamanho');

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
