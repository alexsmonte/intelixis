<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Database\Schema\TableSchema;
use Cake\Event\Event;

/**
 * Financas Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\CcustosTable&\Cake\ORM\Association\BelongsTo $Ccustos
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsTo $Profissionais
 * @property \App\Model\Table\ClientesTable&\Cake\ORM\Association\BelongsTo $Clientes
 * @property \App\Model\Table\ProcedimentosTable&\Cake\ORM\Association\BelongsTo $Procedimentos
 * @property \App\Model\Table\PagamentosTable&\Cake\ORM\Association\HasMany $Pagamentos
 *
 * @method \App\Model\Entity\Financa newEmptyEntity()
 * @method \App\Model\Entity\Financa newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Financa> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Financa get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Financa findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Financa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Financa> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Financa|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Financa saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Financa>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Financa>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Financa>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Financa> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Financa>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Financa>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Financa>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Financa> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FinancasTable extends Table
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

        $this->setTable('financas');
        $this->setDisplayField('descricao');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Ccustos', [
            'className' => 'Ccustos',
            'foreignKey' => 'ccustos_id',
            'joinType' => 'LEFT',
        ]);
        $this->belongsTo('Profissionais', [
            'foreignKey' => 'profissional_id',
        ]);
        $this->belongsTo('Clientes', [
            'foreignKey' => 'cliente_id',
        ]);
        $this->belongsTo('Procedimentos', [
            'foreignKey' => 'procedimento_id',
        ]);
        $this->hasMany('Pagamentos', [
            'foreignKey' => 'financa_id',
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
            ->integer('ccustos_id')
            ->allowEmptyString('ccustos_id');

        $validator
            ->allowEmptyString('profissional_id');

        $validator
            ->allowEmptyString('cliente_id');

        $validator
            ->integer('procedimento_id')
            ->allowEmptyString('procedimento_id');

        $validator
            ->integer('tipo')
            ->allowEmptyString('tipo');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

            $validator
            ->date('data_vencimento', ['ymd'], 'Informe uma data válida')
            ->requirePresence('data_vencimento', 'create')
            ->notEmptyDate('data_vencimento', 'Informe uma data válida');
        $validator
            ->decimal('valor')
            ->requirePresence('valor', 'create')
            ->notEmptyString('valor');

        $validator
            ->decimal('desconto')
            ->allowEmptyString('desconto');

            $validator
            ->date('data_baixa', ['ymd'], 'Informe uma data válida')
            
            ->allowEmptyDate('data_baixa', 'Informe uma data válida');

        $validator
            ->decimal('valor_recebido')
            ->allowEmptyString('valor_recebido');

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
        $rules->add($rules->existsIn(['ccustos_id'], 'Ccustos'), ['errorField' => 'ccustos_id']);
        $rules->add($rules->existsIn(['profissional_id'], 'Profissionais'), ['errorField' => 'profissional_id']);
        $rules->add($rules->existsIn(['cliente_id'], 'Clientes'), ['errorField' => 'cliente_id']);
        $rules->add($rules->existsIn(['procedimento_id'], 'Procedimentos'), ['errorField' => 'procedimento_id']);

        return $rules;
    }

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
{

    $caracterInvalido   =   [" ",".","-","/",")","(",","];
    $caracterDecimal   =   [","];

    if(isset($data['documento']))
        $data['documento']      =   str_replace($caracterInvalido, "", $data['documento']);

    if(isset($data['telefone']))
        $data['telefone']      =   preg_replace('/[\p{L}]/', '',str_replace($caracterInvalido, "", $data['telefone']));

        if(isset($data['celular']))
        $data['celular']      =   preg_replace('/[\p{L}]/', '',str_replace($caracterInvalido, "", $data['celular']));

        if(isset($data['cep']))
        $data['cep']      =   preg_replace('/[\p{L}]/', '',str_replace($caracterInvalido, "", $data['cep']));
        
        
    }

  public function afterSave(\Cake\Event\EventInterface $event, \Cake\Datasource\EntityInterface $entity)
{
    if (!$entity->isNew()) {

        if (!is_null($entity->data_baixa) && !is_null($entity->valor_recebido)) {

            $diferenca = round(floatval(((float)$entity->valor - (float)$entity->desconto) - (float)$entity->valor_recebido), 2);

            if (floatval($diferenca) > 0.00) {

                $contas = $this->newEntity([
                    'ccusto_id'          => $entity->ccusto_id,
                    'cliente_id'        => $entity->cliente_id,
                    'estabelecimento_id' => $entity->estabelecimento_id,
                    'fornecedor_id'     => $entity->cliente_id,
                    'profissional_id'   => $entity->profissional_id,
                    'tipo'              => $entity->tipo,
                    'diferenca'         => $entity->id,
                    'descricao'         => 'Diferença referente ao pagamento ID: ' . $entity->id . ' - ' . $entity->descricao,
                    'data_vencimento'   => $entity->data_vencimento,
                    'valor'             => $diferenca,
                    'desconto'          => 0.00,
                ]);

                $this->save($contas);
            }
        }
    }
}
}
