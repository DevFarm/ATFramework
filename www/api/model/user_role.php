<?php

/**
 * API Model User_role of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 25.10.12
 * @version 2.0
 */
class Model_User_role
{
    public static function _list($data = array(), $setting = array())
    {
        $limit = '';

        if($data['show'] != 'all')
        {
            if($data['page'])
            {
                $data['page'] -= 1;
            }

            $limit = ' LIMIT '.intval($data['page'] * $data['show']).', '.intval($data['show']);
        }

        if(!is_array($setting['rows']))
        {
            $rows = $setting['rows'];
        }
        else
        {
            $rows = implode(', ', $setting['rows']);

            if($setting['index'])
            {
                $rows .= ', '.$setting['index'];
            }
        }

        $sql = db::query('SELECT '.$rows.' FROM `at_user_role`'.$limit);

        if($sql)
        {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }
    }

    public static function search($data = array(), $setting = array())
    {
        $limit = '';

        if($data['show'] != 'all')
        {
            if($data['page'])
            {
                $data['page'] -= 1;
            }

            $limit = ' LIMIT '.intval($data['page'] * $data['show']).', '.intval($data['show']);
        }

        unset($data['page']);
        unset($data['show']);

        if(!is_array($setting['rows']))
        {
            $rows = $setting['rows'];
        }
        else
        {
            $rows = implode(', ', $setting['rows']);

            if($setting['index'])
            {
                $rows .= ', '.$setting['index'];
            }
        }

        $where = ' 1=1';

        if(!empty($data))
        {
            foreach($data as $row => $value)
            {
                if(empty($value))
                {
                    continue;
                }

                if(isset($setting['search'][$row]) && $setting['search'][$row] == 'loose')
                {
                    $row = str_replace('-', '.', $row);
                    $where .= ' AND '.$row.' LIKE "%'.$value.'%"';
                }
                else
                {
                    $row = str_replace('-', '.', $row);

                    if(is_numeric($value))
                    {
                        $where .= ' AND '.$row.' = '.intval($value);
                    }
                    else
                    {
                        $where .= ' AND '.$row.' = "'.$value.'"';
                    }
                }
            }
        }

        $sql = db::query('
			SELECT '.$rows.' FROM `at_user_role`
			WHERE'.$where.$limit);

        if($sql)
        {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }
    }

    public static function info($data = array(), $setting = array())
    {
        if(!is_array($setting['rows']))
        {
            $rows = $setting['rows'];
        }
        else
        {
            $rows = implode(', ', $setting['rows']);

            if($setting['index'])
            {
                $rows .= ', '.$setting['index'];
            }
        }

        if($setting['join'])
        {
            // SQL query WITH join tables
            $sql = db::query('
				SELECT '.$rows.' from `at_user_role` ur
				LEFT JOIN `at_user_role_rule` urr ON (ur.`id` = urr.`role`)
				WHERE `id` = '.intval($data['id']).'
			');
        }
        else
        {
            // SQL query WITHOUT join tables
            $sql = db::query('SELECT '.$rows.' FROM `at_user_role` WHERE `id` = '.intval($data['id']));
        }

        if(db::num($sql))
        {
            return ($setting['join']) ? db::fetch_all($sql) : db::fetch($sql);
        }

        return array('error'=> 'unknown');
    }

    public static function add($data = array(), $setting = array())
    {
        $sql = db::query('INSERT INTO `at_user_role` SET
			`name`			= "'.$data['name'].'",
			`description`	= "'.$data['description'].'"
		');

        if($sql)
        {
            $result = array(
                'id'        => db::insert_id(),
                'result'    => 'done'
            );

            return $result;
        }

        return array('error'=> 'unknown');
    }

    public static function edit($data = array(), $setting = array())
    {
        $sql = db::query('
			UPDATE `at_user_role` SET
				`name`			= "'.$data['name'].'",
				`description`	= "'.$data['description'].'"
			WHERE `id` = '.intval($data['id'])
        );

        if($sql)
        {
            return array('result' => 'done');
        }

        return array('error'=> 'unknown');
    }

    public static function delete($data = array(), $setting = array())
    {
        $sql = db::query('UPDATE `at_user_role` SET `del` = 1 WHERE `id` = '.intval($data['id']));

        if($sql)
        {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }

    public static function restore($data = array(), $setting = array())
    {
        $sql = db::query('UPDATE `at_user_role` SET `del` = 0 WHERE `id` = '.intval($data['id']));

        if($sql)
        {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }
}