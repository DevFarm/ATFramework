<?php

use classes\base\ATCore_Log as log;

class ATCore
{
    public static $root = '';

    /**
     * @var Object Contains $_SERVER
     */
    public static $serv;

    public static $init = false;

    public static $app = '';


    public static function init()
    {
        if (static::$init) {
            return true;
        }

        /*register_shutdown_function(function ()
        {
            return ATCore::shutdown_handler();
        });*/

        /*set_exception_handler(function (Exception $e)
        {
            echo 'exception '.$e; exit;
        });*/

        set_error_handler(function ($severity, $message, $filepath, $line) {
            echo 'error: ' . $severity . ' ' . $message . ' ' . $filepath . ' ' . $line;
            log::add('error: ' . $severity . ' ' . $message . ' ' . $filepath . ' ' . $line, ATCore::$app);
        });

        static::$serv = (object)array_change_key_case($_SERVER, CASE_LOWER);

        if (empty(static::$serv->http_x_real_ip)) {
            static::$serv->http_x_real_ip = static::$serv->remote_addr;
        }

        static::$root = dirname(__FILE__) . '/at_core';
        return request::init();
    }

    public static function shutdown_handler()
    {
        if ($last_error = error_get_last()) {
            //error::error($last_error);
        }
    }
}