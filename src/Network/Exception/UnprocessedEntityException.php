<?php

namespace App\Network\Exception;

use Cake\Network\Exception\HttpException;

/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 06:29
 */
class UnprocessedEntityException extends HttpException
{
    /**
     * Constructor
     *
     * @param string|null $message If no message is given 'Unprocessed Entity' will be the message
     * @param int $code Status code, defaults to 422
     */
    public function __construct($message = null, $code = 422)
    {
        if (empty($message)) {
            $message = 'Unprocessed Entity';
        }
        parent::__construct($message, $code);
    }
}
