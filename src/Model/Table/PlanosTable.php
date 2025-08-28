<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Planos Model
 *
 * @property \App\Model\Table\AssinaturasTable&\Cake\ORM\Association\HasMany $Assinaturas
 *
 * @method \App\Model\Entity\Plano newEmptyEntity()
 * @method \App\Model\Entity\Plano newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Plano> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Plano get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Plano findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Plano patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Plano> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Plano|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Plano saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Plano>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Plano>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Plano>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Plano> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Plano>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Plano>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Plano>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Plano> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PlanosTable extends Table
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

        $this->setTable('planos');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Assinaturas', [
            'foreignKey' => 'plano_id',
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
            ->maxLength('nome', 80,'O nome do plano deve ter no máximo 80 caracteres')
            ->requirePresence('nome', 'create', 'Informe o nome do plano')
            ->notEmptyString('nome','Informe o nome do plano');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->scalar('resumo')
            ->maxLength('resumo', 255)
            ->allowEmptyString('resumo');

        $validator
            ->decimal('valor','Informe o valor do plano corretamente')
            ->allowEmptyString('valor');

        $validator
            ->integer('numero_whatsapp', 'Informe um número')
            ->allowEmptyString('numero_whatsapp');

        $validator
            ->boolean('ativar')
            ->allowEmptyString('ativar');

        return $validator;
    }
}
