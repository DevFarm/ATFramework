<?php

/**
 * Admin Controller Login of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_View as view;
use classes\base\ATCore_F as f;

class Controller_Login extends Controller_Base
{
    public static function before()
    {
    }

    public static function index()
    {
        if ($_POST) {
            $outsider = false;

            if (isset($_POST['outsider'])) {
                $outsider = true;
            }

            $auth = auth::login($_POST['email'], $_POST['password'], $outsider);

            if (f::is_done($auth)) {
                header('Location: /');
                exit;
            } else {
                $_POST['error'] = 1;
            }
        }

        view::$layout = false;
        view::load('login');
    }
}