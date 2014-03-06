<?php

/**
 * API Model User_rule of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 17.10.12
 * @version 2.0
 */
use classes\base\ATCore_Db as db;

class Model_User_rule
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

        $sql = db::query('SELECT '.$rows.' FROM `at_user_rule`'.$limit);

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
                if(empty($value) && !is_numeric($value))
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
			SELECT ur.'.$rows.' FROM `at_user_rule` ur
			LEFT JOIN `app` a ON (ur.`app` = a.`id`)
			LEFT JOIN `controller` c ON (ur.`controller` = c.`name`)
			LEFT JOIN `action` ac ON (ur.`action` = ac.`name`)
			WHERE'.$where.$limit
        );

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
            //TODO: нужно в rows добавлять альясы, так как они могут быть при join'е
            // SQL query WITH join tables
            $sql = db::query('SELECT '.$rows.' from `at_user_rule`');
        }
        else
        {
            // SQL query WITHOUT join tables
            $sql = db::query('SELECT '.$rows.' FROM `at_user_rule` WHERE `id` = '.intval($data['id']));
        }

        if(db::num($sql))
        {
            return ($setting['join']) ? db::fetch_all($sql) : db::fetch($sql);
        }

        return array('error'=> 'unknown');
    }

    public static function add($data = array(), $setting = array())
    {
        $sql = db::query('INSERT INTO `at_user_rule` SET
			`app`			= '.intval($data['app']).',
			`controller`	= "'.$data['controller'].'",
			`action`		= "'.$data['action'].'",
			`access`		= '.intval($data['access']).'
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
			UPDATE `at_user_rule` SET
				`app`			= '.intval($data['app']).',
				`controller`	= "'.$data['controller'].'",
				`action`		= "'.$data['action'].'",
				`access`		= '.intval($data['access']).'
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
        $sql = db::query('UPDATE `at_user_rule` SET `del` = 1 WHERE `id` = '.intval($data['id']));

        if($sql)
        {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }

    public static function restore($data = array(), $setting = array())
    {
        $sql = db::query('UPDATE `at_user_rule` SET `del` = 0 WHERE `id` = '.intval($data['id']));

        if($sql)
        {
            return array('result' => 'done');
        }

        return array('error' => 'unknown');
    }
}