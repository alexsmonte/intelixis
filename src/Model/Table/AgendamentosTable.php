<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Agendamentos Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\ClientesTable&\Cake\ORM\Association\BelongsTo $Clientes
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\BelongsTo $Servicos
 * @property \App\Model\Table\StatusTable&\Cake\ORM\Association\BelongsTo $Status
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsTo $Profissionais
 * @property \App\Model\Table\LocaisTable&\Cake\ORM\Association\BelongsTo $Locais
 * @property \App\Model\Table\ConveniosTable&\Cake\ORM\Association\BelongsTo $Convenios
 *
 * @method \App\Model\Entity\Agendamento newEmptyEntity()
 * @method \App\Model\Entity\Agendamento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Agendamento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agendamento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Agendamento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Agendamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Agendamento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agendamento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Agendamento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Agendamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Agendamento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Agendamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Agendamento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Agendamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Agendamento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Agendamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Agendamento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AgendamentosTable extends Table
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

        $this->setTable('agendamentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Clientes', [
            'foreignKey' => 'cliente_id',
        ]);
        $this->belongsTo('Servicos', [
            'foreignKey' => 'servico_id',
        ]);
        $this->belongsTo('Status', [
            'foreignKey' => 'statu_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Profissionais', [
            'foreignKey' => 'profissional_id',
        ]);
        $this->belongsTo('Locais', [
            'foreignKey' => 'local_id',
        ]);
        $this->belongsTo('Convenios', [
            'foreignKey' => 'convenio_id',
        ]);
    }

    public function validationAgendar(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('estabelecimento_id');

        $validator
            ->notEmptyString('titulo','Informe um cliente');

        $validator
            ->allowEmptyString('cliente_id');

        $validator
            ->notEmptyString('servico_id');

        $validator
            ->notEmptyString('statu_id');

        $validator
            ->allowEmptyString('profissional_id');

        $validator
            ->allowEmptyString('local_id');

        $validator
            ->integer('convenio_id')
            ->allowEmptyString('convenio_id');

        $validator
            ->date('data_inicio',['ymd'],'Informe uma data válida')
            ->requirePresence('data_inicio')
            ->notEmptyDateTime('data_inicio','Informe uma data válida');

        $validator
            ->requirePresence('hora_inicio')
            ->notEmptyString('hora_inicio', 'Informe a hora')
            ->add('hora_inicio', 'formato', [
                'rule' => ['custom', '/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
                'message' => 'Hora inválida',
            ]);

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        return $validator;

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
            ->allowEmptyString('cliente_id');

        $validator
            ->allowEmptyString('servico_id');

        $validator
            ->notEmptyString('statu_id');

        $validator
            ->allowEmptyString('profissional_id');

        $validator
            ->allowEmptyString('local_id');

        $validator
            ->integer('convenio_id')
            ->allowEmptyString('convenio_id');

        $validator
            ->dateTime('data_hora_inicio',['dmy'],'Informe uma data válida')
            ->requirePresence('data_hora_inicio', 'create')
            ->notEmptyDateTime('data_hora_inicio');

        $validator
            ->dateTime('data_hora_fim',['dmy'],'Informe uma data válida')
            ->requirePresence('data_hora_fim', 'create')
            ->notEmptyDateTime('data_hora_fim');

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
        $rules->add($rules->existsIn(['cliente_id'], 'Clientes'), ['errorField' => 'cliente_id']);
        $rules->add($rules->existsIn(['servico_id'], 'Servicos'), ['errorField' => 'servico_id']);
        $rules->add($rules->existsIn(['statu_id'], 'Status'), ['errorField' => 'statu_id']);
        $rules->add($rules->existsIn(['profissional_id'], 'Profissionais'), ['errorField' => 'profissional_id']);
        $rules->add($rules->existsIn(['local_id'], 'Locais'), ['errorField' => 'local_id']);
        $rules->add($rules->existsIn(['convenio_id'], 'Convenios'), ['errorField' => 'convenio_id']);

        return $rules;
    }
}
