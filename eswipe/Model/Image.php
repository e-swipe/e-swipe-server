<?php

namespace Eswipe\Model;


class Image
{
    public $uuid;
    public $url;
    public $order;

    /**
     * Image constructor.
     * @param \App\Model\Entity\Image $image
     */
    public function __construct(\App\Model\Entity\Image $image)
    {
        $this->uuid = $image->uuid;
        $this->url = $image->url;
        $this->order = $image->_joinData->order;
    }
}
