<?php

/**
 * API Controller Auth of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */

class Controller_Auth extends Controller_Base
{
    public static function check($params, $settings = array())
    {
        if (!empty($params)) {
            if (isset($params['sess_id'])) {
                $data = explode('_', $params['sess_id']);

                return Model_Auth::check(array(
                    'uid' => $data[0],
                    'hash' => $data[1]
                ));
            } elseif (isset($params['email'])) {
                return Model_Auth::check(array(
                    'email' => $params['email'],
                    'hash' => $params['hash'],
                    'role' => (!empty($params['role']) ? $params['role'] : 'all')
                ));
            }
        }

        return array('error' => 'unknown');
    }
}