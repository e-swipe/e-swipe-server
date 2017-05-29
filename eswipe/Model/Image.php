<?php

namespace Eswipe\Model;


class Image
{
    public $uuid;
    public $url;
    public $order;

    /**
     * Image constructor.
     * @param array $image
     */
    public function __construct($image)
    {
        $this->uuid = $image->uuid;
        $this->url = $image->url;
        $this->order = $image->_joinData->order;
    }
}
