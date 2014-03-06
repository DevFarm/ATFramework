<?php

/**
 * API Controller Log of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */

class Controller_Log extends Controller_Base
{
    public static function _list($params)
    {
        return Model_Log::_list($params);
    }

    public static function count($params)
    {
        return Model_Log::count($params);
    }


    public static function api_list($params)
    {
        return Model_Log::api_list($params);
    }

    public static function api_count($params)
    {
        return Model_Log::api_count($params);
    }
}