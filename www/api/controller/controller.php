<?php

/**
 * API Controller Controller of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 20.02.14
 * @version 2.0.1
 */
class Controller_Controller extends Controller_Base
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
            'rows' => [
                'c.id as c_id',
                'c.name as c_name',
                'c.description as c_description',
                'c.icon as c_icon',
                'c.del as c_del',
                'a.id as a_id',
                'a.name as a_name',
                'a.description as a_description',
                'a.icon as a_icon',
                'ca.app as ca_app'
            ],
            'flat' => false,
            'join' => false
        );
        $settings = array_merge($setting_default, $settings);

        $data = Model_Controller::_list($params, $settings);
        $result = array();

        foreach ($data as $item) {
            if (!isset($result[$item['ca_app']])) {
                $result[$item['ca_app']] = [
                    'controllers' => []
                ];
            }

            if (!isset($result[$item['ca_app']]['controllers'][$item['c_name']])) {
                $result[$item['ca_app']]['controllers'][$item['c_name']] = [
                    'id' => $item['c_id'],
                    'name' => $item['c_name'],
                    'description' => $item['c_description'],
                    'icon' => $item['c_icon'],
                    'del' => $item['c_del']
                ];
            }

            $result[$item['ca_app']]['controllers'][$item['c_name']]['actions'][$item['a_name']] = [
                'id' => $item['a_id'],
                'name' => $item['a_name'],
                'description' => $item['a_description'],
                'icon' => $item['a_icon']
            ];
        }

        return $result;
    }

    public static function light_list($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all'
        );
        $params = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows' => [
                'c.id as c_id',
                'c.name as c_name',
                'c.description as c_description',
                'c.icon as c_icon',
                'c.del as c_del',
                'a.id as a_id',
                'a.name as a_name',
                'a.description as a_description',
                'a.icon as a_icon',
                'ca.app as ca_app'
            ],
            'flat' => false,
            'join' => false
        );
        $settings = array_merge($setting_default, $settings);

        $data = Model_Controller::_list($params, $settings);
        $result = array();

        foreach ($data as $item) {
            if(!isset($result[$item['c_name']])) {
                $result[$item['c_name']] = [
                    'id' => $item['c_id'],
                    'name' => $item['c_name'],
                    'description' => $item['c_description'],
                    'icon' => $item['c_icon'],
                    'app' => $item['ca_app'],
                ];
            }

            $result[$item['c_name']]['actions'][$item['a_name']] = [
                'id' => $item['a_id'],
                'name' => $item['a_name'],
                'description' => $item['a_description'],
                'icon' => $item['a_icon'],
            ];
        }

        return $result;
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
            'rows' => [
                'c.id as c_id',
                'c.name as c_name',
                'c.description as c_description',
                'c.icon as c_icon',
                'c.del as c_del',
                'a.id as a_id',
                'a.name as a_name',
                'a.description as a_description',
                'a.del as a_del',
                'a.icon as a_icon',
                'ca.app as ca_app',
            ],
            'flat' => false,
            'join' => false,
            'search' => array()
        );
        $settings = array_merge($setting_default, $settings);

        $data = Model_Controller::search($params, $settings);
        $result = array();

        foreach ($data as $item) {
            if (!isset($result[$item['c_id']])) {
                $result[$item['c_id']] = [
                    'id' => $item['c_id'],
                    'name' => $item['c_name'],
                    'description' => $item['c_description'],
                    'icon' => $item['c_icon'],
                    'del' => $item['c_del']
                ];
            }

            $result[$item['c_id']]['actions'][$item['a_name']] = [
                'id' => $item['a_id'],
                'name' => $item['a_name'],
                'description' => $item['a_description'],
                'icon' => $item['a_icon'],
                'del' => $item['a_del']
            ];
        }

        return $result;
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

        $result['count'] = count(Model_Controller::search($params, $settings));

        return $result;
    }

    public static function info($params, $settings = array())
    {
        $params_default = array();
        $params = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows' => [
                'c.id as c_id',
                'c.name as c_name',
                'c.description as c_description',
                'c.icon as c_icon',
                'c.del as c_del',
                'a.id as a_id',
                'a.name as a_name',
                'a.description as a_description',
                'a.icon as a_icon',
                'ca.app as ca_app'
            ],
            'flat' => false,
            'join' => true
        );
        $settings = array_merge($setting_default, $settings);

        $data = Model_Controller::info($params, $settings);
        $result = [];

        foreach ($data as $item) {
            if(!isset($result[$item['c_id']])) {
                $result[$item['c_id']] = [
                    'id' => $item['c_id'],
                    'name' => $item['c_name'],
                    'description' => $item['c_description'],
                    'icon' => $item['c_icon'],
                    'del' => $item['c_del'],
                    'app' => $item['ca_app']
                ];
            }

            $result[$item['c_id']]['actions'][$item['a_id']] = [
                'id' => $item['a_id'],
                'name' => $item['a_name'],
                'description' => $item['a_description'],
                'icon' => $item['a_icon']
            ];
        }

        return $result[$item['c_id']];
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

        return Model_Controller::add($params, $settings);
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

        return Model_Controller::edit($params, $settings);
    }

    public static function delete($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        return Model_Controller::delete($params, $settings);
    }

    public static function restore($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        return Model_Controller::restore($params, $settings);
    }
}