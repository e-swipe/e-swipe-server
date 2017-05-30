<?php

namespace Eswipe\Android;


class MessageData
{
    public $uuid;
    //public $image_url;

    /**
     * MessageData constructor.
     * @param $uuid
     */
    public function __construct($uuid)
    {
        $this->uuid = $uuid;
        // TODO(fati): integrer d'abord dans android studio
        //$this->image_url = $image_url;
    }

}
