<?php

namespace classes\base;

class ATCore_Db
{
    public static $_link;

    public static $_host = '';

    public static $_login = '';

    public static $_password = '';

    public static $_db = '';

    public static function connect()
    {
        static::$_link = mysqli_connect(static::$_host, static::$_login, static::$_password, static::$_db);
        mysqli_query(static::$_link, 'SET NAMES "utf8"');

        if (!static::$_link) {
            die('error');
        }
    }

    public static function query($sql)
    {
        return mysqli_query(static::$_link, $sql);
    }

    public static function fetch($sql)
    {
        return mysqli_fetch_assoc($sql);
    }

    public static function fetch_all($sql, $field = null, $flat = false)
    {
        if ($field) {
            $result = array();
            while ($a = static::fetch($sql)) {
                if (!isset($a[$field])) {
                    return false;
                }

                $b = $a;
                unset($b[$field]);

                if ($flat && count($b) == 1) {
                    $b = array_values($b);
                    $result[$a[$field]] = array_shift($b);
                } else {
                    $result[$a[$field]] = $a;
                    unset($result[$a[$field]][$field]);
                }
            }

            return $result;
        }

        return mysqli_fetch_all($sql, MYSQL_ASSOC);
    }

    public static function num($sql)
    {
        return mysqli_num_rows($sql);
    }

    public static function insert_id()
    {
        return mysqli_insert_id(static::$_link);
    }
}