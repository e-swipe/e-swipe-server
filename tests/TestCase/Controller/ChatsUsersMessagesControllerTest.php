<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ChatsUsersMessagesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ChatsUsersMessagesController Test Case
 */
class ChatsUsersMessagesControllerTest extends IntegrationTestCase
{

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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
