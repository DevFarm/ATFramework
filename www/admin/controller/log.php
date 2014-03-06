<?php

/**
 * Admin Controller Log of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 27.10.12
 * @version 2.0
 */
use classes\base\ATCore_Api as api;
use classes\base\ATCore_View as view;
use classes\base\ATCore_F as f;
use classes\base\ATCore_Form as form;

class Controller_Log extends Controller_Base
{
    /**
     * Action Index (log/index) - show data list or other.
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
            'log_list' => array(),
            'log_count' => array(),
        );
        $settings = array(
            'log_list' => array(),
            'log_count' => array(),
        );

        $params['log_list'] = array_merge($params['log_list'], $pagination);

        $list_log = api::query('log/list', $params['log_list'], $settings['log_list']);
        $count_log = api::query('log/count', $params['log_count'], $settings['log_count']);
        $count_log = $count_log['count'];

        view::load('log/index', array(
            'list' => $list_log,
            'page' => $params['log_list']['page'],
            'show' => $params['log_list']['show'],
            'count' => $count_log
        ));
    }

    /**
     * Action Search (log/search) - search data on GET parameters.
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
                'log_search' => $_GET
            );
            $settings = array(
                'log_search' => array()
            );

            $params['log_search'] = array_merge($params['log_search'], $pagination);

            $search_log = api::query('log/search', $params['log_search'], $settings['log_search']);

            if (f::is_done($search_log)) {
                unset($search_log['result']);
                view::load('log/search', array('list' => $search_log));
            } else {
                static::index();
            }
        } else {
            static::index();
        }
    }

    /**
     * Action Add (log/add) - saving data from POST parameters.
     * In terminology CRUD is Create.
     */
    public static function add()
    {
        $params = array(
            'log_add' => $_POST
        );

        $settings = array(
            'log_add' => array()
        );

        if ($_POST) {
            $validation = form::validation(array());

            if ($validation) {
                $add_log = api::query('log/add', $params['log_add'], $settings['log_add']);

                if (f::is_done($add_log)) {
                    header('Location: /log');
                }
            }
        }

        view::load('log/form');
    }

    /**
     * Action Edit (log/edit) - edit data on ID element.
     * In terminology CRUD is Update.
     */
    public static function edit()
    {
        if (request::$params) {
            $params = array(
                'log_info' => array(
                    'id' => request::$params
                ),
                'log_edit' => $_POST
            );

            $settings = array(
                'log_info' => array(),
                'log_edit' => array()
            );

            $info_log = api::query('log/info', $params['log_info'], $settings['log_info']);

            if ($_POST) {
                $validation = form::validation(array());

                if ($validation) {
                    if (!empty($info_log)) {
                        $params['user_rule_edit'] = $info_log['id'];
                    }
                    $edit_log = api::query('log/edit', $params['log_edit'], $settings['log_edit']);

                    if (f::is_done($edit_log)) {
                        header('Location: /log');
                    }
                }
            } else {
                if (!empty($info_log)) {
                    $_POST = array_merge($_POST, $info_log);
                }
            }

            view::load('log/form');
        } else {
            header('Location: /log');
        }
    }

    /**
     * Action Delete (log/delete) - set row 'del'=1 on ID element.
     * In terminology CRUD is Update.
     */
    public static function delete()
    {
        if (request::$params) {
            $params = array(
                'log_delete' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'log_delete' => array()
            );

            $delete_log = api::query('log/delete', $params['log_delete'], $settings['log_delete']);

            if (f::is_done($delete_log)) {
                header('Location: /log');
            }
        } else {
            header('Location: /log');
        }
    }

    /**
     * Action Restore (log/restore) - set row 'del'=0 on ID element.
     * In terminology CRUD is Update.
     */
    public static function restore()
    {
        if (request::$params) {
            $params = array(
                'log_restore' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'log_restore' => array()
            );

            $restore_log = api::query('log/restore', $params['log_restore'], $settings['log_restore']);

            if (f::is_done($restore_log)) {
                header('Location: /log');
            }
        } else {
            header('Location: /log');
        }
    }
}