<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 28/05/2017
 * Time: 18:13
 */

namespace Eswipe\Utils;


use Eswipe\Model\Position;

class Coordinates
{
    public static function toPosition($latitude, $longitude)
    {
        return new Position($latitude, $longitude);
    }

    public static function getBoundingBox($latitude, $longitude, $kilometer)
    {

        $radiusOfEarthKM = 6371;
        $latitudeRadians = deg2rad($latitude);
        $longitudeRadians = deg2rad($longitude);
        $distance = $kilometer / $radiusOfEarthKM;

        $deltaLongitude = asin(sin($distance) / cos($latitudeRadians));

        $bounds = new \stdClass();

        // these are the outer bounds of the circle (S2)
        $bounds->minLat = rad2deg($latitudeRadians - $distance);
        $bounds->maxLat = rad2deg($latitudeRadians + $distance);
        $bounds->minLong = rad2deg($longitudeRadians - $deltaLongitude);
        $bounds->maxLong = rad2deg($longitudeRadians + $deltaLongitude);

        // and these are the inner bounds (S1)
        // $bounds->innerMinLat = rad2deg($latitudeRadians + $distance * cos(5 * M_PI_4));
        // $bounds->innerMaxLat = rad2deg($latitudeRadians + $distance * sin(M_PI_4));
        // $bounds->innerMinLong = rad2deg($longitudeRadians + $deltaLongitude * sin(5 * M_PI_4));
        // $bounds->innerMaxLong = rad2deg($longitudeRadians + $deltaLongitude * cos(M_PI_4));

        return $bounds;

    }
}
