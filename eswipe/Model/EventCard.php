<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 23:00
 */

namespace Eswipe\Model;


use Cake\Utility\Hash;

class EventCard
{
    public $uuid;
    public $name;
    public $picture_url;
    public $position;

    /**
     * EventCard constructor.
     * @param string $uuid
     * @param string $name
     * @param string $picture_url
     * @param Position $position
     */
    public function __construct($eventCard)
    {
        $this->uuid = $eventCard['id'];
        $this->name = $eventCard['name'];
        $pictures = Hash::sort($eventCard['images'], '{n}._joinData.order', 'asc');
        if (!empty($pictures))
            $this->picture_url = $pictures[0]['url'];
        $this->position = new Position($eventCard['latitude'], $eventCard['longitude']);
    }
}
