<?php

namespace App\Controller;

use App\Http\JsonBodyResponse;
use App\Model\Entity\Event;
use App\Network\Exception\UnprocessedEntityException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
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
}
