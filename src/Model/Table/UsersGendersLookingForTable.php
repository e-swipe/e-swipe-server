<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

/**
 * UsersGendersLookingFor Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Genders
 *
 * @method \App\Model\Entity\UsersGendersLookingFor get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsersGendersLookingFor newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsersGendersLookingFor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsersGendersLookingFor|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersGendersLookingFor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsersGendersLookingFor[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsersGendersLookingFor findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersGendersLookingForTable extends Table
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

        $this->setTable('users_genders_looking_for');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey(['user_id', 'gender_id']);

        $this->belongsTo(
            'Users',
            [
                'foreignKey' => 'user_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Genders',
            [
                'foreignKey' => 'gender_id',
                'joinType' => 'INNER',
            ]
        );
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
        $rules->add($rules->existsIn(['gender_id'], 'Genders'));

        return $rules;
    }
}
