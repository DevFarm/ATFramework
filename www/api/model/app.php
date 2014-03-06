<?php

/**
 * API Model App of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_Db as db;
use classes\base\ATCore_String as string;

class Model_App
{
    public static function _list($data = array(), $setting = array())
    {
        $limit = '';

        if ($data['show'] != 'all') {
            if ($data['page']) {
                $data['page'] -= 1;
            }

            $limit = ' LIMIT ' . intval($data['page'] * $data['show']) . ', ' . intval($data['show']);
        }

        if (!is_array($setting['rows'])) {
            $rows = '*';
        } else {
            $rows = implode(', ', $setting['rows']);

            if ($setting['index']) {
                $rows .= ', ' . $setting['index'];
            }
        }

        $where = '1=1';
        if (!$data['del']) {
            $where .= ' AND `del` <> 1';
        }

        $sql = db::query('SELECT ' . $rows . ' FROM `at_app` WHERE ' . $where . $limit);

        if (db::num($sql)) {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }

        return array();
    }

    public static function search($data = array(), $setting = array())
    {
        $limit = '';

        if ($data['show'] != 'all') {
            if ($data['page']) {
                $data['page'] -= 1;
            }

            $limit = ' LIMIT ' . intval($data['page'] * $data['show']) . ', ' . intval($data['show']);
        }

        unset($data['page']);
        unset($data['show']);

        if (!is_array($setting['rows'])) {
            $rows = $setting['rows'];
        } else {
            $rows = implode(', ', $setting['rows']);

            if ($setting['index']) {
                $rows .= ', ' . $setting['index'];
            }
        }

        $where = ' 1=1';

        if (!$data['del']) {
            $where .= ' AND `del` <> 1';
        }

        if (!empty($data)) {
            foreach ($data as $row => $value) {
                if (empty($value) || $row == 'del') {
                    continue;
                }

                if (isset($setting['search'][$row]) && $setting['search'][$row] == 'loose') {
                    $row = str_replace('-', '.', $row);
                    $where .= ' AND ' . $row . ' LIKE "%' . $value . '%"';
                } else {
                    $row = str_replace('-', '.', $row);

                    if (is_numeric($value)) {
                        $where .= ' AND ' . $row . ' = ' . intval($value);
                    } else {
                        $where .= ' AND ' . $row . ' = "' . $value . '"';
                    }
                }
            }
        }

        $sql = db::query('SELECT ' . $rows . ' FROM `at_app` WHERE' . $where . $limit);

        if ($sql) {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }

        return array('error' => 'unknown');
    }

    public static function info($data = array(), $setting = array())
    {
        if (!is_array($setting['rows'])) {
            $rows = $setting['rows'];
        } else {
            $rows = implode(', ', $setting['rows']);

            if ($setting['index']) {
                $rows .= ', ' . $setting['index'];
            }
        }

        if ($setting['join']) {
            // SQL query WITH join tables
            $sql = db::query('
				SELECT ' . $rows . ' FROM `at_app` a
				LEFT JOIN `at_app_access` aa
				ON (a.`id` = aa.`app`)
				WHERE a.`id` = ' . intval($data['id']) . '');
        } else {
            // SQL query WITHOUT join tables
            $sql = db::query('SELECT ' . $rows . ' FROM `at_app` WHERE `id` = ' . intval($data['id']));
        }

        if (db::num($sql)) {
            return ($setting['join']) ? db::fetch_all($sql) : db::fetch($sql);
        }

        return array('error' => 'unknown');
    }

    public static function add($data = array(), $settings = array())
    {
        $check_settings = array('api_key' => $data['api_key']);

        if (!static::check_free_apikey($check_settings)) {
            return array('error' => 'api_key exists');
        }

        $sql = db::query('
		    INSERT INTO `at_app` SET
		        `api_key`   = "' . string::filter($data['api_key']) . '",
		        `name`      = "' . string::filter($data['name']) . '",
		        `comment`   = "' . string::filter($data['comment']) . '"
		');

        if ($sql) {
            $result = array(
                'id' => db::insert_id(),
                'result' => 'done'
            );

            foreach ($data['new_rule'] as $key => $rule) {
                $access = (!empty($data['new_access'][$key]) ? $data['new_access'][$key] : 0);
                $sql = db::query('
				    INSERT INTO `at_app_access` SET
						`app`       = ' . intval($result['id']) . ',
						`rule`      = "' . $rule . '",
						`access`    = "' . $access . '"
				');

                if (!$sql) {
                    static::delete(array('id' => $result['id']));

                    unset($result['result']);
                    $result['error'] = 'write rule';

                    break;
                }
            }

            return $result;
        }

        return array('error' => 'unknown');
    }

    public static function edit($data = array(), $settings = array())
    {
        $check_settings = array(
            'api_key' => $data['api_key'],
            'exists_mode' => true,
            'id' => $data['id']
        );

        if (!static::check_free_apikey($check_settings)) {
            return array('error' => 'api_key exists');
        }

        $sql = db::query('
			UPDATE `at_app` SET
				`api_key`   = "' . string::filter($data['api_key']) . '",
				`name`      = "' . string::filter($data['name']) . '",
				`comment`   = "' . string::filter($data['comment']) . '"
			WHERE `id` = ' . intval($data['id']) . '
		');

        if ($sql) {
            static::delete_rule(array('id' => $data['id']));

            foreach ($data['rule'] as $key => $rule) {
                $access = (!empty($data['access'][$key]) ? $data['access'][$key] : 0);
                $sql = db::query('INSERT INTO `at_app_access` SET
						`app`       = ' . intval($data['id']) . ',
						`rule`      = "' . $rule . '",
						`access`    = "' . $access . '"
					');

                if (!$sql) {
                    return array('error' => 'write rule');
                }
            }

            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }

    public static function delete($data = array(), $setting = array())
    {
        $sql = db::query('UPDATE `at_app` SET `del` = 1 WHERE `id` = ' . intval($data['id']));

        if ($sql) {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }

    public static function restore($data = array(), $setting = array())
    {
        $sql = db::query('UPDATE `at_app` SET `del` = 0 WHERE `id` = ' . intval($data['id']));

        if ($sql) {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }

    public static function delete_rule(array $data = array())
    {
        $sql = db::query('DELETE FROM `at_app_access` WHERE `app` = ' . intval($data['id']));

        if ($sql) {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }

    public static function access(array $data = array())
    {
        $sql = db::query('SELECT
				a.`id`,
				a.`api_key`,
				aa.`rule`
			FROM `at_app` a
				LEFT JOIN `at_app_access` aa
			ON
				(a.`id` = aa.`app`)
			WHERE
				a.`id` = ' . intval($data['id']) . '
				AND
				(
					aa.`rule` = "' . $data['controller'] . '/' . $data['action'] . '"
					OR
					aa.`rule` = "' . $data['controller'] . '/*"
					OR
					aa.`rule` = "*"
				)
				AND
				aa.`access` = 1
			LIMIT 1
		');

        return db::fetch($sql);
    }

    public static function last_access($id)
    {
        $sql = db::query('UPDATE `at_app` SET `last_access` = NOW() WHERE `id` = ' . intval($id));

        return true;
    }

    public static function check_free_apikey(array $data = array())
    {
        $default = array(
            'id' => 0,
            'exists_mode' => 0,
        );
        $data = array_merge($default, $data);

        if (!$data['exists_mode']) {
            $sql = db::query('SELECT 1 FROM `at_app` WHERE `api_key` = "' . $data['api_key'] . '"');

            if (db::num($sql)) {
                return false;
            }
        } elseif ($data['id']) {
            $params = array(
                'app_info' => array(
                    'id' => $data['id']
                )
            );

            $settings = array(
                'app_info' => array(
                    'index' => false,
                    'rows' => '*',
                    'flat' => false,
                    'join' => false
                )
            );

            $info_app = static::info($params['app_info'], $settings['app_info']);

            $sql = db::query('SELECT count(*)count FROM `at_app` WHERE `api_key` = "' . $data['api_key'] . '" OR `api_key` = "' . $info_app['api_key'] . '"');

            $result = db::fetch($sql);
            $count = intval($result['count']);

            if ($count > 1) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }
}