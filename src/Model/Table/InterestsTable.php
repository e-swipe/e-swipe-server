<?php
namespace App\Model\Table;

use App\Model\Entity\Interest;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Interests Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Events
 * @property \Cake\ORM\Association\BelongsToMany $Users
 *
 * @method Interest get($primaryKey, $options = [])
 * @method Interest newEntity($data = null, array $options = [])
 * @method Interest[] newEntities(array $data, array $options = [])
 * @method Interest|bool save(EntityInterface $entity, $options = [])
 * @method Interest patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Interest[] patchEntities($entities, array $data, array $options = [])
 * @method Interest findOrCreate($search, callable $callback = null, $options = [])
 */
class InterestsTable extends Table
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

        $this->setTable('interests');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Events', [
            'foreignKey' => 'interest_id',
            'targetForeignKey' => 'event_id',
            'joinTable' => 'events_interests'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'interest_id',
            'targetForeignKey' => 'user_id',
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
            ->allowEmpty('id', 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

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
