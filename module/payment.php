<?php

class payment
{
	public static $type = '';

	public static $login = '';

	public static $password = '';

	public static $data = array();

	private static $error = null;

	public static function init()
	{
		switch(static::$type)
		{
			case 'robokassa':
			{
				static::$data = array(
					'MrchLogin'    => static::$login,
					'MrchPassword' => static::$password
				);
				break;
			}
			default:
			{
				static::$error = 'Unknown payment system';
				break;
			}
		}

		debug::vardump(static::$data);
	}

	public static function process(array $data = array())
	{
		static::$data                   = array_merge(static::$data, $data);
		static::$data['SignatureValue'] = static::signature();
	}

	private static function signature()
	{
		$data = static::$data;
		$crc  = null;

		switch(static::$type)
		{
			case 'robokassa':
			{
				$crc = md5($data['MrchLogin'].':'.$data['OutSum'].':'.$data['InvId'].':'.$data['MrchPassword'].':Shp_item='.$data['Shp_item']);
				break;
			}
			default:
			{
				break;
			}
		}

		if($crc)
		{
			return $crc;
		}

		return false;
	}
}