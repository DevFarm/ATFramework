<?php

/**
 * Controller Main of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 24.11.12
 * @version 2.0
 */
class Controller_Main extends Controller_Base
{
	public static function index()
	{
			view::load('main/index');
	}

	/**
	 * Action Search (main/search) - search data on GET parameters.
	 * In terminology CRUD is Read.
	 * If empty GET params or errors, call Action Index.
	 */
	public static function search()
	{
		$pagination = array(
			'page' => 1,
			'show' => 20
		);

		if(isset($_GET['page']))
		{
			$pagination['page'] = intval($_GET['page']);
			unset($_GET['page']);
		}

		if(isset($_GET['show']))
		{
			$pagination['show'] = intval($_GET['show']);
			unset($_GET['show']);
		}

		if($_GET)
		{
			$params   = array(
				'main_search' => $_GET
			);
			$settings = array(
				'main_search' => array()
			);

			$params = array_merge($params['main_search'], $pagination);

			$search_main = api::query('main/search', $params['main_search'], $settings['main_search']);

			if(f::is_done($search_main))
			{
				unset($search_main['result']);
				view::load('main/search', array('list' => $search_main));
			}
			else
			{
				static::index();
			}
		}
		else
		{
			static::index();
		}
	}

	/**
	 * Action Add (main/add) - saving data from POST parameters.
	 * In terminology CRUD is Create.
	 */
	public static function add()
	{
		$params = array(
			'main_add' => $_POST
		);

		$settings = array(
			'main_add' => array()
		);

		if($_POST)
		{
			$validation = form::validation(array());

			if($validation)
			{
				$add_main = api::query('main/add', $params['main_add'], $settings['main_add']);

				if(f::is_done($add_main))
				{
					header('Location: /main');
				}
			}
		}

		view::load('main/form');
	}

	/**
	 * Action Edit (main/edit) - edit data on ID element.
	 * In terminology CRUD is Update.
	 */
	public static function edit()
	{
		if(request::$params)
		{
			$params = array(
				'main_info' => array(
					'id' => request::$params
				),
				'main_edit' => $_POST
			);

			$settings = array(
				'main_info' => array(),
				'main_edit' => array()
			);

			$info_main = api::query('main/info', $params['main_info'], $settings['main_info']);

			if($_POST)
			{
				$validation = form::validation(array());

				if($validation)
				{
					if(!empty($info_main))
					{
						$params['user_rule_edit'] = $info_main['id'];
					}
					$edit_main = api::query('main/edit', $params['main_edit'], $settings['main_edit']);

					if(f::is_done($edit_main))
					{
						header('Location: /main');
					}
				}
			}
			else
			{
				if(!empty($info_main))
				{
					$_POST = array_merge($_POST, $info_main);
				}
			}

			view::load('main/form');
		}
		else
		{
			header('Location: /main');
		}
	}

	/**
	 * Action Delete (main/delete) - set row 'del'=1 on ID element.
	 * In terminology CRUD is Update.
	 */
	public static function delete()
	{
		if(request::$params)
		{
			$params = array(
				'main_delete' => array(
					'id' => request::$params
				)
			);

			$settings = array(
				'main_delete' => array()
			);

			$delete_main = api::query('main/delete', $params['main_delete'], $settings['main_delete']);

			if(f::is_done($delete_main))
			{
				header('Location: /main');
			}
		}
		else
		{
			header('Location: /main');
		}
	}

	/**
	 * Action Restore (main/restore) - set row 'del'=0 on ID element.
	 * In terminology CRUD is Update.
	 */
	public static function restore()
	{
		if(request::$params)
		{
			$params = array(
				'main_restore' => array(
					'id' => request::$params
				)
			);

			$settings = array(
				'main_restore' => array()
			);

			$restore_main = api::query('main/restore', $params['main_restore'], $settings['main_restore']);

			if(f::is_done($restore_main))
			{
				header('Location: /main');
			}
		}
		else
		{
			header('Location: /main');
		}
	}
}