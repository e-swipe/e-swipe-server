<?php

namespace App\Controller;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\Response;
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
        $email = $this->request->getParam('email');
        $password = $this->request->getParam('password');
        $instanceId = $this->request->getParam('instance_id');

        if (!Validation::email($email) || !is_string($password) || strlen($password) > 250 || !is_string($instanceId) || strlen($instanceId) > 250) {
            $fieldError = new FieldError();
            $fieldError->code = 422;

            if (!Validation::email($email)) {
                $fieldError->field = 'email';
                $fieldError->message = 'not an email';
            } else if (!is_string($password) || strlen($password) > 250) {
                $fieldError->field = 'password';
                $fieldError->message = 'unauthorized password type';
            } else if (!is_string($instanceId) || strlen($instanceId) > 250) {
                $fieldError->field = 'instance_id';
                $fieldError->message = 'unauthorized instance_id type';
            }

            $response = (new Response())->withStatus(422, 'Unprocessable Entity')
                ->withType('json')
                ->withStringBody(json_encode($fieldError));
            return $response;
        }

        $hashedpassword = (new DefaultPasswordHasher())->hash($password);
        $this->loadModel('Users');

        $user = $this->Users->find()
            ->select(['id', 'email', 'password'])
            ->where(['email' => $email,
                'password' => $hashedpassword])
            ->first();

        if ($user) {
            $this->loadModel('Sessions');
            $uuid = Text::uuid();

            $session = $this->Sessions->newEntity();
            $session->string_uuid = $uuid;
            $this->Sessions->save($session);

            $token = new Token();
            $token->auth = $uuid;

            return (new Response())->withStatus(200, 'OK')
                ->withType('json')->withBody(json_encode($token));
        } else {
            $response = (new Response())->withStatus(401, 'Unauthorized');
            return $response;
        }
    }

    /**
     * Login via facebook
     */
    public function facebookLogin()
    {

    }

    /**
     * Logout
     */
    public function logout()
    {

    }
}
