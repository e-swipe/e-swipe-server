<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Event Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Cake\I18n\FrozenTime $date_begin
 * @property \Cake\I18n\FrozenTime $date_end
 * @property float $latitude
 * @property float $longitude
 * @property int $is_visible
 *
 * @property \App\Model\Entity\EventsUsersAccept[] $events_users_accept
 * @property \App\Model\Entity\EventsUsersDeny[] $events_users_deny
 * @property \App\Model\Entity\Image[] $images
 * @property \App\Model\Entity\Interest[] $interests
 */
class Event extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
