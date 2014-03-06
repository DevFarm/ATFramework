<?php

/**
 * API Controller Base of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_Log as log;

class Controller_Base
{
    public static function before($params)
    {
        $access_ctrl = 0; //TODO: убрать на продакшене

        if ($access_ctrl) {
            if (!empty($params['token']) && !empty($params['app_id'])) {
                $app = Model_App::access(array(
                    'controller' => request::$controller,
                    'action' => (request::$action == '_list') ? 'list' : request::$action,
                    'id' => $params['app_id']
                ));

                $token = $params['token'];

                unset($params['token']);
                unset($params['app_id']);

                ksort($params);

                if (!empty($app)) {
                    $crc = md5($app['id'] . http_build_query($params) . $app['api_key']);
                    if ($token == $crc) {
                        Model_App::last_access($app['id']);
                        return true;
                    } else {
                        log::add(json_encode(array('token ' . $token . ' incorrect')), 'api');
                        echo 'Token ' . $token . ' incorrect!';
                        exit;
                    }
                } else {
                    log::add(json_encode(array('access denied')), 'api');
                    echo 'Access denied!';
                    exit;
                }
            } else {
                log::add(json_encode(array('access denied')), 'api');
                echo 'Access denied!';
                exit;
            }
        } else {
            //TODO: обязательно убрать потом!!!
            if ($_GET) {
                $_POST = array_merge($_POST, $_GET);
            }
        }
    }

}