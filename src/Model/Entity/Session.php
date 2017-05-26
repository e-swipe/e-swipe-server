<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Eswipe\Utils\Uuid;

/**
 * Session Entity
 *
 * @property string|resource $uuid
 * @property int $user_id
 * @property int $string_uuid
 *
 * @property \App\Model\Entity\User $user
 *
 */
class Session extends Entity
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
        'uuid' => false
    ];


    protected function _getStringUuid()
    {
        return Uuid::toString($this->uuid);
    }

    protected function _setStringUuid($uuid)
    {
        $this->set('uuid', Uuid::toByte($uuid));
        return $uuid;
    }
}
