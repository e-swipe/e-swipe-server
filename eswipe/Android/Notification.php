<?php

namespace Eswipe\Android;


class Notification
{
    public $title;
    public $body;
    public $click_action;

    /**
     * Notification constructor.
     * @param $title
     * @param $body
     * @param $click_action
     */
    public function __construct($title, $body, $click_action)
    {
        $this->title = $title;
        $this->body = $body;
        $this->click_action = $click_action;
    }

}
