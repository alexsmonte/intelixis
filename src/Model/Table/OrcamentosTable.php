<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orcamentos Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\ClientesTable&\Cake\ORM\Association\BelongsTo $Clientes
 * @property \App\Model\Table\ConsultasTable&\Cake\ORM\Association\BelongsTo $Consultas
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\BelongsToMany $Servicos
 *
 * @method \App\Model\Entity\Orcamento newEmptyEntity()
 * @method \App\Model\Entity\Orcamento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Orcamento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Orcamento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Orcamento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Orcamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Orcamento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Orcamento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Orcamento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Orcamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Orcamento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Orcamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Orcamento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Orcamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Orcamento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Orcamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Orcamento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrcamentosTable extends Table
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

        $this->setTable('orcamentos');
        $this->setDisplayField('status');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Clientes', [
            'foreignKey' => 'cliente_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Consultas', [
            'foreignKey' => 'consulta_id',
        ]);

        $this->hasMany('OrcamentosServicos', [
            'foreignKey' => 'orcamento_id',
            'dependent' => true,
        ]);

        $this->hasMany('OrcamentosProcedimentos', [
            'foreignKey' => 'orcamento_id',
            'saveStrategy' => 'replace'
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
            ->integer('usuario_id')
            ->notEmptyString('usuario_id');

        $validator
            ->notEmptyString('cliente_id');

        $validator
            ->integer('consulta_id')
            ->allowEmptyString('consulta_id');

        $validator
            ->date('validade',['ymd'],'Data inválida')
            ->allowEmptyDate('validade', 'Data inválida');

        $validator
            ->scalar('status')
            ->maxLength('status', 60)
            ->notEmptyString('status');

        $validator
            ->decimal('valor_total')
            ->requirePresence('valor_total', 'create')
            ->notEmptyString('valor_total');

        $validator
            ->decimal('desconto')
            ->allowEmptyString('desconto');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

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
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'), ['errorField' => 'usuario_id']);
        $rules->add($rules->existsIn(['cliente_id'], 'Clientes'), ['errorField' => 'cliente_id']);
        $rules->add($rules->existsIn(['consulta_id'], 'Consultas'), ['errorField' => 'consulta_id']);

        return $rules;
    }
}
