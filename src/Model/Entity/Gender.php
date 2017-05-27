<?php

namespace App\Model\Entity;

use App\Model\Entity\UsersGendersLookingFor;
use Cake\ORM\Entity;

/**
 * Gender Entity
 *
 * @property int $id
 * @property string $name
 *
 * @property User[] $users
 * @property UsersGendersLookingFor[] $users_genders_looking_for
 */
class Gender extends Entity
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
}
