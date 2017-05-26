<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChatsUsersMessagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChatsUsersMessagesTable Test Case
 */
class ChatsUsersMessagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChatsUsersMessagesTable
     */
    public $ChatsUsersMessages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.chats_users_messages',
        'app.chat',
        'app.users',
        'app.facebooks',
        'app.instances',
        'app.genders',
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
        $config = TableRegistry::exists('ChatsUsersMessages') ? [] : ['className' => 'App\Model\Table\ChatsUsersMessagesTable'];
        $this->ChatsUsersMessages = TableRegistry::get('ChatsUsersMessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ChatsUsersMessages);

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
