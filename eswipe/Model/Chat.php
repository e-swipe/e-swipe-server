<?php


namespace Eswipe\Model;


class Chat
{
    public $uuid;
    public $user;
    public $last_message_content = '';
    public $messages;


    public function __construct($chat)
    {
        $this->uuid = $chat->id;
        $this->user = new UserCard($chat->matched_users[0]);
        if (!empty($chat->chats_users_messages)) {
            $this->last_message_content = $chat->chats_users_messages[0]->content;
        }
        $this->messages = [];
        foreach ($chat->chats_users_messages as $message) {
            $this->messages[] = new Message($message);
        }
    }
}
