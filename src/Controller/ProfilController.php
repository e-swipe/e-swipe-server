<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 22:11
 */

namespace App\Controller;


use App\Http\JsonBodyResponse;
use App\Model\Table\UsersTable;
use App\Network\Exception\UnprocessedEntityException;
use App\Validator\DataValidator;
use Cake\I18n\FrozenDate;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Eswipe\Model\ChatCard;
use Eswipe\Model\UserInfo;

/**
 * @property UsersTable Users
 */
class ProfilController extends ApiV1Controller
{

    /**
     * @property bool|object Users
     */
    public function profil()
    {
        $this->loadModel('Users');
        $user_id = $this->Auth->user('user_id');

        // TODO : optimize
        // https://api.cakephp.org/3.4/class-Cake.ORM.Query.html#_contain
        $user = $this->Users->get(
            $user_id,
            [
                'contain' => ['LookingFor', 'Genders', 'Images', 'AcceptedEvents' => ['Images']],
            ]
        );

        $userInfo = new UserInfo($user);

        return JsonBodyResponse::okResponse($this->response, $userInfo);
    }

    public function patch()
    {
        $this->loadModel('Users');
        $user_id = $this->Auth->user('user_id');

        $message = DataValidator::validateMePatch($this->request);
        if (!is_null($message)) {
            throw new UnprocessedEntityException($message);
        }
        $userPatch = array_filter(
            $this->request->getData(),
            function ($key) {
                return $key != null;
            }
        );

        $user = $this->Users->get($user_id, ['contain' => ['LookingFor']]);

        //TODO : faire ca en plus joli :)
        if (isset($userPatch['looking_for'])) {
            $this->Users->LookingFor->unlink($user, $user->looking_for); // suppresion des anciennes liaisons

            $genders = $this->Users->LookingFor
                ->find('all')->where(['name IN' => $userPatch['looking_for']])->toArray();

            $this->Users->LookingFor->link($user, $genders);// reassociation de la liaison :)
        }

        if (isset($userPatch['first_name'])) {
            $user->firstname = $userPatch['first_name'];
        }

        if (isset($userPatch['last_name'])) {
            $user->lastname = $userPatch['last_name'];
        }

        if (isset($userPatch['date_of_birth'])) {
            $user->date_of_birth = FrozenDate::parseDate($userPatch['date_of_birth']);
        }

        if (isset($userPatch['description'])) {
            $user->description = $userPatch['description'];
        }

        if (isset($userPatch['gender'])) {
            $user->gender = $this->Users->Genders->findByName($userPatch['gender'])->first();;
        }

        if (isset($userPatch['looking_for_age_min'])) {
            $user->min_age = $userPatch['looking_for_age_min'];
        }

        if (isset($userPatch['looking_for_age_max'])) {
            $user->max_age = $userPatch['looking_for_age_max'];
        }

        if (isset($userPatch['is_visible'])) {
            $user->is_visible = $userPatch['is_visible'];
        }

        $this->Users->save($user);

        return $this->response->withStatus(204);

    }

    public function changePassword()
    {

    }

    public function addPhoto()
    {
        //TODO :hexadecimales
    }

    public function deletePhoto($uuid)
    {

        $userId = $this->Auth->user('user_id');


        //TODO : delete photo
    }

    public function updatePhotosOrder()
    {
        //TODO: updatePhotosOrder
    }

    public function getChats()
    {
        $userId = $this->Auth->user('user_id');

        $chatsTable = TableRegistry::get('Chats');
        $message = DataValidator::validateGetChats($this->request);
        if (!is_null($message)) {
            throw new UnprocessedEntityException($message); //422
        }

        $offset = $this->request->getQuery('offset', 0);
        $limit = $this->request->getQuery('limit', 10);



        $chats = $chatsTable->find()
            ->contain('ChatsUsersMessages')
            ->contain([
                'MatchedUsers' => [
                    'queryBuilder' => function ($q) use ($userId) {
                        return $q->where(['matcher_id' => $userId]);
                    },
                    'Images',
                ],
            ])
            ->matching('Matches', function ($q) use ($userId) {
                /** @var Query $q */
                return $q->where(['matcher_id' => $userId]);
            })
            ->limit($limit)->offset($offset);

        $chatCards = [];
        foreach ($chats as $chat) {
            $chatCards[] = new ChatCard($chat);
        }

        return JsonBodyResponse::okResponse($this->response, $chatCards);

    }
}
