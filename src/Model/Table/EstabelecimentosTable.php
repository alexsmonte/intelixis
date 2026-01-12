<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;

/**
 * Estabelecimentos Model
 *
 * @property \App\Model\Table\ContasTable&\Cake\ORM\Association\BelongsTo $Contas
 * @property \App\Model\Table\AssinaturasTable&\Cake\ORM\Association\BelongsTo $Assinaturas
 * @property \App\Model\Table\AgendamentosTable&\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\BloqueiosTable&\Cake\ORM\Association\HasMany $Bloqueios
 * @property \App\Model\Table\ClientesTable&\Cake\ORM\Association\HasMany $Clientes
 * @property \App\Model\Table\ConveniosTable&\Cake\ORM\Association\HasMany $Convenios
 * @property \App\Model\Table\LocaisTable&\Cake\ORM\Association\HasMany $Locais
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\HasMany $Periodos
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\HasMany $Profissionais
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\HasMany $Servicos
 * @property \App\Model\Table\StatusTable&\Cake\ORM\Association\HasMany $Status
 * @property \App\Model\Table\WhatsappsTable&\Cake\ORM\Association\HasMany $Whatsapps
 * @property \App\Model\Table\ExtensoesTable&\Cake\ORM\Association\BelongsToMany $Extensoes
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsToMany $Usuarios
 *
 * @method \App\Model\Entity\Estabelecimento newEmptyEntity()
 * @method \App\Model\Entity\Estabelecimento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Estabelecimento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Estabelecimento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Estabelecimento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Estabelecimento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Estabelecimento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Estabelecimento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Estabelecimento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Estabelecimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Estabelecimento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Estabelecimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Estabelecimento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Estabelecimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Estabelecimento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Estabelecimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Estabelecimento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EstabelecimentosTable extends Table
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

        $this->setTable('estabelecimentos');
        $this->setDisplayField('razao_social');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Contas', [
            'foreignKey' => 'conta_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Assinaturas', [
            'foreignKey' => 'assinatura_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'estabelecimento_id',
        ]);

        $this->hasMany('Anexos', [
            'foreignKey' => 'estabelecimento_id',
        ]);

        $this->hasMany('Bloqueios', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Caixas', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Ccustos', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Clientes', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Consultas', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Convenios', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Financas', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('FormaPagamentos', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Locais', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Periodos', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Profissionais', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Receituarios', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Servicos', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Status', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Whatsapps', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->belongsToMany('Extensoes', [
            'foreignKey' => 'estabelecimento_id',
            'targetForeignKey' => 'extensao_id',
            'joinTable' => 'estabelecimentos_extensoes',
        ]);
        $this->belongsToMany('Usuarios', [
            'foreignKey' => 'estabelecimento_id',
            'targetForeignKey' => 'usuario_id',
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
            ->integer('assinatura_id')
            ->notEmptyString('assinatura_id');

        $validator
            ->scalar('logo')
            ->maxLength('logo', 255)
            ->allowEmptyString('logo');

        $validator
            ->scalar('razao_social')
            ->maxLength('razao_social', 255)
            ->requirePresence('razao_social', 'create')
            ->notEmptyString('razao_social');

        $validator
            ->scalar('nome_fantasia')
            ->maxLength('nome_fantasia', 255)
            ->requirePresence('nome_fantasia', 'create')
            ->notEmptyString('nome_fantasia');

        $validator
            ->scalar('cpf_cnpj')
            ->maxLength('cpf_cnpj', 21)
            ->requirePresence('cpf_cnpj', 'create')
            ->notEmptyString('cpf_cnpj','Você precisa informa o CPF/CNPJ');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 9)
            ->allowEmptyString('cep');

        $validator
            ->scalar('logradouro')
            ->maxLength('logradouro', 255)
            ->allowEmptyString('logradouro');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 100)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('numero')
            ->maxLength('numero', 30)
            ->allowEmptyString('numero');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 80)
            ->allowEmptyString('complemento');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('telefone')
            ->maxLength('telefone', 20)
            ->allowEmptyString('telefone');

        $validator
            ->scalar('telefone_opcional')
            ->maxLength('telefone_opcional', 30)
            ->allowEmptyString('telefone_opcional');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 20)
            ->allowEmptyString('celular');

        $validator
            ->boolean('ativar')
            ->allowEmptyString('ativar');

        $validator
            ->integer('habilitar_programacao')
            ->allowEmptyString('habilitar_programacao');

        $validator
            ->integer('intervalo_atendimento')
            ->allowEmptyString('intervalo_atendimento');

        $validator
            ->integer('retorno_atendimento')
            ->allowEmptyString('retorno_atendimento');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 80)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('uf')
            ->maxLength('uf', 2)
            ->allowEmptyString('uf');

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
        $rules->add($rules->existsIn(['conta_id'], 'Contas'), ['errorField' => 'conta_id']);
        $rules->add($rules->existsIn(['assinatura_id'], 'Assinaturas'), ['errorField' => 'assinatura_id']);

        return $rules;
    }


    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {

        $caracterInvalido   =   [" ",".","-","/",")","(",","];

        if(isset($data['cpf_cnpj']))
            $data['cpf_cnpj']      =   str_replace($caracterInvalido, "", $data['cpf_cnpj']);
        /**
        if(isset($data['cep']))
        $data['cep']           =   str_replace($caracterInvalido, "", $data['cep']);

        if(isset($data['telefone']))
        $data['telefone']      =   preg_replace('/[\p{L}]/', '',str_replace($caracterInvalido, "", $data['telefone']));

        if(isset($data['celular']))
        $data['celular']      =   preg_replace('/[\p{L}]/', '',str_replace($caracterInvalido, "", $data['celular']));
         **/
    }
}
