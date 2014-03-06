<?php

/**
 * API Controller App of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 06.10.12
 * @version 2.0
 */
use classes\base\ATCore_F as f;

class Controller_App extends Controller_Base
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

        return Model_App::_list($params, $settings);
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

        return Model_App::search($params, $settings);
    }

    public static function count($params, $settings = array())
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

        $result['count'] = count(Model_App::search($params, $settings));

        return $result;
    }

    public static function info($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows' => '*',
            'flat' => false,
            'join' => false
        );
        $settings = array_merge($setting_default, $settings);

        if ($params['id']) {
            $result = Model_App::info($params, $settings);

            if ($settings['join']) {
                $mod_result = array();

                foreach ($result as $data) {
                    if (empty($mod_result)) {
                        $mod_result = array(
                            'id' => $data['id'],
                            'api_key' => $data['api_key'],
                            'last_access' => $data['last_access'],
                            'comment' => $data['comment'],
                            'name' => $data['name'],
                            'del' => $data['del'],

                        );
                    }

                    $mod_result['rule'][] = $data['rule'];
                    $mod_result['access'][] = $data['access'];
                }

                return $mod_result;
            }

            return $result;
        }

        return array('error' => 'wrong id');
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

        return Model_App::add($params, $settings);
    }

    public static function edit($params, $settings = array())
    {
        if (empty($params)) {
            return array('error' => 'wrong params');
        }

        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        if (!$params['id']) {
            return array('error' => 'wrong id');
        }

        return Model_App::edit($params, $settings);
    }

    public static function delete($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        return Model_App::delete($params, $settings);
    }

    public static function restore($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params = array_merge($params_default, $params);

        $setting_default = array();
        $settings = array_merge($setting_default, $settings);

        return Model_App::restore($params, $settings);
    }

    public static function generate_api_key()
    {
        $api_key = f::rand_string(array(
            25,
            30
        ));

        if (Model_App::check_free_apikey(array('api_key' => $api_key))) {
            return array('api_key' => $api_key);
        } else {
            return array('api_key' => '');
        }
    }
}