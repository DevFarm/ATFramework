<?php

/**
 * API Model Log of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_Db as db;

class Model_Log
{
    public static function _list(array $data = array())
    {
        $default = array(
            'page' => 1,
            'show' => 'all'
        );
        $data = array_merge($default, $data);

        $limit = '';
        if ($data['show'] != 'all') {
            if ($data['page']) {
                $data['page'] -= 1;
            }

            $limit = ' LIMIT ' . intval($data['page'] * $data['show']) . ', ' . intval($data['show']);
        }

        $sql = db::query('SELECT * FROM `at_log` ORDER BY `date` DESC' . $limit);

        if ($sql) {
            $result = db::fetch_all($sql);
            return $result;
        }

        return array('error' => 'unknown');
    }

    public static function count(array $data = array())
    {
        $sql = db::query('SELECT count(*)count FROM `at_log`');
        return db::fetch($sql);
    }


    public static function api_list(array $data = array())
    {
        $default = array(
            'page' => 1,
            'show' => 'all'
        );
        $data = array_merge($default, $data);

        $limit = '';
        if ($data['show'] != 'all') {
            if ($data['page']) {
                $data['page'] -= 1;
            }

            $limit = ' LIMIT ' . intval($data['page'] * $data['show']) . ', ' . intval($data['show']);
        }

        $sql = db::query('SELECT * FROM `at_log_api` ORDER BY `date` DESC, `id` DESC' . $limit);

        if ($sql) {
            $result = db::fetch_all($sql);
            return $result;
        }

        return array('error' => 'unknown');
    }

    public static function api_count(array $data = array())
    {
        $sql = db::query('SELECT count(*)count FROM `at_log_api`');
        return db::fetch($sql);
    }
}