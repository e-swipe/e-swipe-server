<?php

namespace App\Controller;

use App\Http\JsonBodyResponse;
use App\Model\Entity\User;
use App\Network\Exception\UnprocessedEntityException;
use App\Validator\DataValidator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\I18n\FrozenDate;
use Cake\Log\Log;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Cake\Utility\Text;
use Cake\Validation\Validation;
use Eswipe\Model\Token;

/**
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\SessionsTable $Sessions
 * @property \App\Model\Table\SessionsTable Genders
 */
class LoginController extends AppController
{
    /**
     * Login via default email, password
     */
    public function basic()
    {
        $this->loadModel('Users');
        $this->loadModel('Sessions');

        $email = $this->request->getQuery('email');
        $password = $this->request->getQuery('password');
        $instanceId = $this->request->getQuery('instance_id');

        if (!Validation::email($email) || !is_string($password) || strlen($password) > 250 || !is_string(
                $instanceId
            ) || strlen($instanceId) > 250
        ) {

            $message = null;
            if (!Validation::email($email)) {
                $message = 'not an "email"';
            } else {
                if (!is_string($password) || strlen($password) > 250) {
                    $message = 'unauthorized "password" type';
                } else {
                    if (!is_string($instanceId) || strlen($instanceId) > 250) {
                        $message = 'unauthorized "instance_id" type';
                    }
                }
            }

            throw new UnprocessedEntityException($message);
        }


        /** @var User $user */
        $user = $this->Users->find()
            ->select(['id', 'password'])
            ->where(['email' => $email])
            ->first();

        if (is_null($user) || !(new DefaultPasswordHasher())->check($password, $user->password)) {
            throw new UnauthorizedException();
        }


        $user->instance_id = $instanceId;
        $this->Users->save($user);

        $session = $this->Sessions->findByUserId($user->id)->first();

        if (is_null($session)) {
            $session = $this->Sessions->newEntity();
            $session->uuid = Text::uuid();
            $session->user = $user;
            $this->Sessions->save($session);
        }

        $token = new Token($session->uuid);

        Log::info('[LOGIN][default][201]['.$user->id.']'.$session->uuid.' : '.$user->id);

        return JsonBodyResponse::okResponse($this->response, $token);
    }

    /**
     * Login via facebook
     */
    public function facebook()
    {
        $this->loadModel('Users');
        $this->loadModel('Genders');
        $this->loadModel('Sessions');

        $facebookAuth = $this->request->getQuery('facebook_auth');
        $instanceId = $this->request->getQuery('instance_id');
        $userData = $this->request->getData();
        $accessToken = Configure::read('facebook.id')."|".Configure::read('facebook.key');

        $message = DataValidator::validateLoginFacebook($this->request);
        if (!is_null($message)) {
            throw new UnprocessedEntityException($message);
        }

        $answer = (new Client())->get(
            'https://graph.facebook.com/debug_token',
            ['input_token' => $facebookAuth, 'access_token' => $accessToken]
        );

        $facebookData = $answer->json;
        if (array_key_exists('error', $facebookData)) {
            debug($facebookData);
            throw new UnauthorizedException($facebookData['error']['message']);
        }

        $facebookId = $facebookData['data']['user_id'];

        $user = $this->Users->findByFacebookId($facebookId)->first();

        if ($user) { // if user exists
            $user->firstname = $userData['first_name'];
            $user->lastname = $userData['last_name'];
            $user->email = $userData['email'];
            $user->date_of_birth = FrozenDate::parseDate($userData['date_of_birth']);
            $user->gender = $this->Genders->find('all', ['name' => $userData['gender']])->first();
            $user->instance_id = $instanceId;

            $this->Users->save($user);

            $session = $this->Sessions->findByUserId($user->id)->first();
            if (is_null($session)) {
                $session = $this->Sessions->newEntity();
                $session->uuid = Text::uuid();
                $session->user = $user;
                $this->Sessions->save($session);
            }

            $token = new Token($session->uuid);

            Log::info('[LOGIN][facebook][200]['.$user->id.']'.$session->uuid.":".$user->facebook_id);

            return JsonBodyResponse::okResponse($this->response, $token);
        }

        if ($this->Users->findByEmail($userData['email'])->first()) {
            throw new UnauthorizedException('connect by email');
        }

        // new user
        $user = $this->Users->newEntity();
        $user->firstname = $userData['first_name'];
        $user->lastname = $userData['last_name'];
        $user->facebook_id = $facebookData['data']['user_id'];
        $user->email = $userData['email'];
        $user->password = Security::randomBytes(32);
        $user->instance_id = $instanceId;
        $user->description = '';
        $user->date_of_birth = FrozenDate::parseDate($userData['date_of_birth']);
        $user->gender = $this->Genders->find('all', ['name' => $userData['gender']])->first();

        $user->max_age = $user->date_of_birth->age + 10;
        $user->min_age = $user->date_of_birth->age > 28 ? $user->date_of_birth->age - 10 : 18;

        $this->Users->save($user);

        $session = $this->Sessions->newEntity();
        $session->uuid = Text::uuid();
        $session->user = $user;
        $this->Sessions->save($session);

        $token = new Token($session->uuid);

        Log::info('[LOGIN][facebook][201]['.$user->id.']'.$session->uuid.' : '.$user->facebook_id);

        return JsonBodyResponse::createdResponse($this->response, $token);


    }
}
