<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChatsUsersMessage Entity
 *
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string $content
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\Chat $chat
 * @property \App\Model\Entity\User $user
 */
class ChatsUsersMessage extends Entity
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
        'chat_id' => false,
        'user_id' => false,
    ];
}
