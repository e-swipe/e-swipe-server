<?php

namespace App\Controller;

use App\Http\JsonBodyResponse;
use App\Model\Table\ChatsTable;
use App\Network\Exception\UnprocessedEntityException;
use App\Validator\DataValidator;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Network\Exception\UnauthorizedException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Eswipe\Model\Chat;
use Eswipe\Model\Message;
use Eswipe\Utils\PushNotifications;

/**
 * @property ChatsTable Chats
 */
class ChatsController extends ApiV1Controller
{

    public function get($uuid)
    {
        $message = DataValidator::validateChatGet($this->request);
        if (!is_null($message)) {
            throw new UnprocessedEntityException($message);
        }

        $offset = $this->request->getQuery('offset', 0);
        $limit = $this->request->getQuery('limit', 10);
        $since = $this->request->getQuery('since', 0);

        $userId = $this->Auth->user('user_id');

        $chat = $this->Chats->find('all')->contain([
            'ChatsUsersMessages' => [
                'Users',
                'queryBuilder' => function ($q) use ($offset, $limit, $since) {
                    /**@var Query $q */
                    return $q->where(['created_at >=' => $since])->offset($offset)->limit($limit);
                },
            ],
            'MatchedUsers' => [
                'queryBuilder' => function ($q) use ($userId) {
                    /** @var Query $q */
                    return $q->where(['matcher_id' => $userId]);
                },
                'Images',
            ],
        ])->matching('Matches', function ($q) use ($userId) {
            /** @var Query $q */
            return $q->where(['matcher_id' => $userId]);
        })->where(['Chats.id' => $uuid])->first();

        if (!$chat) {
            throw new UnauthorizedException();
        }

        $jsonChat = new Chat($chat);

        Log::Debug('[CHAT][get]['.$uuid.'] limit='.$limit.' | offset='.$offset.' | since='.$since);

        return JsonBodyResponse::okResponse($this->response, $jsonChat);
    }

    public function addMessage($uuid)
    {
        $message = DataValidator::validateChatAddMessage($this->request);
        if (!is_null($message)) {
            throw new UnprocessedEntityException($message);
        }

        $userId = $this->Auth->user('user_id');

        $messageData = $this->request->getData();

        $matchesTable = TableRegistry::get('Matches');

        if (!$matchesTable->findByMatcherIdAndChatId($userId, $uuid)->first()) {
            throw new UnauthorizedException('Hey, where are you trying to go !');
        }

        $message = $this->Chats->ChatsUsersMessages->newEntity();
        $message->chat_id = $uuid;
        $message->user_id = $userId;
        $message->content = $messageData['content'];
        $message->created_at = new Time($messageData['date']);

        $this->Chats->ChatsUsersMessages->save($message);

        $jsonMessage = new Message($message);


        /*
         * Push Notification :)
         */
        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->get($userId, ['fields' => ['firstname']]);

        $reciever = $usersTable->find(['fields' => ['id', 'instance_id']])->matching('Matched',
            function ($q) use ($userId, $uuid) {
                /** @var Query $q */
                return $q->where(["matcher_id" => $userId, 'chat_id' => $uuid]);
            })->first();

        PushNotifications::pushNewMessage($user, $reciever, $uuid, $message);

        return JsonBodyResponse::createdResponse($this->response, $jsonMessage);
    }
}
