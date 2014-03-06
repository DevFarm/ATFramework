<?php

use classes\base\ATCore_Auth;
use classes\base\ATCore_Api as api;
use classes\base\ATCore_F as f;

class Auth extends ATCore_Auth
{
    public static function login($email, $password, $outsider = false, $pass_source = true)
    {
        if ($pass_source) {
            $password = md5(static::SALT . $password);
        }

        $auth = api::query('auth/check', array('email' => $email, 'hash' => $password, 'role' => array(1, 2))); //TODO: нужно автоматически определять есть ли у данной роли доступ к приложению

        if (f::is_done($auth)) {
            if (!$outsider) {
                setcookie(static::COOKIE_NAME, $auth['id'] . '_' . $auth['password'], time() + static::COOKIE_EXPIRES, '/', ATCore::$serv->http_host);
            }

            static::process($auth);
            return array('result' => 'done');
        }

        return false;
    }
}