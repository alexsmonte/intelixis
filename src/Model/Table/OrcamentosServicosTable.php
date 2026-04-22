<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;

/**
 * OrcamentosServicos Model
 *
 * @property \App\Model\Table\OrcamentosTable&\Cake\ORM\Association\BelongsTo $Orcamentos
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\BelongsTo $Servicos
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsTo $Profissionais
 *
 * @method \App\Model\Entity\OrcamentosServico newEmptyEntity()
 * @method \App\Model\Entity\OrcamentosServico newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\OrcamentosServico> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcamentosServico get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\OrcamentosServico findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\OrcamentosServico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\OrcamentosServico> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcamentosServico|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\OrcamentosServico saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\OrcamentosServico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OrcamentosServico>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OrcamentosServico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OrcamentosServico> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OrcamentosServico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OrcamentosServico>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OrcamentosServico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OrcamentosServico> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrcamentosServicosTable extends Table
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

        $this->setTable('orcamentos_servicos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orcamentos', [
            'foreignKey' => 'orcamento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Servicos', [
            'foreignKey' => 'servico_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Profissionais', [
            'foreignKey' => 'profissional_id',
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
            ->integer('orcamento_id')
            ->notEmptyString('orcamento_id');

        $validator
            ->notEmptyString('servico_id');

        $validator
            ->allowEmptyString('profissional_id');

        $validator
            ->integer('quantidade')
            ->allowEmptyString('quantidade');

        $validator
            ->decimal('valor_unitario')
            ->allowEmptyString('valor_unitario');

        $validator
            ->decimal('desconto')
            ->allowEmptyString('desconto');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

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
        $rules->add($rules->existsIn(['orcamento_id'], 'Orcamentos'), ['errorField' => 'orcamento_id']);
        $rules->add($rules->existsIn(['servico_id'], 'Servicos'), ['errorField' => 'servico_id']);
        $rules->add($rules->existsIn(['profissional_id'], 'Profissionais'), ['errorField' => 'profissional_id']);

        return $rules;
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, \ArrayObject $options)
    {
        $this->recalcularTotais($entity->orcamento_id);
    }

    private function recalcularTotais($orcamentoId)
    {
        $itens = $this->find()
            ->where(['orcamento_id' => $orcamentoId])
            ->all();

        $totalBruto = 0;
        $totalDesconto = 0;

        foreach ($itens as $item) {
            $totalBruto += $item->quantidade * $item->valor_unitario;
            $totalDesconto += $item->desconto;
        }

        $orcamento = $this->Orcamentos->get($orcamentoId);

        $orcamento->valor_total = $totalBruto - $totalDesconto;
        $orcamento->desconto = $totalDesconto;

        $this->Orcamentos->save($orcamento);
    }
}
