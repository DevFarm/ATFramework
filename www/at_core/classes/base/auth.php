<?php

namespace classes\base;

use ATCore;
use classes\base\ATCore_Crypt as crypt;
use classes\base\ATCore_Api as api;
use classes\base\ATCore_F as f;

class ATCore_Auth
{
    const COOKIE_NAME = 'sessid';
    const COOKIE_EXPIRES = 31536000;
    const SESS_PREFIX = 'atsess_';
    const SALT = 'F#$RF@@d23D@#$R';
    const AES_SALT = '63482754679842310587941249673421';

    public static $user = array();

    public static $done = false;

    public static function init()
    {
        session_start();

        if (empty(static::$user)) {
            if (!empty($_SESSION) && isset($_SESSION[static::SESS_PREFIX . 'SDFVdfdfv'])) {
                static::$user = static::get_session();
                static::$done = true;
            } else {
                if (!empty($_COOKIE) && isset($_COOKIE[static::COOKIE_NAME])) {
                    $auth = api::query('auth/check', array('sess_id' => $_COOKIE[static::COOKIE_NAME]));

                    if (f::is_done($auth)) {
                        static::process($auth);

                        return true;
                    }

                    return false;
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    public static function process($data = array())
    {
        if (!empty($data)) {
            unset($data['result']);

            if (!empty($data)) {
                $sess_data = array(
                    static::SESS_PREFIX . 'VDfvdfFfv' => crypt::aes_encrypt($data['email'], static::AES_SALT),
                    static::SESS_PREFIX . 'VdeGrERgR' => crypt::aes_encrypt($data['name'], static::AES_SALT),
                    static::SESS_PREFIX . 'SDFVdfdfv' => crypt::aes_encrypt($data['id'], static::AES_SALT),
                    static::SESS_PREFIX . 'ErvgRrG4F' => crypt::aes_encrypt($data['surname'], static::AES_SALT),
                    static::SESS_PREFIX . 'VDfvdfFfv' => crypt::aes_encrypt($data['email'], static::AES_SALT),
                    static::SESS_PREFIX . 'G4GDER43G' => crypt::aes_encrypt($data['middle_name'], static::AES_SALT),
                    static::SESS_PREFIX . 'DfdsdfFs4' => crypt::aes_encrypt($data['role'], static::AES_SALT),
                );

                if (!empty($_SESSION)) {
                    $_SESSION = array_merge($_SESSION, $sess_data);
                } else {
                    $_SESSION = $sess_data;
                }

                static::$user = static::get_session();
                static::$done = true;
            }
        }
    }

    public static function get_session()
    {
        return array(
            'id' => crypt::aes_decrypt($_SESSION[static::SESS_PREFIX . 'SDFVdfdfv'], static::AES_SALT),
            'email' => crypt::aes_decrypt($_SESSION[static::SESS_PREFIX . 'VDfvdfFfv'], static::AES_SALT),
            'name' => crypt::aes_decrypt($_SESSION[static::SESS_PREFIX . 'VdeGrERgR'], static::AES_SALT),
            'surname' => crypt::aes_decrypt($_SESSION[static::SESS_PREFIX . 'ErvgRrG4F'], static::AES_SALT),
            'middle_name' => crypt::aes_decrypt($_SESSION[static::SESS_PREFIX . 'G4GDER43G'], static::AES_SALT),
            'role' => crypt::aes_decrypt($_SESSION[static::SESS_PREFIX . 'DfdsdfFs4'], static::AES_SALT),
        );
    }

    public static function login($email, $password, $outsider = false, $pass_source = true)
    {
        if ($pass_source) {
            $password = md5(static::SALT . $password);
        }

        $auth = api::query('auth/check', array('email' => $email, 'hash' => $password));

        if (f::is_done($auth)) {
            if (!$outsider) {
                setcookie(static::COOKIE_NAME, $auth['id'] . '_' . $auth['password'], time() + static::COOKIE_EXPIRES, '/', ATCore::$serv->http_host);
            }

            static::process($auth);
            return array('result' => 'done');
        }

        return false;
    }

    public static function logout()
    {
        setcookie(static::COOKIE_NAME, null, time() - 9999, '/', ATCore::$serv->http_host);

        if (!empty($_SESSION)) {
            foreach ($_SESSION as $key => $value) {
                if (strstr($key, static::SESS_PREFIX)) {
                    unset($_SESSION[$key]);
                }
            }
        }
    }
}