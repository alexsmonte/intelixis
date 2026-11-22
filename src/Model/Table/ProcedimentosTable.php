<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Procedimentos Model
 *
 * @property \App\Model\Table\ConsultasTable&\Cake\ORM\Association\BelongsTo $Consultas
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\BelongsTo $Servicos
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsTo $Profissionais
 * @property \App\Model\Table\DocumentosTable&\Cake\ORM\Association\HasMany $Documentos
 *
 * @method \App\Model\Entity\Procedimento newEmptyEntity()
 * @method \App\Model\Entity\Procedimento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Procedimento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Procedimento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Procedimento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Procedimento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Procedimento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Procedimento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Procedimento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Procedimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Procedimento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Procedimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Procedimento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Procedimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Procedimento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Procedimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Procedimento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProcedimentosTable extends Table
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

        $this->setTable('procedimentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Consultas', [
            'foreignKey' => 'consulta_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Servicos', [
            'foreignKey' => 'servico_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Profissionais', [
            'foreignKey' => 'profissional_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Anamneses', [
            'foreignKey' => 'procedimento_id',
        ]);
        $this->hasMany('Anotacoes', [
            'foreignKey' => 'procedimento_id',
        ]);
        $this->hasMany('Documentos', [
            'foreignKey' => 'procedimento_id',
        ]);
        $this->hasMany('Financas', [
            'foreignKey' => 'procedimento_id',
        ]);
        $this->hasMany('Insumos', [
            'foreignKey' => 'procedimento_id',
        ]);

        $this->hasMany('Anexos', [
            'foreignKey' => 'entidade_id',
            'conditions' => ['Anexos.entidade' => 'Procedimentos'],
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->belongsTo('ClientesConvenios', [
            'foreignKey' => 'clientes_convenio_id',
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
            ->integer('consulta_id')
            ->notEmptyString('consulta_id');

        $validator
            ->notEmptyString('servico_id');

        $validator
            ->notEmptyString('profissional_id');

        $validator
            ->integer('quantidade')
            ->notEmptyString('quantidade');

        $validator
            ->decimal('valor_unitario')
            ->notEmptyString('valor_unitario');

        $validator
            ->decimal('desconto')
            ->allowEmptyString('desconto');

        $validator
            ->boolean('aprovar')
            ->allowEmptyString('aprovar');

        $validator
            ->boolean('parcelar')
            ->allowEmptyString('parcelar');

        $validator
            ->integer('parcelas')
            ->allowEmptyString('parcelas');

        $validator
            ->scalar('faces')
            ->maxLength('faces', 255)
            ->allowEmptyString('faces');

        $validator
            ->scalar('dentes')
            ->maxLength('dentes', 255)
            ->allowEmptyString('dentes');

        $validator
            ->integer('clientes_convenio_id')
            ->allowEmptyString('clientes_convenio_id');

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
        $rules->add($rules->existsIn(['consulta_id'], 'Consultas'), ['errorField' => 'consulta_id']);
        $rules->add($rules->existsIn(['servico_id'], 'Servicos'), ['errorField' => 'servico_id']);
        $rules->add($rules->existsIn(['profissional_id'], 'Profissionais'), ['errorField' => 'profissional_id']);

        return $rules;
    }
}
