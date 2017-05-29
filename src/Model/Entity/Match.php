<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Match Entity
 *
 * @property int $matcher_id
 * @property int $matched_id
 * @property int $chat_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Chat $chat
 */
class Match extends Entity
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
        'matcher_id' => false,
        'matched_id' => false,
    ];
}
