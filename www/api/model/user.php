<?php

use classes\base\ATCore_Db as db;
use classes\base\ATCore_String as string;

class Model_User
{
    public static function _list(array $data = array(), array $setting = array())
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

        $where = '1=1';
        if (!$data['del']) {
            $where .= ' AND `del` <> 1';
        }

        $sql = db::query('SELECT ' . $rows . ' FROM `at_user` WHERE ' . $where . $limit);

        if ($sql) {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }
    }

    public static function count(array $data = array(), array $setting = array())
    {
        if (empty($data)) {
            $sql = db::query('SELECT count(*)count FROM `at_user`');
        }

        return db::fetch($sql);
    }

    public static function search(array $data = array(), array $setting = array())
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

        $sql = db::query('SELECT ' . $rows . ' FROM `at_user` WHERE' . $where . $limit);

        if ($sql) {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }
    }

    public static function info(array $data = array(), array $setting = array())
    {
        $sql = db::query('SELECT * FROM `at_user` WHERE `id` = ' . intval($data['id']));

        return db::fetch($sql);
    }

    public static function add(array $data = array(), array $setting = array())
    {
        if (!empty($data)) {
            $fields = array();

            foreach ($data as $k => $v) {
                if (!empty($v)) {
                    $fields[] = '`' . $k . '` = "' . string::filter($v) . '"';
                }
            }

            $sql = db::query('
				INSERT INTO `at_user` SET
				' . implode(', ', $fields) . '
			');


            if ($sql) {
                $result = array(
                    'result' => 'done',
                    'id' => db::insert_id()
                );

                return $result;
            } else {
                return array('error' => 'cannot add user');
            }
        }

        return array('error' => 'wrong params');
    }

    public static function edit(array $data = array(), array $setting = array())
    {
        $default = array(
            'password' => ''
        );
        $data = array_merge($default, $data);

        $except_fields = array(
            'id',
            'repassword'
        );

        if (!empty($data)) {
            if (!empty($data['id'])) {
                $fields = array();

                foreach ($data as $k => $v) {
                    if (in_array($k, $except_fields)) {
                        continue;
                    }

                    if (!empty($v)) {
                        $fields[] = '`' . $k . '` = "' . string::filter($v) . '"';
                    }
                }

                $sql = db::query('
					UPDATE `at_user` SET
					' . implode(', ', $fields) . '
					WHERE `id` = ' . intval($data['id']) . '
				');

                if ($sql) {
                    return array('result' => 'done');
                }
            }

            return array('error' => 'wrong id');
        }

        return array('error' => 'wrong params');
    }

    public static function delete(array $data = array(), array $setting = array())
    {
        if (!empty($data['id'])) {
            db::query('UPDATE `at_user` SET `del` = 1 WHERE `id` = ' . intval($data['id']));

            return array('result' => 'done');
        }

        return array('error' => 'wrong id');
    }

    public static function restore(array $data = array(), array $setting = array())
    {
        if (!empty($data['id'])) {
            db::query('UPDATE `at_user` SET `del` = 0 WHERE `id` = ' . intval($data['id']));

            return array('result' => 'done');
        }

        return array('error' => 'wrong id');
    }
}