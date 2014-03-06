<?php

/**
 * API Model User_role_rule of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 21.10.12
 * @version 2.0
 */
class Model_User_role_rule
{
    public static function access($data = array(), $setting = array())
    {
        $sql = db::query('
			SELECT
				*
			FROM `at_user_role_rule` urr
			LEFT JOIN `at_user_role` ur ON (urr.`role` = ur.`id`)
			LEFT JOIN `at_user_rule` uru ON (urr.`rule` = uru.`id`)
			WHERE
				(
					ur.`id` = '.intval($data['role']).'
				AND
					urr.`full` = 1
				)
			OR
				(
					ur.`del` <> 1
				AND
					uru.`del` <> 1
				AND
					ur.`id` = '.intval($data['role']).'
				AND
					(
						urr.`full` = 1
					OR
						(uru.app = '.intval($data['app']).' AND uru.controller = "*")
					OR
						(uru.app = '.intval($data['app']).' AND uru.controller = "'.$data['controller'].'" AND uru.`action` = "*")
					OR
						(uru.app = '.intval($data['app']).' AND uru.controller = "'.$data['controller'].'" AND uru.`action` = "'.$data['action'].'")
					)
				)
		');

        return db::fetch_all($sql, $setting['index'], $setting['flat']);
    }

    public static function set($data = array(), $setting = array())
    {
        $sql = db::query('DELETE FROM `at_user_role_rule` WHERE `role` = '.intval($data['role']));

        if(!$data['full'])
        {
            foreach($data['rule'] as $rule)
            {
                $sql = db::query('
					INSERT INTO `at_user_role_rule` SET
						`role` = '.intval($data['role']).',
						`rule` = '.intval($rule).'
				');
            }
        }
        else
        {
            $sql = db::query('
				INSERT INTO `at_user_role_rule` SET
					`role` = '.intval($data['role']).',
					`rule` = 0,
					`full` = 1
			');
        }

        return array('result' => 'done');
    }

    public static function get($data = array(), $setting = array())
    {
        $sql = db::query('SELECT * FROM `at_user_role_rule` WHERE `role` = '.intval($data['role']));
        return db::fetch_all($sql, $setting['index'], $setting['flat']);
    }
}