<?php


namespace Eswipe\Utils;


use Eswipe\Model\BoundingBox;
use Eswipe\Model\Position;

class Coordinates
{
    public static function toPosition($latitude, $longitude)
    {
        return new Position($latitude, $longitude);
    }

    /**
     *
     * @see https://stackoverflow.com/a/40272394/3047350
     * @param $latitude
     * @param $longitude
     * @param $kilometer
     * @return BoundingBox
     */
    public static function getBoundingBox($latitude, $longitude, $kilometer)
    {

        $radiusOfEarthKM = 6371;
        $latitudeRadians = deg2rad($latitude);
        $longitudeRadians = deg2rad($longitude);
        $distance = $kilometer / $radiusOfEarthKM;

        $deltaLongitude = asin(sin($distance) / cos($latitudeRadians));

        $bounds = new BoundingBox();

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

    /**
     * curl -X PATCH "https://api.stardis.blue/v1/me" -H  "accept: application/json" -H  "auth: c1af9e3a-92fc-4ce1-8cbd-421ecdbda71b" -H  "content-type: application/json" -d "{"description":"","gender":"female","is_visible":true,"looking_for_age_max":0,"looking_for_age_min":0}"
     */
}
