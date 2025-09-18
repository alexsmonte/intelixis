<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;

/**
 * Locais Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\AgendamentosTable&\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\BloqueiosTable&\Cake\ORM\Association\HasMany $Bloqueios
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\HasMany $Periodos
 *
 * @method \App\Model\Entity\Local newEmptyEntity()
 * @method \App\Model\Entity\Local newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Local> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Local get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Local findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Local patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Local> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Local|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Local saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Local>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Local>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Local>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Local> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Local>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Local>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Local>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Local> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LocaisTable extends Table
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

        $this->setTable('locais');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'local_id',
        ]);
        $this->hasMany('Bloqueios', [
            'foreignKey' => 'local_id',
        ]);
        $this->hasMany('Periodos', [
            'foreignKey' => 'local_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->notEmptyString('nome', __('O nome do local é obrigatório.'));

        $validator
            ->scalar('endereco')
            ->requirePresence('endereco', 'create')
            ->notEmptyString('endereco', __('O Endereço do local é obrigatório.'));

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

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {

        $caracterInvalido   =   [" ",".","-","/",")","(",","];

        if(isset($data['cnpj']))
            $data['cnpj']      =   str_replace($caracterInvalido, "", $data['cnpj']);

        if(isset($data['telefone']))
            $data['telefone']      =   preg_replace('/[\p{L}]/', '',str_replace($caracterInvalido, "", $data['telefone']));

       

    }
}
