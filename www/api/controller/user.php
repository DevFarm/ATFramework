<?php

/**
 * API Controller User of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 25.10.12
 * @version 2.0
 */
class Controller_User extends Controller_Base
{

    public static function _list($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all',
            'del' => false
        );
        $params = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows' => '*',
            'flat' => false,
            'join' => false
        );
        $settings = array_merge($setting_default, $settings);

        return Model_User::_list($params, $settings);
    }

    public static function count($params, $settings = array())
    {
        return Model_User::count($params, $settings);
    }

    public static function search($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all',
            'del' => false
        );
        $params = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows' => '*',
            'flat' => false,
            'join' => false
        );
        $settings = array_merge($setting_default, $settings);

        return Model_User::search($params, $settings);
    }

    public static function info($params, $settings = array())
    {
        if (!empty($params)) {
            if (isset($params['id'])) {
                return Model_User::info($params, $settings);
            }
        }
    }

    public static function add($params, $settings = array())
    {
        if (!empty($params)) {
            return Model_User::add($params, $settings);
        }
    }

    public static function edit($params, $settings = array())
    {
        if (!empty($params)) {
            if (isset($params['id'])) {
                return Model_User::edit($params, $settings);
            }
        }
    }

    public static function delete($params, $settings = array())
    {
        if (!empty($params)) {
            if (isset($params['id'])) {
                return Model_User::delete($params, $settings);
            }
        }
    }

    public static function restore($params, $settings = array())
    {
        if (!empty($params)) {
            if (isset($params['id'])) {
                return Model_User::restore($params, $settings);
            }
        }
    }
}