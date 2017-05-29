<?php

namespace App\Model\Table;

use App\Model\Entity\Accept;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

/**
 * Accepts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $AcceptedUsers
 *
 * @method Accept get($primaryKey, $options = [])
 * @method Accept newEntity($data = null, array $options = [])
 * @method Accept[] newEntities(array $data, array $options = [])
 * @method Accept|bool save(EntityInterface $entity, $options = [])
 * @method Accept patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Accept[] patchEntities($entities, array $data, array $options = [])
 * @method Accept findOrCreate($search, callable $callback = null, $options = [])
 */
class AcceptsTable extends Table
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

        $this->setTable('accepts');
        $this->setDisplayField('accepter_id');
        $this->setPrimaryKey(['accepter_id', 'accepted_id']);

        $this->belongsTo(
            'Users',
            [
                'foreignKey' => 'accepter_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'AcceptedUsers',
            [
                'className' => 'Users',
                'targetTable' => 'users',
                'foreignKey' => 'accepted_id',
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
        $rules->add($rules->existsIn('accepter_id', 'Users'));
        $rules->add($rules->existsIn('accepted_id', 'Users'));

        return $rules;
    }
}
