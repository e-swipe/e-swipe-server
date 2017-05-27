<?php

namespace App\Model\Entity;

use App\Model\Entity\EventsUsersAccept;
use App\Model\Entity\EventsUsersDeny;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property int $facebook_id
 * @property string $email
 * @property string $password
 * @property string $instance_id
 * @property string $firstname
 * @property string $lastname
 * @property string $description
 * @property \Cake\I18n\FrozenDate $date_of_birth
 * @property float $latitude
 * @property float $longitude
 * @property int $is_visible
 * @property int $gender_id
 * @property int $min_age
 * @property int $max_age
 *
 * @property Gender $gender
 * @property ChatsUsersMessage[] $chats_users_messages
 * @property EventsUsersAccept[] $events_users_accept
 * @property EventsUsersDeny[] $events_users_deny
 * @property UsersGendersLookingFor[] $users_genders_looking_for
 * @property Image[] $images
 * @property Interest[] $interests
 */
class User extends Entity
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
        'id' => false
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }
}
