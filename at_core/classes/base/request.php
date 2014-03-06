<?php

namespace classes\base;

use ATCore;
use Exception;

class ATCore_Request
{
    const GET = 'GET';
    const POST = 'POST';
    const HTTP = 'HTTP';
    const HTTPS = 'HTTPS';

    public static $protocol = '';

	public static $subdomain = '';

	public static $domain = '';

	public static $post = '80';

	public static $ip = '';

	public static $agent = '';

	public static $uri = '';

	public static $controller = 'main';

	public static $action = 'index';

	public static $params = '';

	public static $app_id = 0;

	public static function init()
	{
		static::$protocol = (isset(ATCore::$serv->https)) ? static::HTTPS : static::HTTP;

		$host		= explode('.', ATCore::$serv->http_host);
		$subdomain	= array_reverse($host);
		array_splice($subdomain, 0, 2);

		static::$subdomain = $subdomain;

		static::$domain = ATCore::$serv->http_host;

		static::$post = ATCore::$serv->server_port;

		static::$ip = ATCore::$serv->remote_addr;

		static::$agent = ATCore::$serv->http_user_agent;

		static::$uri = ATCore::$serv->request_uri;

		$arr_uri = array_slice(explode('/', static::$uri, 4), 1);
		$arr_uri = array_diff($arr_uri, array(''));

		if($arr_uri)
		{
			static::$controller = $arr_uri[0];
				static::$action = (isset($arr_uri[1]))?$arr_uri[1]:'index';
					static::$params = (isset($arr_uri[2]))?$arr_uri[2]:false;

			if(substr(static::$params, 0, 1) == '?')
			{
				static::$params = '';
			}
		}

		if(static::$action == 'list')
		{
			static::$action = '_list';
		}

		if(file_exists(ATCore::$serv->document_root . '/controller/' . static::$controller . '.php'))
		{
			if(method_exists('Controller_' . static::$controller, 'before'))
			{
				call_user_func('Controller_' . static::$controller . '::before');
			}

			call_user_func('Controller_' . ucfirst(static::$controller) . '::' . static::$action);

			if(method_exists('Controller_' . static::$controller, 'after'))
			{
				call_user_func('Controller_' . static::$controller . '::after');
			}
		}
		else
		{
			throw new Exception('Controller <b>'.static::$controller.'</b> not found!');
		}
	}
}

?>