<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Periodos Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsTo $Profissionais
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\BelongsTo $Servicos
 * @property \App\Model\Table\LocaisTable&\Cake\ORM\Association\BelongsTo $Locais
 * @property \App\Model\Table\ConveniosTable&\Cake\ORM\Association\BelongsTo $Convenios
 *
 * @method \App\Model\Entity\Periodo newEmptyEntity()
 * @method \App\Model\Entity\Periodo newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Periodo> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Periodo get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Periodo findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Periodo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Periodo> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Periodo|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Periodo saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Periodo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Periodo>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Periodo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Periodo> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Periodo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Periodo>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Periodo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Periodo> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PeriodosTable extends Table
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

        $this->setTable('periodos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Profissionais', [
            'foreignKey' => 'profissional_id',
        ]);
        $this->belongsTo('Servicos', [
            'foreignKey' => 'servico_id',
        ]);
        $this->belongsTo('Locais', [
            'foreignKey' => 'local_id',
        ]);
        $this->belongsTo('Convenios', [
            'foreignKey' => 'convenio_id',
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
            ->allowEmptyString('profissional_id');

        $validator
            ->allowEmptyString('servico_id');

        $validator
            ->allowEmptyString('local_id');

        $validator
            ->integer('convenio_id')
            ->allowEmptyString('convenio_id');

        $validator
            ->dateTime('data_hora_inicio',['dmy'])
            ->requirePresence('data_hora_inicio', 'create')
            ->notEmptyDateTime('data_hora_inicio');

        $validator
            ->dateTime('data_hora_fim', ['dmy'])
            ->requirePresence('data_hora_fim', 'create')
            ->notEmptyDateTime('data_hora_fim');

        $validator
            ->requirePresence('dia_semana', 'create')
            ->notEmptyString('dia_semana');

        $validator
            ->dateTime('data_personalizada', ['dmy'])
            ->allowEmptyDateTime('data_personalizada');

        $validator
            ->scalar('cadeia_id')
            ->maxLength('cadeia_id', 20)
            ->allowEmptyString('cadeia_id');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

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
        $rules->add($rules->existsIn(['profissional_id'], 'Profissionais'), ['errorField' => 'profissional_id']);
        $rules->add($rules->existsIn(['servico_id'], 'Servicos'), ['errorField' => 'servico_id']);
        $rules->add($rules->existsIn(['local_id'], 'Locais'), ['errorField' => 'local_id']);
        $rules->add($rules->existsIn(['convenio_id'], 'Convenios'), ['errorField' => 'convenio_id']);

        return $rules;
    }
}
