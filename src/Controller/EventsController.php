<?php

namespace App\Controller;

use App\Http\JsonBodyResponse;
use App\Model\Entity\Event;
use App\Model\Table\EventsUsersAcceptTable;
use App\Model\Table\EventsUsersDenyTable;
use App\Network\Exception\UnprocessedEntityException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use Cake\Network\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 * @property EventsUsersDenyTable EventsUsersDeny
 * @property EventsUsersAcceptTable EventsUsersAccept
 *
 * @method Event[] paginate($object = null, array $settings = [])
 */
class EventsController extends ApiV1Controller
{

    public function get($uuid)
    {
        try {
            $event = $this->Events->get($uuid, ['contain' => ['Images', 'Interests']]);
        } catch (RecordNotFoundException $e) {
            throw new UnprocessedEntityException($e->getMessage());
        }

        $userId = $this->Auth->user('user_id');

        $eventsUsersAccept = TableRegistry::get('EventsUsersAccept');

        $event->participating = $eventsUsersAccept->findByUserIdAndEventId($userId, $uuid)->isEmpty();

        $jsonEvent = new \Eswipe\Model\Event($event);

        return JsonBodyResponse::okResponse($this->response, $jsonEvent);
    }

    public function participate($uuid)
    {
        $userId = $this->Auth->user('user_id');

        try {
            $event = $this->Events->get($uuid, ['fields' => ['id']]);
        } catch (RecordNotFoundException $e) {
            throw new UnprocessedEntityException($e->getMessage());
        }

        $eventsUsersAcceptTable = TableRegistry::get('EventsUsersAccept');
        $eventsUsersDenyTable = TableRegistry::get('EventsUsersDeny');

        if ($eventsUsersAcceptTable->findByUserIdAndEventId($userId, $event->id)->first()) {
            throw new UnauthorizedException('Hey, you already accepted this one !');
        }

        $deniedEvent = $eventsUsersDenyTable->findByUserIdAndEventId($userId, $event->id)->first();
        if ($deniedEvent) {
            $eventsUsersDenyTable->delete($deniedEvent);
        }

        $acceptEvent = $eventsUsersAcceptTable->newEntity();
        $acceptEvent->user_id = $userId;
        $acceptEvent->event_id = $event->id;
        $eventsUsersAcceptTable->save($acceptEvent);

        Log::info('[EVENTS][accept] '.$userId.'-->'.$event->id);

        return $this->response->withStatus(204);
        // TODO : push notificaitons
    }

    public function decline($uuid)
    {
        $userId = $this->Auth->user('user_id');

        try {
            $event = $this->Events->get($uuid, ['fields' => ['id']]);
        } catch (RecordNotFoundException $e) {
            throw new UnprocessedEntityException($e->getMessage());
        }

        $eventsUsersAcceptTable = TableRegistry::get('EventsUsersAccept');
        $eventsUsersDenyTable = TableRegistry::get('EventsUsersDeny');

        if ($eventsUsersDenyTable->findByUserIdAndEventId($userId, $event->id)->first()) {
            throw new UnauthorizedException('Hey, you already accepted this one !');
        }

        $deniedEvent = $eventsUsersAcceptTable->findByUserIdAndEventId($userId, $event->id)->first();
        if ($deniedEvent) {
            $eventsUsersAcceptTable->delete($deniedEvent);
        }

        $acceptEvent = $eventsUsersDenyTable->newEntity();
        $acceptEvent->user_id = $userId;
        $acceptEvent->event_id = $event->id;
        $eventsUsersDenyTable->save($acceptEvent);
        Log::info('[EVENTS][decline] '.$userId.'-->'.$event->id);

        return $this->response->withStatus(204);
    }
}
