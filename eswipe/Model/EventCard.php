<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 23:00
 */

namespace Eswipe\Model;


class EventCard
{
    public $uuid;
    public $name;
    public $picture_url;
    public $position;

    /**
     * EventCard constructor.
     * @param $eventCard
     */
    public function __construct($eventCard)
    {
        $this->uuid = $eventCard->id;
        $this->name = $eventCard->name;

        if (!empty($eventCard->images)) {
            $this->picture_url = $eventCard->images[0]->url;
        }
        $this->position = new Position($eventCard->latitude, $eventCard->longitude);
    }
}
