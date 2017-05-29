<?php


namespace Eswipe\Model;


use Cake\Utility\Hash;

class UserInfo
{
    public $uuid;
    public $first_name;
    public $last_name;
    public $date_of_birth;
    public $description;
    public $picture_url;
    public $gender;
    public $looking_for;
    public $looking_for_age_min = 18;
    public $looking_for_age_max = 18;
    public $is_visible = true;
    public $position;
    public $pictures;
    public $events;


    public function __construct($user)
    {
        $this->uuid = $user->id;
        $this->first_name = $user->firstname;
        $this->last_name = $user->lastname;
        $this->date_of_birth = $user->date_of_birth_mdy;
        $this->description = $user->description;
        $this->gender = $user->gender->name;
        $this->looking_for = Hash::extract($user->looking_for, '{n}.name');
        $this->looking_for_age_min = $user->min_age;
        $this->looking_for_age_max = $user->max_age;
        $this->is_visible = boolval($user->is_visible);
        $this->position = new Position($user->latitude, $user->longitude);
        $this->pictures = [];

        foreach ($user->images as $picture) {
            $this->pictures[] = new Image($picture);
        }

        if (!empty($this->pictures)) {
            $this->picture_url = $this->pictures[0]->url;
        }

        $this->events = [];
        foreach ($user->events as $event) {
            $this->events[] = new EventCard($event);
        }
    }
}
