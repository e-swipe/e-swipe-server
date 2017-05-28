<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Decline Entity
 *
 * @property int $decliner_id
 * @property int $declined_id
 *
 * @property \App\Model\Entity\User $user
 */
class Decline extends Entity
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
        'decliner_id' => false,
        'declined_id' => false
    ];
}
