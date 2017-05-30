<?php

namespace Eswipe\Android;


class MessageData
{
    public $uuid;
    public $message;
    //public $image_url;

    /**
     * MessageData constructor.
     * @param $uuid
     * @param $message
     */
    public function __construct($uuid, $message = null)
    {
        $this->uuid = $uuid;
        $this->message = $message;
        // TODO(fati): integrer d'abord dans android studio
        //$this->image_url = $image_url;
    }

}
