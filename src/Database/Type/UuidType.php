<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 08:48
 */

namespace App\Database\Type;


use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\Database\TypeInterface;
use Eswipe\Utils\Uuid;
use PDO;

class UuidType extends Type implements TypeInterface
{
    public function toPHP($value, Driver $d)
    {
        return Uuid::toString($value);
    }

    public function toDatabase($value, Driver $d)
    {
        return Uuid::toByte($value);
    }

    /**
     * Get the correct PDO binding type for uuid data.
     *
     * @param mixed $value The value being bound.
     * @param \Cake\Database\Driver $driver The driver.
     * @return int
     */
    public function toStatement($value, Driver $driver)
    {
        return PDO::PARAM_LOB;
    }
}
