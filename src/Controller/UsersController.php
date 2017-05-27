<?php

namespace App\Controller;

use App\Http\JsonBodyResponse;
use App\Model\Entity\User;
use App\Model\Table\GendersTable;
use App\Model\Table\SessionsTable;
use App\Model\Table\UsersTable;
use App\Network\Exception\UnprocessedEntityException;
use Cake\Http\Response;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Text;
use Cake\Validation\Validation;
use Eswipe\Model\Token;
use Eswipe\Utils\Uuid;

/**
 * Users Controller
 *
 * @property UsersTable $Users
 * @property SessionsTable Sessions
 * @property GendersTable Genders
 *
 * @method User[] paginate($object = null, array $settings = [])
 */
class UsersController extends ApiV1Controller
{

    public function logout()
    {
        $auth = $this->request->getHeaderLine('auth');
        $this->loadModel('Sessions');

        if (!Uuid::isValid($auth)) {
            throw new UnauthorizedException();
        }

        $session = $this->Sessions->get($auth);

        if (is_null($session)) {
            throw new UnauthorizedException();
        }

        $this->Sessions->delete($session);

        $response = $this->response->withStatus(204);
        return $response;
    }

    /**
     * @return Response
     */
    public function add()
    {
        $instanceId = $this->request->getQuery('instance_id');
        $userData = $this->request->getData();


        if (empty($userData)) {
            throw new UnprocessedEntityException('incorrect data');
        } else if (!is_string($instanceId) || strlen($instanceId) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "instance_id"');
        } else if (!array_key_exists('first_name', $userData)
            || !is_string($userData['first_name'])
            || strlen($userData['first_name']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "firstname"');
        } else if (!array_key_exists('last_name', $userData)
            || !is_string($userData['last_name'])
            || strlen($userData['last_name']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "firstname"');
        } else if (!array_key_exists('gender', $userData)
            || !is_string($userData['gender'])
            || strlen($userData['gender']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "firstname"');
        } else if (!array_key_exists('date_of_birth', $userData)
            || !is_string($userData['date_of_birth'])
        ) {
            $date = FrozenDate::parseDate($userData['date_of_birth']);

            if (!$date) {
                throw new UnprocessedEntityException('unexpected "date"');
            }

            if ($date->diff(Date::now())->y < 18) {
                throw new UnprocessedEntityException('unexpected "age"');
            }

        } else if (!array_key_exists('email', $userData)
            || !is_string($userData['email'])
            || !Validation::email($userData['email'])
        ) {
            throw new UnprocessedEntityException('unexpected "email"');
        } else if (!array_key_exists('password', $userData)
            || !is_string($userData['password'])
            || strlen($userData['password']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "email"');
        }

        $this->loadModel('Genders');
        $this->loadModel('Sessions');

        if ($this->Users->findByEmail($userData['email'])->first()) {
            throw new ConflictException('user already exists');
        }

        $user = $this->Users->newEntity();
        $user->firstname = $userData['first_name'];
        $user->lastname = $userData['last_name'];
        $user->email = $userData['email'];
        $user->password = $userData['password'];
        $user->instance_id = $instanceId;
        $user->description = "";
        $user->date_of_birth = FrozenDate::parseDate($userData['date_of_birth']);
        $user->gender = $this->Genders->find('all', ['name' => $userData['gender']])->first();


        $session = $this->Sessions->newEntity();
        $session->uuid = Text::uuid();
        $session->user = $this->Users->save($user);

        $this->Sessions->save($session);

        $token = new Token();
        $token->auth = $session->uuid;

        return JsonBodyResponse::createdResponse($this->response, $token);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Genders']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Genders', 'Images', 'Interests', 'ChatsUsersMessages', 'EventsUsersAccept', 'EventsUsersDeny', 'UsersGendersLookingFor']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }


    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Images', 'Interests']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $genders = $this->Users->Genders->find('list', ['limit' => 200]);
        $images = $this->Users->Images->find('list', ['limit' => 200]);
        $interests = $this->Users->Interests->find('list', ['limit' => 200]);
        $this->set(compact('user', 'genders', 'images', 'interests'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
