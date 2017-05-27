<?php

namespace App\Controller;

use App\Http\JsonBodyResponse;
use App\Model\Entity\User;
use App\Network\exception\UnprocessedEntityException;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Text;
use Cake\Validation\Validation;
use Eswipe\Model\FieldError;
use Eswipe\Model\Token;

/**
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\SessionsTable $Sessions
 */
class LoginController extends AppController
{
    /**
     * Login via default email, password
     */
    public function basic()
    {
        $email = $this->request->getQuery('email');
        $password = $this->request->getQuery('password');
        $instanceId = $this->request->getQuery('instance_id');

        if (!Validation::email($email) || !is_string($password) || strlen($password) > 250 || !is_string($instanceId) || strlen($instanceId) > 250) {

            $message = null;
            if (!Validation::email($email)) {
                $message = 'not an "email"';
            } else if (!is_string($password) || strlen($password) > 250) {
                $message = 'unauthorized "password" type';
            } else if (!is_string($instanceId) || strlen($instanceId) > 250) {
                $message = 'unauthorized "instance_id" type';
            }

            throw new UnprocessedEntityException($message);
        }

        $this->loadModel('Users');

        /** @var User $user */
        $user = $this->Users->find()
            ->select(['id', 'email', 'password'])
            ->where(['email' => $email])
            ->first();

        if (is_null($user) || !(new DefaultPasswordHasher())->check($password, $user->password)) {
            throw new UnauthorizedException();
        }

        $user->instance_id = $instanceId;

        $this->loadModel('Sessions');

        $session = $this->Sessions->find('all', ['condition' => ['user_id' => $user->id]])->first();

        if (is_null($session)) {
            $session = $this->Sessions->newEntity();
            $session->uuid = Text::uuid();
            $session->user = $user;
            $this->Sessions->save($session);
        }

        $token = new Token();
        $token->auth = $session->uuid;


        return JsonBodyResponse::okResponse($this->response, $token);
    }

    /**
     * Login via facebook
     */
    public function facebook()
    {
        //TODO

        /*$facebook_auth = $this->request->getQuery('facebook_auth');
        $instanceId = $this->request->getQuery('instance_id');

        //TODO
        debug($facebook_auth);
        debug(Configure::read('facebook.key'));

        $client = new Client();
        $answer = $client->get('https://graph.facebook.com/debug_token', ['
        input_token' => $facebook_auth, 'access_token' => Configure::read('facebook.key')]);

        debug($answer);
        debug($this->request->getData());
        $user = $this->request->getData();
        /*
         * (
                [first_name] => string
                [last_name] => string
                [date_of_birth] => string
                [gender] => male
                [email] => user@example.com
            )

         */
        return $this->response;
    }
}
