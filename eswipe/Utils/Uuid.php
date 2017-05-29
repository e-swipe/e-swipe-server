<?php

namespace Eswipe\Utils;

use Cake\Utility\Text;

/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 26/05/2017
 * Time: 23:49
 */
class Uuid
{
    public static function toByte($uuid)
    {
        $uuid = Text::slug($uuid);

        return pack("h*", str_replace('-', '', $uuid));

    }

    public static function toString($byteUuid)
    {
        $uuid = unpack('h*', $byteUuid);

        return preg_replace(
            "/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/",
            "$1-$2-$3-$4-$5",
            $uuid
        )[1];
    }

    public static function isValid($uuid)
    {
        return preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i", $uuid);
    }
}
