<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Event\Event;


/**
 * Usuarios Model
 *
 * @property \App\Model\Table\ContasTable&\Cake\ORM\Association\BelongsTo $Contas
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsToMany $Estabelecimentos
 *
 * @method \App\Model\Entity\Usuario newEmptyEntity()
 * @method \App\Model\Entity\Usuario newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Usuario> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Usuario get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Usuario findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Usuario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Usuario> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Usuario|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Usuario saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Usuario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Usuario>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Usuario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Usuario> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Usuario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Usuario>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Usuario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Usuario> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsuariosTable extends Table
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

        $this->setTable('usuarios');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Contas', [
            'foreignKey' => 'conta_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Profissionais', [
            'foreignKey' => 'profissional_id',
            'joinType' => 'LEFT',
        ]);

        $this->belongsToMany('Estabelecimentos', [
            'foreignKey' => 'usuario_id',
            'targetForeignKey' => 'estabelecimento_id',
            'joinTable' => 'usuarios_estabelecimentos',
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
            ->integer('conta_id')
            ->notEmptyString('conta_id');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 255,'Máximo de 255 caracteres')
            ->requirePresence('nome', 'create', 'Informe o nome completo')
            ->notEmptyString('nome', 'O nome não pode ser vazio');

        $validator
            ->email('email',false,'Informe um e-mail válido')
            ->maxLength('email', 255,'Máximo de 255 caracteres')
            ->requirePresence('email', 'create','Informe um e-mail')
            ->notEmptyString('email','O e-mail não pode ser vazio')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->minLength('password', 6,'Mínimo de 6 caracteres')
            ->allowEmptyString('password','Informe uma senha','create');

        $validator
            ->scalar('password_confirm')
            ->allowEmptyString('password_confirm')
            ->add('password_confirm', 'custom', [
                'rule' => function ($value, $context) {
                    return isset($context['data']['password']) && $value === $context['data']['password'];
                },
                'message' => 'As senhas não conferem'
            ]);

        $validator
            ->boolean('ativar')
            ->allowEmptyString('ativar');

        $validator
            ->scalar('tipo')
            ->notEmptyString('tipo');

        return $validator;
    }


    public function validationCriarUsuario(Validator $validator): Validator
    {
        $validator
            ->integer('conta_id')
            ->notEmptyString('conta_id');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 255,'Máximo de 255 caracteres')
            ->requirePresence('nome', 'create', 'Informe o nome completo')
            ->notEmptyString('nome', 'O nome não pode ser vazio');

        $validator
            ->email('email',false,'Informe um e-mail válido')
            ->maxLength('email', 255,'Máximo de 255 caracteres')
            ->requirePresence('email', 'create','Informe um e-mail')
            ->notEmptyString('email','O e-mail não pode ser vazio')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table','message'=>'E-Mail já está em uso']);

        $validator
            ->scalar('password')
            ->minLength('password', 6,'Mínimo de 6 caracteres')
            ->allowEmptyString('password','Informe uma senha','create');

        $validator
            ->scalar('password_confirm')
            ->requirePresence('password_confirm', 'create')
            ->notEmptyString('password_confirm', 'Confirme a senha')
            ->add('password_confirm', 'custom', [
                'rule' => function ($value, $context) {
                    return isset($context['data']['password']) && $value === $context['data']['password'];
                },
                'message' => 'As senhas não conferem'
            ]);

        $validator
            ->boolean('ativar')
            ->allowEmptyString('ativar');

        $validator
            ->scalar('tipo')
            ->notEmptyString('tipo');

        return $validator;
    }


    public function validationEditarUsuario(Validator $validator): Validator
    {
        $validator
            ->integer('conta_id')
            ->notEmptyString('conta_id');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 255,'Máximo de 255 caracteres')
            ->requirePresence('nome', 'create', 'Informe o nome completo')
            ->notEmptyString('nome', 'O nome não pode ser vazio');

        $validator
            ->email('email',false,'Informe um e-mail válido')
            ->maxLength('email', 255,'Máximo de 255 caracteres')
            ->requirePresence('email', 'create','Informe um e-mail')
            ->notEmptyString('email','O e-mail não pode ser vazio')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table','message'=>'E-Mail já está em uso']);

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->minLength('password', 6,'Mínimo de 6 caracteres');

        $validator
            ->scalar('password_confirm')
            ->requirePresence('password_confirm', 'create')
            ->add('password_confirm', 'custom', [
                'rule' => function ($value, $context) {
                    if (!isset($context['data']['password']) || $context['data']['password'] === '') {
                        return true;
                    }
                    return $value === $context['data']['password'];
                },
                'message' => 'As senhas não conferem'
            ]);

        $validator
            ->boolean('ativar')
            ->allowEmptyString('ativar');

        $validator
            ->scalar('tipo')
            ->notEmptyString('tipo');

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
        $rules->add($rules->isUnique(['email'],'E-Mail já esta em uso'), ['errorField' => 'email']);
        $rules->add($rules->existsIn(['conta_id'], 'Contas','Verifique o ID da conta.'), ['errorField' => 'conta_id']);
        $rules->add($rules->existsIn('profissional_id', 'Profissionais'), [
            'errorField' => 'profissional_id',
            'message' => 'Profissional não encontrado'
        ]);
        return $rules;
    }

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {

        if(isset($data['password']) && empty($data['password']))
            unset($data['password']);
    }

    public function findAtivos(Query $query, array $options)
    {
        return $query->where(['Usuarios.ativar' => true]);
    }
}
