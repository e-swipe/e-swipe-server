<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MatchesFixture
 *
 */
class MatchesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'matcher_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'matched_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'chat_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_matches_users1_idx' => ['type' => 'index', 'columns' => ['matched_id'], 'length' => []],
            'fk_matches_chat1_idx' => ['type' => 'index', 'columns' => ['chat_id'], 'length' => []],
            'chat_index' => ['type' => 'index', 'columns' => ['chat_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['matcher_id', 'matched_id'], 'length' => []],
            'matches_ibfk_1' => ['type' => 'foreign', 'columns' => ['matcher_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
            'matches_ibfk_2' => ['type' => 'foreign', 'columns' => ['matched_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
            'matches_ibfk_3' => ['type' => 'foreign', 'columns' => ['chat_id'], 'references' => ['chat', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'matcher_id' => 1,
            'matched_id' => 1,
            'chat_id' => 1
        ],
    ];
}
