<?php

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
