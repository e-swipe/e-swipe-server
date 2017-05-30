<?php

namespace Eswipe\Android;


class PushNotification
{

    public $notification;
    public $data;
    public $to;

    public function __construct(Notification $notification, $data, $to)
    {
        $this->notification = $notification;
        $this->data = $data;
        $this->to = $to;
    }
}
