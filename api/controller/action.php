<?php

/**
 * API Controller Action of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 20.02.14
 * @version 2.0.1
 */
class Controller_Action extends Controller_Base
{
    public static function _list($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all'
        );
        $params = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows' => '*',
            'flat' => false,
            'join' => false
        );
        $settings = array_merge($setting_default, $settings);

        return Model_Action::_list($params, $settings);

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
            'join' => false,
            'search' => array()
        );
        $settings = array_merge($setting_default, $settings);

        return Model_Action::search($params, $settings);
    }

    public static function count($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all'
        );
        $params = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows' => '*',
            'flat' => false,
            'join' => false
        );
        $settings = array_merge($setting_default, $settings);

        $result['count'] = count(Model_Action::search($params, $settings));

        return $result;
    }

    public static function info($params, $settings = array())
    {
        $params_default = array();
        $params = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows' => '*',
            'flat' => false,
            'join' => false
        );
        $settings = array_merge($setting_default, $settings);

        return Model_Action::info($params, $settings);
    }

    public static function add($params, $settings = array())
    {
        if (empty($params)) {
            return array('error' => 'wrong params');
        }

        $params_default = array();
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        return Model_Action::add($params, $settings);
    }

    public static function edit($params, $settings = array())
    {
        if (empty($params)) {
            return array('error' => 'wrong params');
        }
        if (!isset($params['id'])) {
            return array('error' => 'wrong id');
        }

        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        return Model_Action::edit($params, $settings);
    }

    public static function delete($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        return Model_Action::delete($params, $settings);
    }

    public static function restore($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        return Model_Action::restore($params, $settings);
    }
}