<?php


namespace Eswipe\Model;


class Message
{
    public $uuid;
    public $user_id;
    public $content;
    public $date;

    public function __construct($message)
    {
        $this->uuid = $message->id;
        $this->user_id = $message->user_id;
        $this->content = $message->content;
        $this->date = $message->created_at;
    }
}
