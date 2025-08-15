<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuariosWhatsapps Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\WhatsappsTable&\Cake\ORM\Association\BelongsTo $Whatsapps
 *
 * @method \App\Model\Entity\UsuariosWhatsapp newEmptyEntity()
 * @method \App\Model\Entity\UsuariosWhatsapp newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UsuariosWhatsapp> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosWhatsapp get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UsuariosWhatsapp findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UsuariosWhatsapp patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UsuariosWhatsapp> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosWhatsapp|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UsuariosWhatsapp saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UsuariosWhatsapp>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UsuariosWhatsapp>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UsuariosWhatsapp>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UsuariosWhatsapp> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UsuariosWhatsapp>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UsuariosWhatsapp>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UsuariosWhatsapp>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UsuariosWhatsapp> deleteManyOrFail(iterable $entities, array $options = [])
 */
class UsuariosWhatsappsTable extends Table
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

        $this->setTable('usuarios_whatsapps');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Whatsapps', [
            'foreignKey' => 'whatsapp_id',
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
            ->integer('usuarios_id')
            ->notEmptyString('usuarios_id');

        $validator
            ->integer('whatsapps_id')
            ->notEmptyString('whatsapps_id');

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
        $rules->add($rules->existsIn(['usuarios_id'], 'Usuarios'), ['errorField' => 'usuarios_id']);
        $rules->add($rules->existsIn(['whatsapps_id'], 'Whatsapps'), ['errorField' => 'whatsapps_id']);

        return $rules;
    }
}
