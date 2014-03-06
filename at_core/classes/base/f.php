<?php

namespace classes\base;

use classes\base\ATCore_Reference as reference;
use Exception;

class ATCore_F
{
	public static function array_flatten($array)
	{
		if(!is_array($array))
		{
			return false;
		}
		$result = array();
		foreach($array as $value)
		{
			if(is_array($value))
			{
				$result = array_merge($result, static::array_flatten($value));
			}
			else
			{
				$result[] = $value;
			}
		}

		return $result;
	}

	public static function is_done($array = array())
	{
		if(is_array($array) && isset($array['result']) && $array['result'] == 'done')
		{
			return true;
		}

		return false;
	}

	public static function is_error($array = array())
	{
		if(isset($array['error']))
		{
			return true;
		}

		return false;
	}

	public static function err_code($array = array())
	{
		if(static::is_error($array))
		{
			return $array['error'];
		}

		return null;
	}

	public static function api_reference($error_key)
	{
		if(isset(reference::$api_error[$error_key]))
		{
			return reference::$api_error[$error_key];
		}

		return $error_key;
	}

	public static function rand_string($length = 10, $multi_case = 1, $tmpl = 'abcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*~+=-_()/.,')
	{
		$str = '';

		if(is_array($length))
		{
			$length = rand($length[0], $length[1]);
		}

		$case = 0;

		for($i = 0; $i < $length; $i++)
		{
			if($multi_case)
			{
				$case = rand(0, 1);
			}

			if($case)
			{
				$str .= mb_strtoupper($tmpl[rand(0, (mb_strlen($tmpl)-1))]);
			}
			else
			{
				$str .= mb_strtolower($tmpl[rand(0, (mb_strlen($tmpl)-1))]);
			}
		}

		return $str;
	}

	public static function alias_controller($controller, $list)
	{
		if(!empty($list[$controller]))
		{
			return $list[$controller]['description'];
		}

		return $controller;
	}

	public static function alias_action($controller, $action, $list)
	{
		if(!empty($list[$controller]['actions'][$action]))
		{
			return $list[$controller]['actions'][$action]['description'];
		}

		return $action;
	}

	public static function gen_approve_hash()
	{
		$first  = sha1(static::rand_string().microtime(true));
		$middle = '00';
		$last   = md5(microtime().static::rand_string());

		$result = $first.$middle.$last;

		return substr($result, 0, 64);
	}

	public static function current_func()
	{
		$backtrace = debug_backtrace();
		unset($backtrace[0]);
		$backtrace = array_reverse($backtrace);
		if($result = array_pop($backtrace) === false)
		{
			throw new Exception('Must be called within the functions and methods');
		}

		return $result;
	}
}