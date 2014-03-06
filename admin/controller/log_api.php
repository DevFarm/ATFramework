<?php

/**
 * Admin Controller Log_api of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 10.12.12
 * @version 2.0
 */
use classes\base\ATCore_Api as api;
use classes\base\ATCore_View as view;
use classes\base\ATCore_F as f;
use classes\base\ATCore_Form as form;

class Controller_Log_api extends Controller_Base
{
    /**
     * Action Index (log_api/index) - show data list or other.
     * In terminology CRUD is Read.
     */
    public static function index()
    {
        $pagination = array(
            'page' => 1,
            'show' => 20
        );

        if (isset($_GET['page'])) {
            $pagination['page'] = intval($_GET['page']);
        }
        if (isset($_GET['show'])) {
            $pagination['show'] = intval($_GET['show']);
        }

        $params = array(
            'log_api_list' => array(),
            'log_api_count' => array(),
            'app_list' => array(),
        );
        $settings = array(
            'log_api_list' => array(),
            'log_api_count' => array(),
            'app_list' => array(
                'index' => 'id',
                'flat' => true
            ),
        );

        $params['log_api_list'] = array_merge($params['log_api_list'], $pagination);

        $list_log_api = api::query('log/api_list', $params['log_api_list'], $settings['log_api_list']);
        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
        $count_log_api = api::query('log/api_count', $params['log_api_count'], $settings['log_api_count']);
        $count = $count_log_api['count'];

        view::load('log_api/index', array(
            'list' => $list_log_api,
            'list_app' => $list_app,
            'page' => $pagination['page'],
            'show' => $pagination['show'],
            'count' => $count,
            'current_app_id' => api::$app_id
        ));
    }

    /**
     * Action Search (log_api/search) - search data on GET parameters.
     * In terminology CRUD is Read.
     * If empty GET params or errors, call Action Index.
     */
    public static function search()
    {
        $pagination = array(
            'page' => 1,
            'show' => 20
        );

        if (isset($_GET['page'])) {
            $pagination['page'] = intval($_GET['page']);
            unset($_GET['page']);
        }

        if (isset($_GET['show'])) {
            $pagination['show'] = intval($_GET['show']);
            unset($_GET['show']);
        }

        if ($_GET) {
            $params = array(
                'log_api_search' => $_GET
            );
            $settings = array(
                'log_api_search' => array()
            );

            $params = array_merge($params['log_api_search'], $pagination);

            $search_log_api = api::query('log_api/search', $params['log_api_search'], $settings['log_api_search']);

            if (f::is_done($search_log_api)) {
                unset($search_log_api['result']);
                view::load('log_api/search', array('list' => $search_log_api));
            } else {
                static::index();
            }
        } else {
            static::index();
        }
    }

    /**
     * Action Add (log_api/add) - saving data from POST parameters.
     * In terminology CRUD is Create.
     */
    public static function add()
    {
        $params = array(
            'log_api_add' => $_POST
        );

        $settings = array(
            'log_api_add' => array()
        );

        if ($_POST) {
            $validation = form::validation(array());

            if ($validation) {
                $add_log_api = api::query('log_api/add', $params['log_api_add'], $settings['log_api_add']);

                if (f::is_done($add_log_api)) {
                    header('Location: /log_api');
                }
            }
        }

        view::load('log_api/form');
    }

    /**
     * Action Edit (log_api/edit) - edit data on ID element.
     * In terminology CRUD is Update.
     */
    public static function edit()
    {
        if (request::$params) {
            $params = array(
                'log_api_info' => array(
                    'id' => request::$params
                ),
                'log_api_edit' => $_POST
            );

            $settings = array(
                'log_api_info' => array(),
                'log_api_edit' => array()
            );

            $info_log_api = api::query('log_api/info', $params['log_api_info'], $settings['log_api_info']);

            if ($_POST) {
                $validation = form::validation(array());

                if ($validation) {
                    if (!empty($info_log_api)) {
                        $params['user_rule_edit'] = $info_log_api['id'];
                    }
                    $edit_log_api = api::query('log_api/edit', $params['log_api_edit'], $settings['log_api_edit']);

                    if (f::is_done($edit_log_api)) {
                        header('Location: /log_api');
                    }
                }
            } else {
                if (!empty($info_log_api)) {
                    $_POST = array_merge($_POST, $info_log_api);
                }
            }

            view::load('log_api/form');
        } else {
            header('Location: /log_api');
        }
    }

    /**
     * Action Delete (log_api/delete) - set row 'del'=1 on ID element.
     * In terminology CRUD is Update.
     */
    public static function delete()
    {
        if (request::$params) {
            $params = array(
                'log_api_delete' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'log_api_delete' => array()
            );

            $delete_log_api = api::query('log_api/delete', $params['log_api_delete'], $settings['log_api_delete']);

            if (f::is_done($delete_log_api)) {
                header('Location: /log_api');
            }
        } else {
            header('Location: /log_api');
        }
    }

    /**
     * Action Restore (log_api/restore) - set row 'del'=0 on ID element.
     * In terminology CRUD is Update.
     */
    public static function restore()
    {
        if (request::$params) {
            $params = array(
                'log_api_restore' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'log_api_restore' => array()
            );

            $restore_log_api = api::query('log_api/restore', $params['log_api_restore'], $settings['log_api_restore']);

            if (f::is_done($restore_log_api)) {
                header('Location: /log_api');
            }
        } else {
            header('Location: /log_api');
        }
    }
}