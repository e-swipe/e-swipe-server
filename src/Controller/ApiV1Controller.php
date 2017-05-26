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

class ApiV1Controller extends Controller
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', ['authenticate' => [
            'Api' => [
                'userModel' => 'sessions',
                'fields' => ['token' => 'auth']
            ]
        ]]);
        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index']);
    }


    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }

    }

}
