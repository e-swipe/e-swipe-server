<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 00:03
 */

namespace Eswipe\Model;


class Token
{
    public $auth;

    /**
     * Token constructor.
     * @param $auth
     */
    public function __construct($auth = null)
    {
        $this->auth = $auth;
    }


}
