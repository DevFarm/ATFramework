<?php

/**
 * Admin Controller Stat of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_View as view;

class Controller_Stat extends Controller_Base
{
    public static function index()
    {
        view::load('main/index');
    }
}