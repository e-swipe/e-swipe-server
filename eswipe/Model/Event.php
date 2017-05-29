<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 29/05/2017
 * Time: 18:37
 */

namespace Eswipe\Model;


class Event
{
    public $uuid;
    public $name;
    public $description;
    public $images;
    public $position;
    public $participants;
    public $interests;
    public $date_begin;
    public $date_end;
    public $participating;

    /**
     * EventCard constructor.
     * @param $event
     */
    public function __construct(\App\Model\Entity\Event $event)
    {
        $this->uuid = $event->id;
        $this->name = $event->name;
        $this->description = $event->description;

        $this->images = [];

        foreach ($event->images as $picture) {
            $this->images[] = new Image($picture);
        }

        $this->position = new Position($event->latitude, $event->longitude);
        $this->participants = $event->user_count;
        $this->interests = [];

        foreach ($event->interests as $interest) {
            $this->interests[] = new Interest($interest);
        }

        $this->date_begin = $event->date_begin;
        $this->date_end = $event->date_begin;
        $this->participating = $event->participating;

    }
}
