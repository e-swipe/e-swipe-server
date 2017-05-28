<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DeclinesFixture
 *
 */
class DeclinesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'decliner_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'declined_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_accept_users2_idx' => ['type' => 'index', 'columns' => ['declined_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['decliner_id', 'declined_id'], 'length' => []],
            'declines_ibfk_1' => ['type' => 'foreign', 'columns' => ['decliner_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
            'declines_ibfk_2' => ['type' => 'foreign', 'columns' => ['declined_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
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
            'decliner_id' => 1,
            'declined_id' => 1
        ],
    ];
}
