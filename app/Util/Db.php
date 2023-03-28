<?php


namespace App\Util;


use Medoo\Medoo;

class Db
{
    /**
     * @var Medoo
     */
    protected static $db;

    /**
     * @param Medoo $db
     */
    public static function setDb(Medoo $db)
    {
        static::$db = $db;
    }

    /**
     * @return Medoo
     */
    public static function getDb()
    {
        return static::$db;
    }
}