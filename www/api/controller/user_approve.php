<?php

/**
 * API Controller User of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 25.10.12
 * @version 2.0
 */
class Controller_User_approve extends Controller_Base
{

    public static function _list($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all',
            'del'  => false
        );
        $params         = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows'  => '*',
            'flat'  => false,
            'join'  => false
        );
        $settings        = array_merge($setting_default, $settings);

        return Model_User::_list($params, $settings);
    }

    public static function count($params, $settings = array())
    {
        return Model_User::count($params);
    }

    public static function info($params)
    {
        if(!empty($params))
        {
            if(isset($params['hash']))
            {
                return Model_User_approve::info($params);
            }
        }
    }

    public static function add($params)
    {
        if(!empty($params))
        {
            return Model_User_approve::add($params);
        }
    }

    public static function delete($params)
    {
        if(!empty($params))
        {
            if(isset($params['hash']))
            {
                return Model_User_approve::delete($params);
            }
        }
    }
}