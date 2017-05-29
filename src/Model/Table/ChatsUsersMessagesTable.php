<?php

namespace App\Model\Table;

use App\Model\Entity\ChatsUsersMessage;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChatsUsersMessages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Chat
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method ChatsUsersMessage get($primaryKey, $options = [])
 * @method ChatsUsersMessage newEntity($data = null, array $options = [])
 * @method ChatsUsersMessage[] newEntities(array $data, array $options = [])
 * @method ChatsUsersMessage|bool save(EntityInterface $entity, $options = [])
 * @method ChatsUsersMessage patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method ChatsUsersMessage[] patchEntities($entities, array $data, array $options = [])
 * @method ChatsUsersMessage findOrCreate($search, callable $callback = null, $options = [])
 */
class ChatsUsersMessagesTable extends Table
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

        $this->setTable('chats_users_messages');
        $this->setDisplayField('id');
        $this->setPrimaryKey(['id', 'chat_id', 'user_id']);

        $this->belongsTo(
            'Chats',
            [
                'foreignKey' => 'chat_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Users',
            [
                'foreignKey' => 'user_id',
                'joinType' => 'INNER',
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

        $validator
            ->requirePresence('content', 'create')
            ->notEmpty('content');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmpty('created_at');

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
        $rules->add($rules->existsIn(['chat_id'], 'Chats'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
