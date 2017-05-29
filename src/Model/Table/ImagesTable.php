<?php

namespace App\Model\Table;

use App\Model\Entity\Image;
use Cake\Database\Schema\TableSchema;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Images Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Events
 * @property \Cake\ORM\Association\BelongsToMany $Users
 *
 * @method Image get($primaryKey, $options = [])
 * @method Image newEntity($data = null, array $options = [])
 * @method Image[] newEntities(array $data, array $options = [])
 * @method Image|bool save(EntityInterface $entity, $options = [])
 * @method Image patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Image[] patchEntities($entities, array $data, array $options = [])
 * @method Image findOrCreate($search, callable $callback = null, $options = [])
 */
class ImagesTable extends Table
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

        $this->setTable('images');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany(
            'Events',
            [
                'foreignKey' => 'image_id',
                'targetForeignKey' => 'event_id',
                'joinTable' => 'events_images',
            ]
        );
        $this->belongsToMany(
            'Users',
            [
                'foreignKey' => 'image_id',
                'targetForeignKey' => 'user_id',
                'joinTable' => 'images_users',
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
            ->requirePresence('url', 'create')
            ->notEmpty('url');

        $validator
            ->requirePresence('uuid', 'create')
            ->notEmpty('uuid')
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
        $rules->add($rules->isUnique(['id']));

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
     * @param TableSchema $schema The table definition fetched from database.
     * @return TableSchema the altered schema
     */
    protected function _initializeSchema(TableSchema $schema)
    {
        $schema->columnType('uuid', 'uuidtype');

        return $schema;
    }
}
