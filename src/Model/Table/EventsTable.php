<?php

namespace App\Model\Table;

use App\Model\Entity\Event;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Events Model
 *
 * @property \Cake\ORM\Association\HasMany $EventsUsersAccept
 * @property \Cake\ORM\Association\HasMany $EventsUsersDeny
 * @property \Cake\ORM\Association\BelongsToMany $Images
 * @property \Cake\ORM\Association\BelongsToMany $Interests
 *
 * @method Event get($primaryKey, $options = [])
 * @method Event newEntity($data = null, array $options = [])
 * @method Event[] newEntities(array $data, array $options = [])
 * @method Event|bool save(EntityInterface $entity, $options = [])
 * @method Event patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Event[] patchEntities($entities, array $data, array $options = [])
 * @method Event findOrCreate($search, callable $callback = null, $options = [])
 */
class EventsTable extends Table
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

        $this->setTable('events');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'EventsUsersAccept',
            [
                'foreignKey' => 'event_id',
            ]
        );
        $this->hasMany(
            'EventsUsersDeny',
            [
                'foreignKey' => 'event_id',
            ]
        );
        $this->belongsToMany(
            'Images',
            [
                'foreignKey' => 'event_id',
                'targetForeignKey' => 'image_id',
                'joinTable' => 'events_images',
            ]
        );
        $this->belongsToMany(
            'Interests',
            [
                'foreignKey' => 'event_id',
                'targetForeignKey' => 'interest_id',
                'joinTable' => 'events_interests',
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->dateTime('date_begin')
            ->requirePresence('date_begin', 'create')
            ->notEmpty('date_begin');

        $validator
            ->dateTime('date_end')
            ->requirePresence('date_end', 'create')
            ->notEmpty('date_end');

        $validator
            ->numeric('latitude')
            ->allowEmpty('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmpty('longitude');

        $validator
            ->integer('is_visible')
            ->requirePresence('is_visible', 'create')
            ->notEmpty('is_visible');

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
