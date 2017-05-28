<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ImagesUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ImagesUsersTable Test Case
 */
class ImagesUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ImagesUsersTable
     */
    public $ImagesUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.images_users',
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
        $config = TableRegistry::exists('ImagesUsers') ? [] : ['className' => 'App\Model\Table\ImagesUsersTable'];
        $this->ImagesUsers = TableRegistry::get('ImagesUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ImagesUsers);

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
