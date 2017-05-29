<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

/**
 * Declines Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $DeclinedUsers
 *
 * @method \App\Model\Entity\Decline get($primaryKey, $options = [])
 * @method \App\Model\Entity\Decline newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Decline[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Decline|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Decline patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Decline[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Decline findOrCreate($search, callable $callback = null, $options = [])
 */
class DeclinesTable extends Table
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

        $this->setTable('declines');
        $this->setDisplayField('decliner_id');
        $this->setPrimaryKey(['decliner_id', 'declined_id']);

        $this->belongsTo(
            'Users',
            [
                'foreignKey' => 'decliner_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'DeclinedUsers',
            [
                'className' => 'Users',
                'targetTable' => 'users',
                'foreignKey' => 'declined_id',
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
        $rules->add($rules->existsIn(['decliner_id'], 'Users'));
        $rules->add($rules->existsIn(['declined_id'], 'Users'));

        return $rules;
    }
}
