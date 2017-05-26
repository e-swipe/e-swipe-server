<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SessionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SessionsTable Test Case
 */
class SessionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SessionsTable
     */
    public $Sessions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sessions',
        'app.users',
        'app.genders',
        'app.chats_users_messages',
        'app.chat',
        'app.events_users_accept',
        'app.events_users_deny',
        'app.users_genders_looking_for',
        'app.images',
        'app.images_users',
        'app.interests',
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
        $config = TableRegistry::exists('Sessions') ? [] : ['className' => 'App\Model\Table\SessionsTable'];
        $this->Sessions = TableRegistry::get('Sessions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sessions);

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
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
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
