<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MatchesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MatchesTable Test Case
 */
class MatchesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MatchesTable
     */
    public $Matches;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.matches',
        'app.users',
        'app.accepted',
        'app.declined',
        'app.matched',
        'app.chat',
        'app.genders',
        'app.users_genders_looking_for',
        'app.looking_for',
        'app.events',
        'app.events_users_accept',
        'app.events_users_deny',
        'app.images',
        'app.events_images',
        'app.images_users',
        'app.interests',
        'app.events_interests',
        'app.chats_users_messages',
        'app.sessions',
        'app.interests_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Matches') ? [] : ['className' => 'App\Model\Table\MatchesTable'];
        $this->Matches = TableRegistry::get('Matches', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Matches);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
