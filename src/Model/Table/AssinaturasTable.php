<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Assinatura;
use App\Model\Entity\Conta;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


/**
 * Assinaturas Model
 *
 * @property \App\Model\Table\ContasTable&\Cake\ORM\Association\BelongsTo $Contas
 * @property \App\Model\Table\PlanosTable&\Cake\ORM\Association\BelongsTo $Planos
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\HasMany $Estabelecimentos
 *
 * @method \App\Model\Entity\Assinatura newEmptyEntity()
 * @method \App\Model\Entity\Assinatura newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Assinatura> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Assinatura get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Assinatura findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Assinatura patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Assinatura> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Assinatura|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Assinatura saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Assinatura>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Assinatura>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Assinatura>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Assinatura> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Assinatura>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Assinatura>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Assinatura>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Assinatura> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssinaturasTable extends Table
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

        $this->setTable('assinaturas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Contas', [
            'foreignKey' => 'conta_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Planos', [
            'foreignKey' => 'plano_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Estabelecimentos', [
            'foreignKey' => 'assinatura_id',
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
            ->integer('plano_id')
            ->notEmptyString('plano_id');

        $validator
            ->date('data_vencimento',['dmy'],'Informe uma data válida')
            ->requirePresence('data_vencimento', 'create')
            ->notEmptyDate('data_vencimento');

        $validator
            ->decimal('desconto')
            ->allowEmptyString('desconto');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

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
        $rules->add($rules->existsIn(['plano_id'], 'Planos'), ['errorField' => 'plano_id']);

        return $rules;
    }

    public function findVerificar(SelectQuery $query, $contaId)
    {
        return $query
            ->contain(['Estabelecimentos'])
            ->where(['Assinaturas.conta_id' => $contaId]);
    }
}
