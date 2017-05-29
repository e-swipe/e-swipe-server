<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Accept Entity
 *
 * @property int $accepter_id
 * @property int $accepted_id
 *
 * @property \App\Model\Entity\User $accepter
 * @property \App\Model\Entity\User $accepted
 */
class Accept extends Entity
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
        'accepter_id' => false,
        'accepted_id' => false,
    ];
}
