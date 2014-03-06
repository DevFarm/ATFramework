<?php

class yandex_services
{
	public static $translate_init = false;

	public static $translate_url = '';

	public static function spell_check($text)
	{
		$url  = 'http://speller.yandex.net/services/spellservice.json/checkText?text='.urlencode($text);
		$data = file_get_contents($url);

		if(!empty($data) && ($data = json_decode($data, true)) && is_array($data))
		{
			foreach($data as $item)
			{
				if(!empty($item['s'][0]))
				{
					$text = str_replace($item['word'], $item['s'][0], $text);
				}
			}
		}

		return $text;
	}

	public static function translate_init($content_type)
	{
		static::$translate_url = 'http://translate.yandex.net/api/v1/tr.json';

		if($content_type == 'xml')
		{
			static::$translate_url = 'http://translate.yandex.net/api/v1/tr';
		}

		static::$translate_init = true;
	}

	protected static function translate_get_lang()
	{
		$data = file_get_contents(static::$translate_url.'/getLangs');

		if(!$data)
		{
			return array('error' => 'Cannot connect to Yandex service. Try again later.');
		}

		return $data;
	}

	protected static function translate_detect($text, $format = 'plain')
	{
		$data = file_get_contents(static::$translate_url.'/detect?text='.urlencode($text).'&format='.$format);

		if(!$data)
		{
			return array('error' => 'Cannot connect to Yandex service. Try again later.');
		}

		return $data;
	}

	public static function translate($data = array())
	{
		static::translate_init($data['content_type']);

		if(static::$translate_init)
		{
			if($data['content_type'] == 'xml')
			{
				$xml = new SimpleXMLElement(static::translate_get_lang());
				$dirs = $xml->dirs->string;
				$langs = (array) $dirs;
			}
			else
			{
				$dirs  = json_decode(static::translate_get_lang(), true);
				$langs = $dirs['dirs'];
			}

			if(!$data['langs'])
			{
				if(!$data['lang_to'])
				{
					return array('error' => 'No finite element direction of translation.');
				}

				if($data['content_type'] == 'xml')
				{
					$xml = new SimpleXMLElement(static::translate_detect($data['text']));
					$lang_detected = (array) $xml->attributes()->lang;
					$lang_detected = $lang_detected[0];
				}
				else
				{
					$lang_detected = json_decode(static::translate_detect($data['text']), true);
					$lang_detected = $lang_detected['lang'];
				}

				$data['langs'] = $lang_detected.'-'.$data['lang_to'];
			}

			if(!in_array($data['langs'], $langs))
			{
				return array('error' => 'The selected direction of translation is not currently supported.');
			}

			$translate = file_get_contents(static::$translate_url.'/translate?lang='.$data['langs'].'&text='.urlencode($data['text']));

			if(!$data['source'])
			{
				if($data['content_type'] == 'xml')
				{
					$xml = new SimpleXMLElement($translate);
					$code = (array) $xml->attributes()->code;
					$lang = (array) $xml->attributes()->lang;

					$translate = array(
						'code'	=> $code[0],
						'lang'	=> $lang[0],
						'text'	=> (array) $xml->text
					);

					debug::vardump($translate, '', 1);
				}
				else
				{
					$translate = json_decode($translate, true);
					debug::vardump($translate, '', 1);
				}
			}

			return $translate;
		}
	}
}