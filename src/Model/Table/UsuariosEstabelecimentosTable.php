<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuariosEstabelecimentos Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 *
 * @method \App\Model\Entity\UsuariosEstabelecimento newEmptyEntity()
 * @method \App\Model\Entity\UsuariosEstabelecimento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UsuariosEstabelecimento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosEstabelecimento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UsuariosEstabelecimento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UsuariosEstabelecimento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UsuariosEstabelecimento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosEstabelecimento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UsuariosEstabelecimento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UsuariosEstabelecimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UsuariosEstabelecimento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UsuariosEstabelecimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UsuariosEstabelecimento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UsuariosEstabelecimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UsuariosEstabelecimento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UsuariosEstabelecimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UsuariosEstabelecimento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsuariosEstabelecimentosTable extends Table
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

        $this->setTable('usuarios_estabelecimentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
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
            ->integer('usuario_id')
            ->notEmptyString('usuario_id');

        $validator
            ->notEmptyString('estabelecimento_id');

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
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'), ['errorField' => 'usuario_id']);
        $rules->add($rules->existsIn(['estabelecimento_id'], 'Estabelecimentos'), ['errorField' => 'estabelecimento_id']);

        return $rules;
    }
}
