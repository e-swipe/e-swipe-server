<?php

namespace App\Model\Table;

use App\Model\Entity\EventsUsersAccept;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

/**
 * EventsUsersAccept Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Events
 *
 * @method EventsUsersAccept get($primaryKey, $options = [])
 * @method EventsUsersAccept newEntity($data = null, array $options = [])
 * @method EventsUsersAccept[] newEntities(array $data, array $options = [])
 * @method EventsUsersAccept|bool save(EntityInterface $entity, $options = [])
 * @method EventsUsersAccept patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method EventsUsersAccept[] patchEntities($entities, array $data, array $options = [])
 * @method EventsUsersAccept findOrCreate($search, callable $callback = null, $options = [])
 */
class EventsUsersAcceptTable extends Table
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

        $this->setTable('events_users_accept');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey(['user_id', 'event_id']);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Events', [
            'foreignKey' => 'event_id',
            'joinType' => 'INNER',
        ]);

        $this->addBehavior('CounterCache', [
            'Events' => [
                'users_count',
            ],
        ]);
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['event_id'], 'Events'));

        return $rules;
    }
}
