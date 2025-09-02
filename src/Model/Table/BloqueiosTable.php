<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bloqueios Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsTo $Profissionais
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\BelongsTo $Servicos
 * @property \App\Model\Table\LocaisTable&\Cake\ORM\Association\BelongsTo $Locais
 *
 * @method \App\Model\Entity\Bloqueio newEmptyEntity()
 * @method \App\Model\Entity\Bloqueio newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Bloqueio> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bloqueio get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Bloqueio findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Bloqueio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Bloqueio> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bloqueio|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Bloqueio saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Bloqueio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Bloqueio>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Bloqueio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Bloqueio> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Bloqueio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Bloqueio>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Bloqueio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Bloqueio> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BloqueiosTable extends Table
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

        $this->setTable('bloqueios');
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
            ->date('data_inicio')
            ->requirePresence('data_inicio', 'create')
            ->notEmptyDate('data_inicio');

        $validator
            ->date('data_fim')
            ->requirePresence('data_fim', 'create')
            ->notEmptyDate('data_fim');

        $validator
            ->boolean('dia_inteiro')
            ->notEmptyString('dia_inteiro');

        $validator
            ->allowEmptyString('hora_inicio');

        $validator
            ->allowEmptyString('hora_fim');

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

        return $rules;
    }
}
