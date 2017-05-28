<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 22:58
 */

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
    public function __construct(array $image)
    {
        $this->uuid = $image['uuid'];
        $this->url = $image['url'];
        $this->order = $image['_joinData']['order'];
    }
}
