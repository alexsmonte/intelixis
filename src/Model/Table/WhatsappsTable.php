<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Whatsapps Model
 *
 * @property \App\Model\Table\ContasTable&\Cake\ORM\Association\BelongsTo $Contas
 *
 * @method \App\Model\Entity\Whatsapp newEmptyEntity()
 * @method \App\Model\Entity\Whatsapp newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Whatsapp> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Whatsapp get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Whatsapp findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Whatsapp patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Whatsapp> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Whatsapp|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Whatsapp saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Whatsapp>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Whatsapp>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Whatsapp>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Whatsapp> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Whatsapp>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Whatsapp>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Whatsapp>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Whatsapp> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WhatsappsTable extends Table
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

        $this->setTable('whatsapps');
        $this->setDisplayField('phone_number');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Contas', [
            'foreignKey' => 'conta_id',
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
            ->integer('conta_id')
            ->notEmptyString('conta_id');

        $validator
            ->notEmptyString('estabelecimento_id');

        $validator
            ->scalar('phone_number')
            ->maxLength('phone_number', 60)
            ->requirePresence('phone_number', 'create')
            ->notEmptyString('phone_number')
            ->add('phone_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('business_account')
            ->maxLength('business_account', 60)
            ->requirePresence('business_account', 'create')
            ->notEmptyString('business_account');

        $validator
            ->scalar('display_phone_number')
            ->maxLength('display_phone_number', 40)
            ->requirePresence('display_phone_number', 'create')
            ->notEmptyString('display_phone_number');

        $validator
            ->scalar('access_token')
            ->allowEmptyString('access_token');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->allowEmptyString('nome');

        $validator
            ->scalar('pin')
            ->maxLength('pin', 10)
            ->allowEmptyString('pin');

        $validator
            ->scalar('senha_chatwoot')
            ->maxLength('senha_chatwoot', 20)
            ->allowEmptyString('senha_chatwoot');

        $validator
            ->scalar('token_empresa')
            ->maxLength('token_empresa', 255)
            ->allowEmptyString('token_empresa');

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
        $rules->add($rules->isUnique(['phone_number']), ['errorField' => 'phone_number']);
        $rules->add($rules->existsIn(['conta_id'], 'Contas'), ['errorField' => 'conta_id']);
        $rules->add($rules->existsIn(['estabelecimento_id'], 'Estabelecimentos'), ['errorField' => 'estabelecimento_id']);

        return $rules;
    }
}
