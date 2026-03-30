<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Documentos Model
 *
 * @property \App\Model\Table\ProcedimentosTable&\Cake\ORM\Association\BelongsTo $Procedimentos
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\ProfissionaisTable&\Cake\ORM\Association\BelongsTo $Profissionais
 *
 * @method \App\Model\Entity\Documento newEmptyEntity()
 * @method \App\Model\Entity\Documento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Documento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Documento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Documento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Documento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Documento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Documento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Documento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Documento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Documento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Documento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Documento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Documento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Documento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Documento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Documento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DocumentosTable extends Table
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

        $this->setTable('documentos');
        $this->setDisplayField('tipo');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Procedimentos', [
            'foreignKey' => 'procedimento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
        ]);
        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
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
            ->integer('procedimento_id')
            ->notEmptyString('procedimento_id');

        $validator
            ->allowEmptyString('estabelecimento_id');

        $validator
            ->allowEmptyString('usuario_id');

        $validator
            ->allowEmptyString('profissional_id');

        $validator
            ->scalar('tipo')
            ->maxLength('tipo', 30)
            ->requirePresence('tipo', 'create')
            ->notEmptyString('tipo');

        $validator
            ->scalar('texto')
            ->allowEmptyString('texto');

        $validator
            ->scalar('audio_transcricao')
            ->allowEmptyString('audio_transcricao');

        $validator
            ->scalar('audio_path')
            ->maxLength('audio_path', 255)
            ->allowEmptyString('audio_path');

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
        $rules->add($rules->existsIn(['procedimento_id'], 'Procedimentos'), ['errorField' => 'procedimento_id']);
        $rules->add($rules->existsIn(['estabelecimento_id'], 'Estabelecimentos'), ['errorField' => 'estabelecimento_id']);
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'), ['errorField' => 'usuario_id']);
        $rules->add($rules->existsIn(['profissional_id'], 'Profissionais'), ['errorField' => 'profissional_id']);

        return $rules;
    }
}
