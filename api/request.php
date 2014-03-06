<?php

use classes\base\ATCore_Request;

class Request extends ATCore_Request
{
    public static $token = '';

    public static function init()
    {
        header('Content-type: application/json; charset=utf-8');

        static::$protocol = (isset(ATCore::$serv->https)) ? static::HTTPS : static::HTTP;

        static::$subdomain = '';

        static::$domain = ATCore::$serv->http_host;

        static::$post = ATCore::$serv->server_port;

        static::$ip = ATCore::$serv->remote_addr;

        static::$uri = ATCore::$serv->request_uri;

        $arr_uri = array_slice(explode('/', static::$uri), 1);
        $arr_uri = array_diff($arr_uri, array(''));

        if ($arr_uri) {
            static::$controller = $arr_uri[0];
            static::$action = (isset($arr_uri[1])) ? $arr_uri[1] : 'index';
            static::$params = (isset($arr_uri[2])) ? $arr_uri[2] : false;
        }

        if (static::$action == 'list') {
            static::$action = '_list';
        }

        if (file_exists(ATCore::$serv->document_root . '/controller/' . static::$controller . '.php')) {
            if (method_exists('Controller_' . static::$controller, 'before')) {
                call_user_func('Controller_' . static::$controller . '::before', $_POST);
            }

            $settings = (!empty($_POST['system_settings'])) ? $_POST['system_settings'] : array();

            static::$app_id = $_POST['app_id'];
            static::$token = $_POST['token'];

            unset($_POST['system_settings']);
            unset($_POST['token']);
            unset($_POST['app_id']);

            $result = call_user_func('Controller_' . ucfirst(static::$controller) . '::' . static::$action, $_POST, $settings);
            $result = json_encode($result);

            if (method_exists('Controller_' . static::$controller, 'after')) {
                call_user_func('Controller_' . static::$controller . '::after', $_POST);
            }

            echo $result;
        } else {
            throw new Exception('Controller <b>' . static::$controller . '</b> not found!');
        }
    }
}