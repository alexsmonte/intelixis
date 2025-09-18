<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Servicos Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\AgendamentosTable&\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\BloqueiosTable&\Cake\ORM\Association\HasMany $Bloqueios
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\HasMany $Periodos
 * @property \App\Model\Table\ConveniosTable&\Cake\ORM\Association\BelongsToMany $Convenios
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsToMany $Profissionais
 *
 * @method \App\Model\Entity\Servico newEmptyEntity()
 * @method \App\Model\Entity\Servico newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Servico> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Servico get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Servico findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Servico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Servico> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Servico|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Servico saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Servico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Servico>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Servico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Servico> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Servico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Servico>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Servico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Servico> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicosTable extends Table
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

        $this->setTable('servicos');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'servico_id',
        ]);
        $this->hasMany('Bloqueios', [
            'foreignKey' => 'servico_id',
        ]);
        //$this->hasMany('Periodos', [
          //  'foreignKey' => 'servico_id',
        //]);
        $this->hasMany('Periodos', [
            'foreignKey' => 'servico_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->belongsToMany('Convenios', [
            'foreignKey' => 'servico_id',
            'targetForeignKey' => 'convenio_id',
            'joinTable' => 'convenios_servicos',
        ]);
        $this->belongsToMany('Profissionais', [
            'foreignKey' => 'servico_id',
            'targetForeignKey' => 'profissional_id',
            'joinTable' => 'profissionais_servicos',
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
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome', __('O nome é obrigatório.'));
           

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->numeric('valor')
            ->allowEmptyString('valor')
            ->add('valor', 'decimal', [
                'rule' => function ($value, $context) {
                    return is_float($value) || filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
                },
                'message' => 'Por favor, insira um valor válido.'
            ]);

        $validator
            ->requirePresence('duracao', 'create')
            ->notEmptyString('duracao',__('Informe a duração do serviço!'));
        

        $validator
            ->boolean('ativar')
            ->allowEmptyString('ativar');

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
