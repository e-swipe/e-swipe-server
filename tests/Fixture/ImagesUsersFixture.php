<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ImagesUsersFixture
 *
 */
class ImagesUsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'user_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'image_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'order' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_users_has_images_images1_idx' => ['type' => 'index', 'columns' => ['image_id'], 'length' => []],
            'fk_users_has_images_users1_idx' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'image_order' => ['type' => 'index', 'columns' => ['order'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['user_id', 'image_id'], 'length' => []],
            'images_users_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'images_users_ibfk_2' => ['type' => 'foreign', 'columns' => ['image_id'], 'references' => ['images', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'user_id' => 1,
            'image_id' => 1,
            'order' => 1
        ],
    ];
}
