<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


/**
 * Convenios Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\AgendamentosTable&\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\HasMany $Periodos
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsToMany $Profissionais
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\BelongsToMany $Servicos
 *
 * @method \App\Model\Entity\Convenio newEmptyEntity()
 * @method \App\Model\Entity\Convenio newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Convenio> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Convenio get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Convenio findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Convenio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Convenio> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Convenio|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Convenio saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Convenio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Convenio>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Convenio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Convenio> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Convenio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Convenio>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Convenio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Convenio> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConveniosTable extends Table
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

        $this->setTable('convenios');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'convenio_id',
        ]);
        /*$this->hasMany('Periodos', [
            'foreignKey' => 'convenio_id',
        ]);*/
        $this->hasMany('Periodos', [
            'foreignKey' => 'convenio_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->belongsToMany('Profissionais', [
            'foreignKey' => 'convenio_id',
            'targetForeignKey' => 'profissional_id',
            'joinTable' => 'convenios_profissionais',
        ]);
        $this->belongsToMany('Servicos', [
            'foreignKey' => 'convenio_id',
            'targetForeignKey' => 'servico_id',
            'joinTable' => 'convenios_servicos',
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
            ->maxLength('nome', 150)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome', __('O nome do convênio é obrigatório.'));

        $validator
            ->scalar('registro_ans')
            ->maxLength('registro_ans', 50)
            ->allowEmptyString('registro_ans');

        $validator
            ->scalar('cnpj')
            ->maxLength('cnpj', 18)
            ->allowEmptyString('cnpj')
            ->add('cnpj', 'length', [
                'rule' => ['lengthBetween', 18, 18],
                'message' => __('O documento deve conter exatamente 14 caracteres.')
            ]);

        $validator
            ->scalar('telefone')
            ->minLength('telefone', 10, __('O Telefone deve conter 10 caracteres.'))
            ->maxLength('telefone', 14)
            ->allowEmptyString('telefone');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('site')
            ->maxLength('site', 255)
            ->allowEmptyString('site');

        $validator
            ->boolean('ativar')
            ->notEmptyString('ativar');

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
