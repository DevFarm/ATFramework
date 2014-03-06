<?php

/**
 * API Model Controller of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 20.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_Db as db;

class Model_Controller
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
            $rows = $setting['rows'];
        } else {
            $rows = implode(', ', $setting['rows']);

            if ($setting['index']) {
                $rows .= ', ' . $setting['index'];
            }
        }

        $sql = db::query('
			SELECT ' . $rows . ' FROM `at_controller` c
			LEFT JOIN `at_controller_action` ca
			ON (c.`id` = ca.`controller`)
			LEFT JOIN `at_action` a
			ON (ca.`action` = a.`id`)
			WHERE c.`del` = 0 AND a.`del` = 0
			' . $limit
        );

        if ($sql) {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }
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
            $where .= ' AND c.`del` <> 1';
        }

        if (!empty($data)) {
            foreach ($data as $row => $value) {
                if ((empty($value) && !is_numeric($value) || $row == 'del')) {
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

        $sql = db::query('
			SELECT ' . $rows . ' FROM `at_controller` c
			LEFT JOIN `at_controller_action` ca
			ON (c.`id` = ca.`controller`)
			LEFT JOIN `at_action` a
			ON (ca.`action` = a.`id`)
			WHERE' . $where . $limit);

        if ($sql) {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }

        return ['error' => 'unknown'];
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
            //TODO: нужно в rows добавлять альясы, так как они могут быть при join'е
            // SQL query WITH join tables
            $sql = db::query('
				SELECT ' . $rows . ' from `at_controller` c
				LEFT JOIN `at_controller_action` ca
				ON (c.`id` = ca.`controller`)
				LEFT JOIN `at_action` a
				ON (ca.`action` = a.`id`)
				WHERE  c.`id` = ' . intval($data['id'])
            );
        } else {
            // SQL query WITHOUT join tables
            $sql = db::query('SELECT ' . $rows . ' FROM `at_controller` WHERE `id` = ' . intval($data['id']));
        }

        if (db::num($sql)) {
            return ($setting['join']) ? db::fetch_all($sql) : db::fetch($sql);
        }

        return array('error' => 'unknown');
    }

    public static function add($data = array(), $setting = array())
    {
        $sql = db::query('
			INSERT INTO `at_controller` SET
				`name` = "' . $data['name'] . '",
				`description` = "' . $data['description'] . '",
				`icon` = "' . $data['icon'] . '"
			'
        );

        if ($sql) {
            $result = array(
                'id' => db::insert_id(),
                'result' => 'done'
            );

            if (!empty($data['actions'])) {
                foreach ($data['actions'] as $action) {
                    db::query('
                    INSERT INTO `at_controller_action` SET
                        `controller` = ' . intval($result['id']) . ',
                        `action` = ' . intval($action) . ',
                        `app` = ' . intval($data['app']) . '
                    '
                    );
                }
            }

            return $result;
        }

        return array('error' => 'unknown');
    }

    public static function edit($data = array(), $setting = array())
    {
        db::query('
            DELETE FROM `at_controller_action`
            WHERE `controller` = ' . intval($data['id']) . ' AND `app` = ' . intval($data['app'])
        );

        if (!empty($data['actions'])) {
            foreach ($data['actions'] as $action) {
                db::query('
                    INSERT INTO `at_controller_action` SET
                        `controller` = ' . intval($data['id']) . ',
                        `action` = ' . intval($action) . ',
                        `app` = ' . intval($data['app']) . '
                    '
                );
            }
        }

        $sql = db::query('
			UPDATE `at_controller` SET
				`name` = "' . $data['name'] . '",
				`description` = "' . $data['description'] . '",
				`icon` = "' . $data['icon'] . '"
			WHERE `id` = ' . intval($data['id'])
        );

        if ($sql) {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }

    public static function delete($data = array(), $setting = array())
    {
        $sql = db::query('UPDATE `at_controller` SET `del` = 1 WHERE `id` = ' . intval($data['id']));

        if ($sql) {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }

    public static function restore($data = array(), $setting = array())
    {
        $sql = db::query('UPDATE `at_controller` SET `del` = 0 WHERE `id` = ' . intval($data['id']));

        if ($sql) {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }
}