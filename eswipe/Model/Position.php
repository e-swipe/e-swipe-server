<?php


namespace Eswipe\Model;


class Position
{
    public $latitude;
    public $longitude;

    /**
     * Position constructor.
     * @param $latitude
     * @param $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
