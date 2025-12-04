<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Caixas Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\MovimentacoesTable&\Cake\ORM\Association\HasMany $Movimentacoes
 *
 * @method \App\Model\Entity\Caixa newEmptyEntity()
 * @method \App\Model\Entity\Caixa newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Caixa> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Caixa get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Caixa findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Caixa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Caixa> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Caixa|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Caixa saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Caixa>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Caixa>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Caixa>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Caixa> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Caixa>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Caixa>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Caixa>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Caixa> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CaixasTable extends Table
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

        $this->setTable('caixas');
        $this->setDisplayField('id');
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
        $this->hasMany('Movimentacoes', [
            'foreignKey' => 'caixa_id',
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
            ->dateTime('data_abertura')
            ->requirePresence('data_abertura', 'create')
            ->notEmptyDateTime('data_abertura');

        $validator
            ->decimal('saldo_inicial')
            ->notEmptyString('saldo_inicial');

        $validator
            ->dateTime('data_fechamento')
            ->allowEmptyDateTime('data_fechamento');

        $validator
            ->scalar('situacao')
            ->allowEmptyString('situacao');

        $validator
            ->integer('usuario_conferiu')
            ->allowEmptyString('usuario_conferiu');

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

        return $rules;
    }
}
