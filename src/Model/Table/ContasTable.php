<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contas Model
 *
 * @property \App\Model\Table\AssinaturasTable&\Cake\ORM\Association\HasMany $Assinaturas
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\HasMany $Usuarios
 * @property \App\Model\Table\WhatsappsTable&\Cake\ORM\Association\HasMany $Whatsapps
 *
 * @method \App\Model\Entity\Conta newEmptyEntity()
 * @method \App\Model\Entity\Conta newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Conta> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Conta get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Conta findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Conta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Conta> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Conta|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Conta saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Conta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Conta>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Conta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Conta> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Conta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Conta>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Conta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Conta> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContasTable extends Table
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

        $this->setTable('contas');
        $this->setDisplayField('cpf_cnpj');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Assinaturas', [
            'foreignKey' => 'conta_id',
        ]);
        $this->hasMany('Estabelecimentos', [
            'foreignKey' => 'conta_id',
        ]);
        $this->hasMany('Usuarios', [
            'foreignKey' => 'conta_id',
        ]);
        $this->hasMany('Whatsapps', [
            'foreignKey' => 'conta_id',
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
            ->scalar('cpf_cnpj')
            ->requirePresence('cpf_cnpj', 'create','Informe seu CPF/CNPJ')
            ->notEmptyString('cpf_cnpj', 'CPF/CNPJ não pode ser vazio');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 255,'Máximo de 255 caracteres')
            ->requirePresence('nome', 'create','Informe seu nome')
            ->notEmptyString('nome','Seu nome não pode ser vazio');

        $validator
            ->scalar('razao_social')
            ->maxLength('razao_social', 255)
            ->allowEmptyString('razao_social');

        $validator
            ->email('email',false,'Informe um e-mail válido')
            ->requirePresence('email', 'create','Informe um e-mail')
            ->notEmptyString('email','O e-mail não pode ser vazio')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('logradouro')
            ->maxLength('logradouro', 255)
            ->allowEmptyString('logradouro');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 10)
            ->allowEmptyString('cep');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 100)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 100)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('uf')
            ->maxLength('uf', 2)
            ->allowEmptyString('uf');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 80)
            ->allowEmptyString('complemento');

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
        $rules->add($rules->isUnique(['email'],'E-mail já cadastrado'), ['errorField' => 'email']);

        return $rules;
    }
}
