<?php

/**
 * Admin Controller Base of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_Api as api;
use classes\base\ATCore_View as view;

class Controller_Base
{
    public static function before()
    {
        if (!auth::$done && request::$controller != 'login') {
            header('Location: /login');
        }

        $list_access = api::query('user_role_rule/access', array(
            'role' => auth::$user['role'],
            'app' => api::$app_id,
            'controller' => request::$controller,
            'action' => request::$action
        ));

        $access = true;

        if (!empty($list_access)) {
            foreach ($list_access as $rule) {
                if ($rule['controller'] == request::$controller && $rule['action'] == request::$action && !$rule['access'] && !$rule['del']) {
                    $access = false;
                }
            }
        } else {
            $access = false;
        }

        if (!$access) {
            view::load('access_denied');
            exit;
        }
    }

    public static function after()
    {
        //debug::vardump(debug::$data);
    }
}