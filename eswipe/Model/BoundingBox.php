<?php


namespace Eswipe\Model;


class BoundingBox
{
    public $minLat;
    public $maxLat;
    public $minLong;
    public $maxLong;

    /**
     * BoundingBox constructor.
     * @param $minLat
     * @param $maxLat
     * @param $minLong
     * @param $maxLong
     */
    public function __construct($minLat = null, $maxLat = null, $minLong = null, $maxLong = null)
    {
        $this->minLat = $minLat;
        $this->maxLat = $maxLat;
        $this->minLong = $minLong;
        $this->maxLong = $maxLong;
    }

}