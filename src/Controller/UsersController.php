<?php

namespace App\Controller;

use App\Http\JsonBodyResponse;
use App\Model\Entity\User;
use App\Model\Table\AcceptsTable;
use App\Model\Table\ChatsTable;
use App\Model\Table\DeclinesTable;
use App\Model\Table\GendersTable;
use App\Model\Table\MatchesTable;
use App\Model\Table\UsersTable;
use App\Network\Exception\UnprocessedEntityException;
use App\Validator\DataValidator;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\Log\Log;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Cake\Validation\Validation;
use Eswipe\Model\Token;
use Eswipe\Model\UserCard;
use Eswipe\Utils\Coordinates;
use Eswipe\Utils\PushNotifications;

/**
 * Users Controller
 *
 * @property UsersTable $Users
 * @property GendersTable Genders
 * @property ChatsTable Chats
 * @property AcceptsTable Accepts
 * @property MatchesTable Matches
 * @property DeclinesTable Declines
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
        $sessionTable = TableRegistry::get('Sessions');
        $userId = $this->Auth->user('user_id');

        $session = $sessionTable->findByUserId($userId)->first();
        $sessionTable->delete($session);

        $this->Auth->logout();

        Log::info('[USERS: logout: 204: '.$userId.']'.$session->uuid);

        return $this->response->withStatus(204);
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
        } elseif (!is_string($instanceId) || strlen($instanceId) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "instance_id"');
        } elseif (!array_key_exists('first_name', $userData)
            || !is_string($userData['first_name'])
            || strlen($userData['first_name']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "last_name"');
        } elseif (!array_key_exists('last_name', $userData)
            || !is_string($userData['last_name'])
            || strlen($userData['last_name']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "gender"');
        } elseif (!array_key_exists('gender', $userData)
            || !is_string($userData['gender'])
            || strlen($userData['gender']) > 250
        ) {
            throw new UnprocessedEntityException('unexpected "date_of_birth"');
        } elseif (!array_key_exists('date_of_birth', $userData)
            || !is_string($userData['date_of_birth'])
        ) {
            $date = FrozenDate::parseDate($userData['date_of_birth']);

            if (!$date) {
                throw new UnprocessedEntityException('unexpected "date"');
            } elseif ($date->diff(Date::now())->y < 18) {
                throw new UnprocessedEntityException('unexpected "age"');
            }

        } elseif (!array_key_exists('email', $userData)
            || !is_string($userData['email'])
            || !Validation::email($userData['email'])
        ) {
            throw new UnprocessedEntityException('unexpected "email"');
        } elseif (!array_key_exists('password', $userData)
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

        $gendersTable = TableRegistry::get('Genders');
        $user->gender = $gendersTable->find('all', ['name' => $userData['gender']])->first();

        $sessionsTable = TableRegistry::get('Sessions');
        $session = $sessionsTable->newEntity();
        $session->uuid = Text::uuid();
        $session->user = $this->Users->save($user);

        $sessionsTable->save($session);

        $token = new Token();
        $token->auth = $session->uuid;

        Log::info('[LOGIN: create: 201: '.$user->id.']: '.$session->uuid);

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

        $userId = $this->Auth->user('user_id');

        try {
            $user = $this->Users->get($userId);
        } catch (RecordNotFoundException $e) {
            throw new UnprocessedEntityException($e->getMessage());
        }
        // maj de la position de l'utilisateur :)
        $user->latitude = $latitude;
        $user->longitude = $longitude;
        $this->Users->save($user);


        $usersLookingFor = $this->Users->LookingFor->find('all', ['fields' => ['id']])
            ->matching('UsersGendersLookingFor',
                function ($q) use ($userId) {
                    /** @var Query $q */
                    return $q->where(['user_id' => $userId]);
                })->toArray();

        $usersLookingFor = Hash::extract($usersLookingFor, '{n}.id');

        $bounding = Coordinates::getBoundingBox($latitude, $longitude, $radius);

        $query = new QueryExpression();
        $query->between('Users.latitude', $bounding->minLat, $bounding->maxLat)
            ->between('Users.longitude', $bounding->minLong, $bounding->maxLong)
            ->notEq('Users.id', $user->id);

        if ($usersLookingFor) {
            $query->in('Users.gender_id', $usersLookingFor);
        }

        $userGenderId = $user->gender_id;

        // TODO : Change matching options
        $users = $this->Users->find('all', [
            'fields' => ['id', 'firstname', 'lastname', 'date_of_birth', 'latitude', 'longitude'],
            'contain' => ['Images'],
        ])
            ->where($query)
            ->matching('UsersGendersLookingFor', function ($q) use ($userGenderId) {
                /** @var Query $q */
                return $q->where(['UsersGendersLookingFor.gender_id' => $userGenderId]);
            })
            ->notMatching('Matched', function ($q) use ($userId) {
                /** @var Query $q */
                return $q->where(['Matched.matcher_id' => $userId]);
            })
            ->notMatching('Accepted', function ($q) use ($userId) {
                /** @var Query $q */
                return $q->where(['Accepted.accepter_id' => $userId]);
            })
            ->notMatching('Declined', function ($q) use ($userId) {
                /** @var Query $q */
                return $q->where(['Declined.decliner_id' => $userId]);
            })
            ->limit(10);

        $usersCard = [];
        foreach ($users as $userCard) {
            $usersCard[] = new UserCard($userCard);
        }

        Log::info('[USERS: profils: 200: '.$userId.'] rad='.$radius.' | found='.sizeof($usersCard));

        return JsonBodyResponse::okResponse($this->response, $usersCard);
    }

    /**
     * View method
     * @param $uuid
     * @return Response|null
     */
    public function accept($uuid)
    {
        $this->loadModel('Accepts');
        $this->loadModel('Declines');
        $this->loadModel('Chats');
        $this->loadModel('Matches');
        $meId = $this->Auth->user('user_id');

        try {
            $user = $this->Users->get($uuid, ['fields' => ['id', 'instance_id', 'firstname']]);
        } catch (RecordNotFoundException $e) {
            throw new UnprocessedEntityException($e->getMessage());
        }

        if ($this->Accepts->findByAccepterIdAndAcceptedId($meId, $user->id)->first()) {
            throw new UnauthorizedException('Hey, you already accepted this one !');
        }

        if ($this->Declines->findByDeclinerIdAndDeclinedId($meId, $user->id)->first()) {
            throw new UnauthorizedException('Hey, you already declined this one !');
        }

        $acceptedEntity = $this->Accepts->newEntity();
        $acceptedEntity->accepter_id = $meId;
        $acceptedEntity->accepted_id = $user->id;

        $this->Accepts->save($acceptedEntity);

        Log::info('[USERS: accept: 200: '.$meId.'] '.$user->id);

        if ($this->Accepts->findByAccepterIdAndAcceptedId($user->id, $meId)->first()) {
            // It's a match :)
            $chat = $this->Chats->newEntity();
            $chat->setDirty('id', true);
            $this->Chats->save($chat);

            $matchMeToUser = $this->Matches->newEntity();
            $matchMeToUser->matcher_id = $meId;
            $matchMeToUser->matched_id = $user->id;
            $matchMeToUser->chat = $chat;

            $matchUserToMe = $this->Matches->newEntity();
            $matchUserToMe->matcher_id = $user->id;
            $matchUserToMe->matched_id = $meId;
            $matchUserToMe->chat = $chat;

            $this->Matches->save($matchUserToMe);
            $this->Matches->save($matchMeToUser);

            Log::info('[USERS: match: 200: '.$meId.'] '.$chat->id.' => ['.$meId.'<->'.$user->id.']');

            /**
             * push notification :)
             */
            $matcher = $this->Users->get($meId, ['fields' => ['instance_id', 'firstname']]);

            PushNotifications::pushNewMatch($matcher, $user, $chat->id);
            PushNotifications::pushNewMatch($user, $matcher, $chat->id);
        }

        return $this->response->withStatus(204);
    }


    public function decline($uuid)
    {
        $this->loadModel('Declines');
        $this->loadModel('Accepts');

        $meId = $this->Auth->user('user_id');

        try {
            $user = $this->Users->get($uuid, ['fields' => ['id']]);
        } catch (RecordNotFoundException $e) {
            throw new UnprocessedEntityException($e->getMessage());
        }

        if ($this->Accepts->findByAccepterIdAndAcceptedId($meId, $user->id)->first()) {
            throw new UnauthorizedException('Hey, you already accepted this one !');
        }

        if ($this->Declines->findByDeclinerIdAndDeclinedId($meId, $user->id)->first()) {
            throw new UnauthorizedException('Hey, you already declined this one !');
        }

        $declinedEntity = $this->Declines->newEntity();
        $declinedEntity->decliner_id = $meId;
        $declinedEntity->declined_id = $user->id;
        $this->Declines->save($declinedEntity);

        Log::info('[USERS: decline: 204: '.$meId.'] '.$user->id);

        return $this->response->withStatus(204);

    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Images', 'Interests'],
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
    }*/

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }*/
}
