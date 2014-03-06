<?php

namespace classes\base;

use ATCore;
use classes\base\ATCore_Db as db;
use classes\base\ATCore_Request as request;
use classes\base\ATCore_String as string;

class ATCore_Log
{
    public static function add($text, $category = 'app')
    {
        if ($category != 'api') {
            db::query('
				INSERT INTO `at_log` SET
					`category` 	= "' . string::filter($category) . '",
					`text`		= "' . string::filter($text) . '"
			');
        } else {
            db::query('
				INSERT INTO `at_log_api` SET
					`app`			= ' . request::$app_id . ',
					`controller`	= "' . request::$controller . '",
					`action`		= "' . request::$action . '",
					`text`			= "' . string::filter($text) . '",
					`ip`			= "' . ATCore::$serv->http_x_real_ip . '"
			');
        }
    }
}