<?php

class Model_User_approve
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

        $where = '1=1';
        if(!$data['del'])
        {
            $where .= ' AND `del` <> 1';
        }

        $sql = db::query('SELECT '.$rows.' FROM `user` WHERE '.$where.$limit);

        if($sql)
        {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }
    }

    public static function count(array $array = array())
    {
        if(empty($data))
        {
            $sql = db::query('SELECT count(*)count FROM `user`');
        }

        return db::fetch($sql);
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

        if(!$data['del'])
        {
            $where .= ' AND `del` <> 1';
        }

        if(!empty($data))
        {
            foreach($data as $row => $value)
            {
                if(empty($value) || $row == 'del')
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

        $sql = db::query('SELECT '.$rows.' FROM `user` WHERE'.$where.$limit);

        if($sql)
        {
            return db::fetch_all($sql, $setting['index'], $setting['flat']);
        }
    }

    public static function info(array $data = array())
    {
        $sql = db::query('SELECT * FROM `user_approve` WHERE `hash` = "'.string::filter($data['hash']).'" LIMIT 1');

        return db::fetch($sql);
    }

    public static function add(array $data = array())
    {
        if(!empty($data))
        {
            $sql = db::query('
				INSERT INTO `user_approve` SET
					`user`		= '.intval($data['user']).',
					`hash`		= "'.$data['hash'].'",
					`expires`	= "'.$data['expires'].'"
			');

            if($sql)
            {
                return array('result' => 'done');
            }
        }

        return array('error' => 'wrong params');
    }

    public static function delete(array $data = array())
    {
        if(!empty($data['hash']))
        {
            db::query('DELETE FROM `user_approve` WHERE `hash` = "'.string::filter($data['hash']).'"');

            return array('result' => 'done');
        }

        return array('error' => 'wrong id');
    }
}