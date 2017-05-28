<?php

namespace App\Controller;

use App\Http\JsonBodyResponse;
use App\Model\Entity\User;
use App\Model\Table\GendersTable;
use App\Model\Table\SessionsTable;
use App\Model\Table\UsersTable;
use App\Network\Exception\UnprocessedEntityException;
use App\Validator\DataValidator;
use Cake\Database\Expression\QueryExpression;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Cake\Validation\Validation;
use Eswipe\Model\Token;
use Eswipe\Model\UserCard;
use Eswipe\Utils\Coordinates;
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
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add']);
    }

    public function logout()
    {
        $this->Auth->user();
        $auth = $this->request->getHeaderLine('auth');
        $this->loadModel('Sessions');

        if (is_null($auth) || !Uuid::isValid($auth)) {
            throw new UnauthorizedException();
        }

        $session = $this->Sessions->findByUuid($auth)->first();

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
            throw new UnprocessedEntityException('unexpected "last_name"');
        } else if (!array_key_exists('last_name', $userData)
            || !is_string($userData['last_name'])
            || strlen($userData['last_name']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "gender"');
        } else if (!array_key_exists('gender', $userData)
            || !is_string($userData['gender'])
            || strlen($userData['gender']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "date_of_birth"');
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
            throw new UnprocessedEntityException('unexpected "password"');
        }

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

        $user->max_age = $user->date_of_birth->age + 10;
        $user->min_age = $user->date_of_birth->age > 28 ? $user->date_of_birth->age - 10 : 18;

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
        $message = DataValidator::validateProfiles($this->request);
        if (!is_null($message)) {
            throw new UnprocessedEntityException($message);
        }

        $latitude = $this->request->getQuery('latitude');
        $longitude = $this->request->getQuery('longitude');
        $radius = $this->request->getQuery('radius', 50);


        $user_id = $this->Auth->user('id');

        $user = $this->Users->get(10, ['contain' => ['LookingFor']]);
        // maj de la position de l'utilisateur :)
        $user->latitude = $latitude;
        $user->longitude = $longitude;

        $this->Users->save($user);

        $user->looking_for = Hash::extract($user->looking_for, '{n}.id');
        $bounding = Coordinates::getBoundingBox($latitude, $longitude, $radius);

        $query = new QueryExpression();
        $query->between('Users.latitude', $bounding->minLat, $bounding->maxLat)
            ->between('Users.longitude', $bounding->minLat, $bounding->maxLong)
            ->notEq('Users.id', $user->id);

        if ($user->looking_for) {
            $query->in('Users.gender_id', $user->looking_for);
        }

        $users = $this->Users->find('all', [
            'fields' => ['id', 'firstname', 'lastname', 'date_of_birth', 'latitude', 'longitude'],
            'contain' => ['Images']
        ])
            ->where($query)
            ->matching('UsersGendersLookingFor', function ($q) use ($user) {
                return $q->where(['UsersGendersLookingFor.gender_id' => $user->gender_id]);
            })
            ->notMatching('Matched', function ($q) use ($user) {
                return $q->where(['Matched.matcher_id' => $user->id]);
            })
            ->notMatching('Accepted', function ($q) use ($user) {
                return $q->where(['Accepted.accepter_id' => $user->id]);
            })
            ->notMatching('Declined', function ($q) use ($user) {
                return $q->where(['Declined.decliner_id' => $user->id]);
            })
            ->limit(10)->all();
        $usersCard = [];
        foreach ($users as $userCard) {
            $userCard->date_of_birth = $userCard->date_of_birth->format('m/d/Y');
            $usersCard[] = new UserCard($userCard->toArray());
        }

        return JsonBodyResponse::okResponse($this->response, $usersCard);
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
