<?php

namespace App\Model\Table;

use App\Model\Entity\ImagesUser;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ImagesUsers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Images
 *
 * @method ImagesUser get($primaryKey, $options = [])
 * @method ImagesUser newEntity($data = null, array $options = [])
 * @method ImagesUser[] newEntities(array $data, array $options = [])
 * @method ImagesUser|bool save(EntityInterface $entity, $options = [])
 * @method ImagesUser patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method ImagesUser[] patchEntities($entities, array $data, array $options = [])
 * @method ImagesUser findOrCreate($search, callable $callback = null, $options = [])
 */
class ImagesUsersTable extends Table
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

        $this->setTable('images_users');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey(['user_id', 'image_id']);

        $this->belongsTo(
            'Users',
            [
                'foreignKey' => 'user_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Images',
            [
                'foreignKey' => 'image_id',
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
            ->integer('order')
            ->requirePresence('order', 'create')
            ->notEmpty('order');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['image_id'], 'Images'));

        return $rules;
    }
}
