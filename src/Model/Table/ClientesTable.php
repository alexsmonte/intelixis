<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;

/**
 * Clientes Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\AgendamentosTable&\Cake\ORM\Association\HasMany $Agendamentos
 *
 * @method \App\Model\Entity\Cliente newEmptyEntity()
 * @method \App\Model\Entity\Cliente newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Cliente> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cliente get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Cliente findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Cliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Cliente> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cliente|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Cliente saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Cliente>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Cliente>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Cliente>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Cliente> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Cliente>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Cliente>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Cliente>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Cliente> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ClientesTable extends Table
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

        $this->setTable('clientes');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'cliente_id',
        ]);
        $this->hasMany('ClientesConvenios', [
            'foreignKey' => 'cliente_id',
            'dependent' => true,
            'saveStrategy' => 'replace',
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
            ->minLength('nome', 5, __('O nome deve ter pelo menos 5 caracteres.'))
            ->maxLength('nome', 255)
            ->add('nome', 'fullName', [
                'rule' => 'isFullName',
                'provider' => 'table',
                'message' => __('Informe nome e sobrenome.'),
            ])
            ->requirePresence('nome', true)
            ->notEmptyString('nome', __('O nome é obrigatório.'));

        $validator
            ->scalar('nome_social')
            ->minLength('nome_social', 5, __('O nome deve ter pelo menos 5 caracteres.'))
            ->maxLength('nome_social', 255)
            ->add('nome_social', 'fullName', [
                'rule' => 'isFullName',
                'provider' => 'table',
                'message' => __('Informe nome e sobrenome.'),
            ])
            ->allowEmptyString('nome_social');
        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 1)
            ->requirePresence('sexo', true)
            ->notEmptyString('sexo', __('O sexo é obrigatório.'))
            ->inList('sexo', ['M', 'F', 'I'], __('O sexo deve ser Masculino, Feminino ou Indefinido.'));
        $validator
            ->date('data_nascimento', ['dmy'], 'Informe uma data válida')
            ->requirePresence('data_nascimento', true)
            ->allowEmptyDate('data_nascimento');

        $validator
            ->requirePresence('documento', true, __('O documento é obrigatório.'))
            ->notEmptyString('documento', __('O documento é obrigatório.'))
            ->add('documento', 'validLength', [
                'rule' => ['custom', '/^(\d{11}|\d{14})$/'],
                'message' => __('O documento deve conter exatamente 11 ou 14 caracteres.')
            ]);



        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('telefone')
            ->minLength('telefone', 10, __('O Telefone deve conter 10 caracteres.'))
            ->maxLength('telefone', 14)
            ->allowEmptyString('telefone');

        $validator
            ->scalar('celular')
            ->minLength('celular', 11, __('O Celular deve conter 11 caracteres.'))
            ->maxLength('celular', 15)
            ->allowEmptyString('celular');



        $validator
            ->scalar('cep')
            ->minLength('cep', 8, __('O CEP deve conter 8 caracteres.'))
            ->maxLength('cep', 9)
            ->allowEmptyString('cep');

        $validator
            ->scalar('logradouro')
            ->maxLength('logradouro', 50)
            ->allowEmptyString('logradouro');

        $validator
            ->scalar('numero')
            ->maxLength('numero', 10)
            ->allowEmptyString('numero');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 100)
            ->allowEmptyString('complemento');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 100)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 45)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('nome_responsavel')
            ->allowEmptyString('nome_responsavel');

        $validator
            ->scalar('documento_responsavel')
            ->allowEmptyString('documento_responsavel')
            ->add('documento_responsavel', 'validLength', [
                'rule' => ['custom', '/^(\d{11}|\d{14})$/'],
                'message' => __('O documento deve conter exatamente 11 ou 14 caracteres.')
            ]);

        $validator
            ->scalar('telefone_responsavel')
            ->minLength('telefone_responsavel', 11, __('O Telefone deve conter 11 caracteres.'))
            ->maxLength('telefone_responsavel', 15)
            ->allowEmptyString('telefone_responsavel');

        $validator
            ->scalar('parentesco_responsavel')
            ->maxLength('parentesco_responsavel', 20)
            ->allowEmptyString('parentesco_responsavel');

        $validator
            ->boolean('foto')
            ->allowEmptyString('foto');

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

    public function isFullName($value, array $context): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Normaliza espaços
        $value = trim(preg_replace('/\s+/u', ' ', $value));

        // Separa por espaço
        $parts = explode(' ', $value);

        // Mantém apenas partes com pelo menos 2 letras (Unicode)
        $validParts = array_filter($parts, function ($w) {
            $lettersOnly = preg_replace('/[^\\p{L}]/u', '', $w);
            return mb_strlen($lettersOnly) >= 2;
        });

        // Precisa de pelo menos duas partes válidas
        return count($validParts) >= 2;
    }

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {

        $caracterInvalido = [" ", ".", "-", "/", ")", "(", ","];

        if (isset($data['documento']))
            $data['documento'] = str_replace($caracterInvalido, "", $data['documento']);

        if (isset($data['documento_responsavel']))
            $data['documento_responsavel'] = str_replace($caracterInvalido, "", $data['documento_responsavel']);

        if (isset($data['telefone']))
            $data['telefone'] = preg_replace('/[\p{L}]/', '', str_replace($caracterInvalido, "", $data['telefone']));
        if (isset($data['telefone_responsavel']))
            $data['telefone_responsavel'] = preg_replace('/[\p{L}]/', '', str_replace($caracterInvalido, "", $data['telefone_responsavel']));

        if (isset($data['celular']))
            $data['celular'] = preg_replace('/[\p{L}]/', '', str_replace($caracterInvalido, "", $data['celular']));

        if (isset($data['cep']))
            $data['cep'] = preg_replace('/[\p{L}]/', '', str_replace($caracterInvalido, "", $data['cep']));

    }

}
