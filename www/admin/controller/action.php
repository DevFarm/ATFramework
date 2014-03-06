<?php

/**
 * Admin Controller Action of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 20.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_Api as api;
use classes\base\ATCore_Form as form;
use classes\base\ATCore_String as string;
use classes\base\ATCore_View as view;
use classes\base\ATCore_F as f;

class Controller_Action extends Controller_Base
{
    /**
     * Action Index (action/index) - show data list or other.
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
            'action_list' => array(),
            'app_list' => array()
        );
        $settings = array(
            'action_list' => array(),
            'app_list' => array(
                'index' => 'id',
                'flat' => true
            )
        );

        $params['action_list'] = array_merge($params['action_list'], $pagination);

        $list_action = api::query('action/list', $params['action_list'], $settings['action_list']);
        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);

        view::load('action/index', array(
            'list' => $list_action,
            'list_app' => $list_app
        ));
    }

    /**
     * Action Search (action/search) - search data on GET parameters.
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

        $_POST = $_GET;

        $params = array(
            'action_search' => $_POST
        );
        $settings = array(
            'action_search' => array(
                'search' => [
                    'name' => 'loose',
                    'description' => 'loose'
                ]
            )
        );

        $params['action_search'] = array_merge($params['action_search'], $pagination);

        $search_action = api::query('action/search', $params['action_search'], $settings['action_search']);

        unset($search_action['result']);
        view::load('action/index', array('list' => $search_action));
    }

    /**
     * Action Add (action/add) - saving data from POST parameters.
     * In terminology CRUD is Create.
     */
    public static function add()
    {
        $params = array(
            'action_add' => $_POST
        );

        $settings = array(
            'action_add' => array()
        );

        if ($_POST) {
            $validation = form::validation(array(
                'name' => array(
                    'value' => $_POST['name'],
                    'validation' => array(
                        'empty' => array(
                            'data' => false,
                            'message' => 'вы не указали название'
                        ),
                        'regexp' => array(
                            'data' => '/^[a-z0-9_]+$/Uis',
                            'message' => 'допускаются только латинские буквы, цифры и символ &laquo;_&raquo;'
                        )
                    )
                )
            ));

            if ($validation) {
                $add_action = api::query('action/add', $params['action_add'], $settings['action_add']);

                if (f::is_done($add_action)) {
                    header('Location: /action');
                }
            }
        }

        view::load('action/form');
    }

    /**
     * Action Edit (action/edit) - edit data on ID element.
     * In terminology CRUD is Update.
     */
    public static function edit()
    {
        if (request::$params) {
            $params = array(
                'action_info' => [
                    'id' => request::$params
                ],
                'action_edit' => $_POST
            );

            $settings = [
                'action_info' => [],
                'action_edit' => []
            ];

            $info_action = api::query('action/info', $params['action_info'], $settings['action_info']);

            if ($_POST) {
                $validation = form::validation(array(
                    'name' => array(
                        'value' => $_POST['name'],
                        'validation' => array(
                            'empty' => array(
                                'data' => false,
                                'message' => 'вы не указали название'
                            ),
                            'regexp' => array(
                                'data' => '/^[a-z0-9_]+$/Uis',
                                'message' => 'допускаются только латинские буквы, цифры и символ &laquo;_&raquo;'
                            )
                        )
                    )
                ));

                if ($validation) {
                    if (!empty($info_action)) {
                        $params['action_edit']['id'] = $info_action['id'];
                    }

                    $edit_action = api::query('action/edit', $params['action_edit'], $settings['action_edit']);

                    if (f::is_done($edit_action)) {
                        header('Location: /action');
                    }
                }
            } else {
                if (!empty($info_action)) {
                    $_POST = array_merge($_POST, $info_action);
                }
            }

            view::load('action/form', [
                'info_action' => $info_action
            ]);
        } else {
            header('Location: /action');
        }
    }

    /**
     * Action Delete (action/delete) - set row 'del'=1 on ID element.
     * In terminology CRUD is Update.
     */
    public static function delete()
    {
        if (request::$params) {
            $params = array(
                'action_delete' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'action_delete' => array()
            );

            $delete_action = api::query('action/delete', $params['action_delete'], $settings['action_delete']);

            if (f::is_done($delete_action)) {
                header('Location: /action');
            }
        } else {
            header('Location: /action');
        }
    }

    /**
     * Action Restore (action/restore) - set row 'del'=0 on ID element.
     * In terminology CRUD is Update.
     */
    public static function restore()
    {
        if (request::$params) {
            $params = array(
                'action_restore' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'action_restore' => array()
            );

            $restore_action = api::query('action/restore', $params['action_restore'], $settings['action_restore']);

            if (f::is_done($restore_action)) {
                header('Location: /action');
            }
        } else {
            header('Location: /action');
        }
    }
}