<?php

namespace App\Model\Table;

use App\Model\Entity\Session;
use Cake\Database\Schema\TableSchema;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sessions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method Session get($primaryKey, $options = [])
 * @method Session newEntity($data = null, array $options = [])
 * @method Session[] newEntities(array $data, array $options = [])
 * @method Session|bool save(EntityInterface $entity, $options = [])
 * @method Session patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Session[] patchEntities($entities, array $data, array $options = [])
 * @method Session findOrCreate($search, callable $callback = null, $options = [])
 */
class SessionsTable extends Table
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

        $this->setTable('sessions');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['uuid']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Override this function in order to alter the schema used by this table.
     * This function is only called after fetching the schema out of the database.
     * If you wish to provide your own schema to this table without touching the
     * database, you can override schema() or inject the definitions though that
     * method.
     *
     * ### Example:
     *
     * ```
     * protected function _initializeSchema(\Cake\Database\Schema\TableSchema $schema) {
     *  $schema->columnType('preferences', 'json');
     *  return $schema;
     * }
     * ```
     *
     * @param \Cake\Database\Schema\TableSchema $schema The table definition fetched from database.
     * @return \Cake\Database\Schema\TableSchema the altered schema
     */
    protected function _initializeSchema(TableSchema $schema)
    {
        $schema->columnType('uuid', 'uuidtype');
        return $schema;
    }
}
