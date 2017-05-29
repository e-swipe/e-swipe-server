<?php


namespace Eswipe\Model;


class UserCard
{
    public $uuid;
    public $first_name;
    public $last_name;
    public $date_of_birth;
    public $picture_url;
    public $position;

    public function __construct($userCard)
    {
        $this->uuid = $userCard->id;
        $this->first_name = $userCard->firstname;
        $this->last_name = $userCard->lastname;
        $this->date_of_birth = $userCard->date_of_birth_mdy;
        if (!empty($userCard->images)) {
            $this->picture_url = $userCard->images[0]->url;
        }
        $this->position = new Position($userCard->latitude, $userCard->longitude);
    }
}
