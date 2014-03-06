<?php

include_once 'lib/yandex_services.php';

class yandex
{
	public static function spell_check($text)
	{
		return yandex_services::spell_check($text);
	}

	public static function translate($data)
	{
		$default = array(
			'content_type'	=> 'json',
			'langs'			=> 'en-ru',
			'text'			=> '',
			'lang_to'		=> null,
			'source'		=> false
		);
		$data = array_merge($default, $data);

		$result = yandex_services::translate($data);

		if(!$data['source'])
		{
			switch($result['code'])
			{
				case 200:
				{
					return $result;
					break;
				}
				case 413:
				{
					return array('error' => 'Text too long');
					break;
				}
				case 422:
				{
					return array('error' => 'Unprocessable text');
					break;
				}
				case 501:
				{
					return array('error' => 'Lang not supported');
					break;
				}
			}
		}

		return $result;
	}
}