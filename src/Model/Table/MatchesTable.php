<?php

namespace App\Model\Table;

use App\Model\Entity\Match;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

/**
 * Matches Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $MatchedUsers
 * @property \Cake\ORM\Association\BelongsTo $Chats
 *
 * @method Match get($primaryKey, $options = [])
 * @method Match newEntity($data = null, array $options = [])
 * @method Match[] newEntities(array $data, array $options = [])
 * @method Match|bool save(EntityInterface $entity, $options = [])
 * @method Match patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Match[] patchEntities($entities, array $data, array $options = [])
 * @method Match findOrCreate($search, callable $callback = null, $options = [])
 */
class MatchesTable extends Table
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

        $this->setTable('matches');
        $this->setDisplayField('matcher_id');
        $this->setPrimaryKey(['matcher_id', 'matched_id']);

        $this->belongsTo(
            'Users',
            [
                'className' => 'Users',
                'foreignKey' => 'matcher_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'MatchedUsers',
            [
                'className' => 'Users',
                'foreignKey' => 'matched_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Chats',
            [
                'foreignKey' => 'chat_id',
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
        $rules->add($rules->existsIn(['matcher_id'], 'Users'));
        $rules->add($rules->existsIn(['matched_id'], 'Users'));
        $rules->add($rules->existsIn(['chat_id'], 'Chats'));

        return $rules;
    }
}
