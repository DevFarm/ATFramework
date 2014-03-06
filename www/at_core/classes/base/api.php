<?php

namespace classes\base;

use classes\base\ATCore_Debug as debug;
use Exception;

class ATCore_Api
{
    public static $_host = '';

    public static $private_key = '';

    public static $app_id = '';

    public static $_error = array();

    public static function query($query, array $params = array(), $setting = array())
    {
        $params['system_settings'] = $setting;
        ksort($params);

        $token = debug::start('api', $params);

        $access_data = array(
            'app_id' => static::$app_id,
            'token' => md5(static::$app_id . http_build_query($params) . static::$private_key)
        );

        $params = array_merge($access_data, $params);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, static::$_host . '/' . $query);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, false);

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (($result = curl_exec($curl)) === false) {
            throw new Exception(curl_error($curl));
        }

        $json = strstr(curl_getinfo($curl, CURLINFO_CONTENT_TYPE), 'json');
        curl_close($curl);

        if ($json) {
            $result = json_decode($result, true);
            debug::stop($token, $result);
        } else {
            //XML result
        }

        return $result;
    }

}