<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 29/05/2017
 * Time: 02:44
 */

namespace Eswipe\Model;


class ChatCard
{
    public $uuid;
    public $user;
    public $last_message_content = '';


    public function __construct($chatCard)
    {
        $this->uuid = $chatCard->id;
        $this->user = new UserCard($chatCard->matched_users[0]);
        if (!empty($chatCard->messages)) {
            $this->last_message_content = $chatCard->chats_users_messages[0]->content;
        }
    }
}
