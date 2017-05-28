<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AcceptsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AcceptsTable Test Case
 */
class AcceptsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AcceptsTable
     */
    public $Accepts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.accepts',
        'app.users',
        'app.accepted',
        'app.declined',
        'app.matched',
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
        'app.chat',
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
        $config = TableRegistry::exists('Accepts') ? [] : ['className' => 'App\Model\Table\AcceptsTable'];
        $this->Accepts = TableRegistry::get('Accepts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Accepts);

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
