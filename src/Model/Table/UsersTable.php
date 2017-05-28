<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Facebooks
 * @property \Cake\ORM\Association\BelongsTo $Instances
 * @property \Cake\ORM\Association\BelongsTo $Genders
 * @property \Cake\ORM\Association\HasMany $ChatsUsersMessages
 * @property \Cake\ORM\Association\HasMany $EventsUsersAccept
 * @property \Cake\ORM\Association\HasMany $EventsUsersDeny
 * @property \Cake\ORM\Association\HasMany $Sessions
 * @property \Cake\ORM\Association\HasMany $UsersGendersLookingFor
 * @property \Cake\ORM\Association\BelongsToMany $Images
 * @property \Cake\ORM\Association\BelongsToMany $LookingFor
 * @property \Cake\ORM\Association\BelongsToMany $Interests
 *
 * @method User get($primaryKey, $options = [])
 * @method User newEntity($data = null, array $options = [])
 * @method User[] newEntities(array $data, array $options = [])
 * @method User|bool save(EntityInterface $entity, $options = [])
 * @method User patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method User[] patchEntities($entities, array $data, array $options = [])
 * @method User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Genders', [
            'foreignKey' => 'gender_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsToMany('LookingFor', [
            'className'=>'Genders',
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'gender_id',
            'joinTable' => 'users_genders_looking_for'
        ]);

        $this->belongsToMany('Events', [
            'className'=>'Events',
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'event_id',
            'joinTable' => 'events_users_accept'
        ]);
        $this->hasMany('ChatsUsersMessages', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('EventsUsersAccept', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('EventsUsersDeny', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Sessions', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UsersGendersLookingFor', [
            'foreignKey' => 'user_id'
        ]);

        //TODO: https://book.cakephp.org/3.0/fr/orm/associations.html#associations-belongstomany
        //order by asc
        $this->belongsToMany('Images', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'image_id',
            'joinTable' => 'images_users',
            'sort' => ['ImagesUsers.order' => 'ASC']
        ]);
        $this->belongsToMany('Interests', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'interest_id',
            'joinTable' => 'interests_users'
        ]);
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
            ->allowEmpty('id', 'create');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('instance_id', 'create')
            ->notEmpty('instance_id');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->requirePresence('firstname', 'create')
            ->notEmpty('firstname');

        $validator
            ->requirePresence('lastname', 'create')
            ->notEmpty('lastname');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->date('date_of_birth')
            ->requirePresence('date_of_birth', 'create')
            ->notEmpty('date_of_birth');

        $validator
            ->numeric('latitude')
            ->allowEmpty('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmpty('longitude');

        $validator
            ->boolean('is_visible')
            ->requirePresence('is_visible', 'create')
            ->notEmpty('is_visible');

        $validator
            ->integer('min_age')
            ->requirePresence('min_age', 'create')
            ->notEmpty('min_age');

        $validator
            ->integer('max_age')
            ->requirePresence('max_age', 'create')
            ->notEmpty('max_age');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['gender_id'], 'Genders'));

        return $rules;
    }
}
