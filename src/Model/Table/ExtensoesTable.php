<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Extensoes Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsToMany $Estabelecimentos
 *
 * @method \App\Model\Entity\Extensao newEmptyEntity()
 * @method \App\Model\Entity\Extensao newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Extensao> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Extensao get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Extensao findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Extensao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Extensao> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Extensao|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Extensao saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Extensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Extensao>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Extensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Extensao> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Extensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Extensao>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Extensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Extensao> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExtensoesTable extends Table
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

        $this->setTable('extensoes');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Estabelecimentos', [
            'foreignKey' => 'extensao_id',
            'targetForeignKey' => 'estabelecimento_id',
            'joinTable' => 'estabelecimentos_extensoes',
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
            ->scalar('nome')
            ->maxLength('nome', 80)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->decimal('valor')
            ->allowEmptyString('valor');

        $validator
            ->boolean('ativar')
            ->allowEmptyString('ativar');

        return $validator;
    }
}
