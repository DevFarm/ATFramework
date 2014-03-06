<?php

/**
 * API Model Auth of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_Db as db;
use classes\base\ATCore_String as string;

class Model_Auth
{
    public static function check(array $data = array())
    {
        $default = array(
            'uid' => 0,
            'email' => '',
            'role' => array()
        );
        $data = array_merge($default, $data);

        if ($data['role'] == 'all') {
            $role = '';
        } else {
            $role = ' AND `role` IN(' . implode(',', $data['role']) . ') ';
        }

        if ($data['uid']) {
            $sql = db::query('
				SELECT * FROM `at_user`
				WHERE
					`id` = ' . intval($data['uid']) . '
				AND
					`password` = "' . $data['hash'] . '"
				AND `del` <> 1 ' . $role . '
				LIMIT
			');
        } else {
            $sql = db::query('
				SELECT * FROM `at_user`
				WHERE
				(
					`email` = "' . string::filter($data['email']) . '"
				OR
					(
						`login`  = "' . string::filter($data['email']) . '"
					AND
						`login` <> ""
					)
				)
				AND
					`password` = "' . $data['hash'] . '"
				AND
					`del` <> 1 ' . $role . '
				LIMIT 1
			');
        }

        if ($sql) {
            if (db::num($sql)) {
                $result = db::fetch($sql);
                $result['result'] = 'done';
                return $result;
            }

            return array('error' => 'failed auth');
        }

        return array('error' => 'unknown');
    }
}