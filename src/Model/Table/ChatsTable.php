<?php

namespace App\Model\Table;

use App\Model\Entity\Chat;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Chats Model
 *
 * @property \Cake\ORM\Association\HasMany $ChatsUsersMessages
 * @property \Cake\ORM\Association\HasMany $Matches
 *
 * @method Chat get($primaryKey, $options = [])
 * @method Chat newEntity($data = null, array $options = [])
 * @method Chat[] newEntities(array $data, array $options = [])
 * @method Chat|bool save(EntityInterface $entity, $options = [])
 * @method Chat patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Chat[] patchEntities($entities, array $data, array $options = [])
 * @method Chat findOrCreate($search, callable $callback = null, $options = [])
 */
class ChatsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('chats');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'ChatsUsersMessages',
            [
                'foreignKey' => 'chat_id',
            ]
        );
        $this->hasMany(
            'Matches',
            [
                'foreignKey' => 'chat_id',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['id']));

        return $rules;
    }
}
