<?php

/**
 * API Controller User_role of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 25.10.12
 * @version 2.0
 */
class Controller_User_role extends Controller_Base
{
    public static function _list($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all'
        );
        $params         = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows'  => '*',
            'flat'  => false,
            'join'  => false
        );
        $settings        = array_merge($setting_default, $settings);

        return Model_User_role::_list($params, $settings);
    }

    public static function search($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all'
        );
        $params         = array_merge($params_default, $params);

        $setting_default = array(
            'index'  => false,
            'rows'   => '*',
            'flat'   => false,
            'join'   => false,
            'search' => array()
        );
        $settings        = array_merge($setting_default, $settings);

        return Model_User_role::search($params, $settings);
    }

    public static function count($params, $settings = array())
    {
        $params_default = array(
            'page' => 1,
            'show' => 'all'
        );
        $params         = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows'  => '*',
            'flat'  => false,
            'join'  => false
        );
        $settings        = array_merge($setting_default, $settings);

        $result['count'] = count(Model_User_role::search($params, $settings));

        return $result;
    }

    public static function info($params, $settings = array())
    {
        $params_default = array();
        $params         = array_merge($params_default, $params);

        $setting_default = array(
            'index' => false,
            'rows'  => '*',
            'flat'  => false,
            'join'  => false
        );
        $settings        = array_merge($setting_default, $settings);

        $result = Model_User_role::info($params, $settings);

        if($settings['join'])
        {
            $mod_result = array();

            foreach($result as $key => $data)
            {
                if(empty($mod_result))
                {
                    $mod_result = array(
                        'id'          => $data['id'],
                        'name'        => $data['name'],
                        'description' => $data['description'],
                        'del'         => $data['del']
                    );
                }

                if(!isset($mod_result['full']) || (isset($mod_result['full']) && !$mod_result['full']))
                {
                    $mod_result['full'] = $data['full'];
                }

                $mod_result['rule'][] = $data['rule'];
            }

            return $mod_result;
        }

        return $result;
    }

    public static function add($params, $settings = array())
    {
        if(empty($params))
        {
            return array('error' => 'wrong params');
        }

        $params_default = array();
        $params         = array_merge($params_default, $params);

        $setting_default = array();
        $settings        = array_merge($setting_default, $settings);

        return Model_User_role::add($params, $settings);
    }

    public static function edit($params, $settings = array())
    {
        if(empty($params))
        {
            return array('error' => 'wrong params');
        }
        if(!isset($params['id']))
        {
            return array('error' => 'wrong id');
        }

        $params_default = array();
        $params         = array_merge($params_default, $params);

        $setting_default = array();
        $settings        = array_merge($setting_default, $settings);

        return Model_User_role::edit($params, $settings);
    }

    public static function delete($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params         = array_merge($params_default, $params);

        $setting_default = array();
        $settings        = array_merge($setting_default, $settings);

        return Model_User_role::delete($params, $settings);
    }

    public static function restore($params, $settings = array())
    {
        $params_default = array(
            'id' => 0
        );
        $params         = array_merge($params_default, $params);

        $setting_default = array();
        $settings        = array_merge($setting_default, $settings);

        return Model_User_role::restore($params, $settings);
    }
}