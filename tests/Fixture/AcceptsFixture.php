<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AcceptsFixture
 *
 */
class AcceptsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'accepter_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'accepted_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_accept_users2_idx' => ['type' => 'index', 'columns' => ['accepted_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['accepter_id', 'accepted_id'], 'length' => []],
            'accepts_ibfk_1' => ['type' => 'foreign', 'columns' => ['accepter_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
            'accepts_ibfk_2' => ['type' => 'foreign', 'columns' => ['accepted_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
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
            'accepter_id' => 1,
            'accepted_id' => 1
        ],
    ];
}
