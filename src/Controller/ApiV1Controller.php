<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 26/05/2017
 * Time: 19:04
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class ApiV1Controller extends AppController
{
    public function initialize()
    {
        parent::initialize();
        /*$this->loadComponent('Auth', ['authenticate' => [
            'Api' => [
                'userModel' => 'sessions',
                'fields' => ['token' => 'auth']
            ]
        ]]);*/
    }
}
