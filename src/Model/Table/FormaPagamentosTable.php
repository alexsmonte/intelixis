<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FormaPagamentos Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\MovimentacoesTable&\Cake\ORM\Association\HasMany $Movimentacoes
 * @property \App\Model\Table\PagamentosTable&\Cake\ORM\Association\HasMany $Pagamentos
 *
 * @method \App\Model\Entity\FormaPagamento newEmptyEntity()
 * @method \App\Model\Entity\FormaPagamento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\FormaPagamento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FormaPagamento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\FormaPagamento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\FormaPagamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\FormaPagamento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FormaPagamento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\FormaPagamento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\FormaPagamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FormaPagamento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FormaPagamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FormaPagamento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FormaPagamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FormaPagamento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FormaPagamento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FormaPagamento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FormaPagamentosTable extends Table
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

        $this->setTable('forma_pagamentos');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->hasMany('Movimentacoes', [
            'foreignKey' => 'forma_pagamento_id',
        ]);
        $this->hasMany('Pagamentos', [
            'foreignKey' => 'forma_pagamento_id',
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
            ->allowEmptyString('estabelecimento_id');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 80)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->integer('tipo_taxa')
            ->allowEmptyString('tipo_taxa');

        $validator
            ->decimal('taxas')
            ->allowEmptyString('taxas');

        $validator
            ->decimal('taxa_antecipacao')
            ->allowEmptyString('taxa_antecipacao');

        $validator
            ->integer('dias')
            ->allowEmptyString('dias');

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
