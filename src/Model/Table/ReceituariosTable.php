<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Receituarios Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 *
 * @method \App\Model\Entity\Receituario newEmptyEntity()
 * @method \App\Model\Entity\Receituario newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Receituario> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Receituario get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Receituario findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Receituario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Receituario> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Receituario|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Receituario saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Receituario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Receituario>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Receituario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Receituario> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Receituario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Receituario>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Receituario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Receituario> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReceituariosTable extends Table
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

        $this->setTable('receituarios');
        $this->setDisplayField('categoria');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
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
            ->scalar('categoria')
            ->maxLength('categoria', 40)
            ->requirePresence('categoria', 'create')
            ->notEmptyString('categoria');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 60)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->scalar('descricao')
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

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

    public function getAgrupadosPorCategoria($estabelecimentoId)
    {
        $receituarios = $this->find()
            ->where([
                'OR' => [
                    'Receituarios.estabelecimento_id' => $estabelecimentoId,
                    'Receituarios.estabelecimento_id IS NULL'
                ]
            ])
            ->order([
                'Receituarios.categoria' => 'ASC',
                'Receituarios.titulo' => 'ASC'
            ])
            ->all();

        $categoriasPadrao = [
            'personalizado',
            'analgesico',
            'antibiotico',
            'antiinflamatorio',
            'antialergico',
            'corticoide',
            'antifungico',
            'antiviral',
            'antiparasitario',
            'gastrointestinal',
            'respiratorio',
            'topico',
            'uso restrito'
        ];

        $agrupados = [];

        // Inicializa
        foreach ($categoriasPadrao as $cat) {
            $agrupados[$cat] = [];
        }

        // Preenche
        foreach ($receituarios as $r) {
            $categoria = strtolower(trim($r->categoria));

            if (isset($agrupados[$categoria])) {
                $agrupados[$categoria][] = $r;
            }
        }

        return $agrupados;
    }
}
