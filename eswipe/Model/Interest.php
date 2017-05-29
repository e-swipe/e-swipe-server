<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 29/05/2017
 * Time: 21:05
 */

namespace Eswipe\Model;


class Interest
{
    public $uuid;
    public $name;

    public function __construct(\App\Model\Entity\Interest $interest)
    {
        $this->uuid = $interest->id;
        $this->name = $interest->name;
    }
}
