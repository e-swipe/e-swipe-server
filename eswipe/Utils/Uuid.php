<?php

namespace Eswipe\Utils;

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
        return call_user_func_array('pack',
            array_merge(array('VvvCCC6'),
                array_map('hexdec',
                    array(substr($uuid, 0, 8),
                        substr($uuid, 9, 4), substr($uuid, 14, 4),
                        substr($uuid, 19, 2), substr($uuid, 21, 2))),
                array_map('hexdec',
                    str_split(substr($uuid, 24, 12), 2))));

    }

    public static function toString($byteUuid)
    {
        $uuid = unpack("H*", $byteUuid);
        return preg_replace("/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/", "$1-$2-$3-$4-$5", $uuid)['1'];
    }
}
