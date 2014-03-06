<?php

/**
 * Admin Controller Logout of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */

class Controller_Logout extends Controller_Base
{
    public static function before()
    {
    }

    public static function index()
    {
        auth::logout();
        header('Location: /login');
        exit;
    }
}