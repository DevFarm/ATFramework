<?php

/**
 * API Controller User_role_rule of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 21.10.12
 * @version 2.0
 */
class Controller_User_role_rule extends Controller_Base
{
    public static function access($params, $settings = array())
    {
        $params_default = array(
            'role'	=> 0
        );
        $params         = array_merge($params_default, $params);

        $setting_default = array(
            'index'	=> false,
            'flat'	=> false
        );
        $settings        = array_merge($setting_default, $settings);
        return Model_User_role_rule::access($params, $settings);
    }

    public static function get($params, $settings = array())
    {
        if(empty($params))
        {
            return array('error' => 'wrong params');
        }

        $params_default = array();
        $params         = array_merge($params_default, $params);

        $setting_default = array();
        $settings        = array_merge($setting_default, $settings);

        return Model_User_role_rule::get($params, $settings);
    }

    public static function set($params, $settings = array())
    {
        if(empty($params))
        {
            return array('error' => 'wrong params');
        }

        $params_default = array(
            'full'	=> 0
        );
        $params         = array_merge($params_default, $params);

        $setting_default = array();
        $settings        = array_merge($setting_default, $settings);

        return Model_User_role_rule::set($params, $settings);
    }
}