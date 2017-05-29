<?php


namespace Eswipe\Model;


use App\Model\Entity\User;

class UserCard
{
    public $uuid;
    public $first_name;
    public $last_name;
    public $age;
    public $picture_url;
    public $position;

    public function __construct(User $userCard)
    {
        $this->uuid = $userCard->id;
        $this->first_name = $userCard->firstname;
        $this->last_name = $userCard->lastname;
        $this->age = $userCard->age;
        if (!empty($userCard->images)) {
            $this->picture_url = $userCard->images[0]->url;
        }
        $this->position = new Position($userCard->latitude, $userCard->longitude);
    }
}
