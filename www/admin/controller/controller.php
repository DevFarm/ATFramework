<?php

/**
 * Admin Controller Controller of ATFramework v2.0
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

class Controller_Controller extends Controller_Base
{
    /**
     * Action Index (controller/index) - show data list or other.
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
            'controller_list' => array(),
            'app_list' => array()
        );
        $settings = array(
            'controller_list' => array(),
            'app_list' => array(
                'index' => 'id',
                'flat' => true
            )
        );

        $params['controller_list'] = array_merge($params['controller_list'], $pagination);

        $list_controller = api::query('controller/list', $params['controller_list'], $settings['controller_list']);
        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);

        view::load('controller/index', array(
            'list' => $list_controller,
            'list_app' => $list_app
        ));
    }

    /**
     * Action Search (controller/search) - search data on GET parameters.
     * In terminology CRUD is Read.
     * If empty GET params or errors, call Action Index.
     */
    public static function search()
    {
        $pagination = array(
            'page' => 1,
            'show' => 100
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
        $_POST['ca-app'] = request::$params;

        $params = array(
            'controller_search' => $_POST,
            'app_info' => array(
                'id' => intval(request::$params)
            )
        );
        $settings = array(
            'controller_search' => array(
                'search' => array(
                    'c-name' => 'loose'
                )
            ),
            'app_info' => array()
        );

        $params['controller_search'] = array_merge($params['controller_search'], $pagination);

        $search_controller = api::query('controller/search', $params['controller_search'], $settings['controller_search']);
        $info_app = api::query('app/info', $params['app_info'], $settings['app_info']);
        $count_controller = api::query('controller/count');
        $count_controller = $count_controller['count'];

        unset($search_controller['result']);
        view::load('controller/show', [
            'list' => $search_controller,
            'info_app' => $info_app,
            'page' => $pagination['page'],
            'show' => $pagination['show'],
            'count' => $count_controller
        ]);
    }

    /**
     * Action Show (controller/show).
     */
    public static function show()
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
            'controller_search' => array(
                'ca-app' => request::$params
            ),
            'app_info' => array(
                'id' => request::$params
            )
        );
        $settings = array(
            'controller_search' => array(),
            'app_info' => array()
        );

        $list_controller = api::query('controller/search', $params['controller_search'], $settings['controller_search']);
        $info_app = api::query('app/info', $params['app_info'], $settings['app_info']);

        view::load('controller/show', array(
            'list' => $list_controller,
            'info_app' => $info_app
        ));
    }

    /**
     * Action Add (controller/add) - saving data from POST parameters.
     * In terminology CRUD is Create.
     */
    public static function add()
    {
        $params = array(
            'controller_add' => $_POST,
            'app_list' => [],
            'action_list' => []
        );

        $settings = array(
            'controller_add' => [],
            'app_list' => [
                'index' => 'id',
                'rows' => ['name'],
                'flat' => true
            ],
            'action_list' => [
                'index' => 'id'
            ]
        );

        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
        $list_action = api::query('action/list', $params['action_list'], $settings['action_list']);

        if ($_POST) {
            $validation = form::validation(array(
                'name' => [
                    'value' => $_POST['name'],
                    'validation' => [
                        'empty' => array(
                            'data' => false,
                            'message' => 'вы не указали название должности'
                        ),
                        'regexp' => array(
                            'data' => '/^[a-z0-9_]+$/Uis',
                            'message' => 'допускаются только латинские буквы, цифры и символ &laquo;_&raquo;'
                        )
                    ]
                ],
                'app' => [
                    'value' => $_POST['app'],
                    'validation' => [
                        'empty' => [
                            'data' => false,
                            'message' => 'выберите приложение'
                        ]
                    ]
                ]
            ));

            if (empty($_POST['actions'])) {
                $validation = false;
                form::error('actions', 'контроллер должен иметь хотя бы одно действие');
            }

            if ($validation) {
                $add_controller = api::query('controller/add', $params['controller_add'], $settings['controller_add']);

                if (f::is_done($add_controller)) {
                    header('Location: /controller/show/' . request::$params);
                }
            }

            if (!empty($_POST['actions'])) {
                $actions = $_POST['actions'];
                unset($_POST['actions']);

                foreach ($actions as $action) {
                    if(!isset($_POST['actions'][$action])) {
                        $_POST['actions'][$action] = [
                            'id' => $action,
                            'name' => $list_action[$action]['name'],
                            'description' => $list_action[$action]['description']
                        ];
                    } else {

                    }

                    unset($list_action[$action]);
                }
            }
        }

        view::load('controller/form', [
            'list_app' => $list_app,
            'list_action' => $list_action
        ]);
    }

    /**
     * Action Edit (controller/edit) - edit data on ID element.
     * In terminology CRUD is Update.
     */
    public static function edit()
    {
        if (request::$params) {
            $params = array(
                'controller_info' => [
                    'id' => request::$params
                ],
                'controller_edit' => $_POST,
                'app_list' => [],
                'action_list' => []
            );

            $settings = [
                'controller_info' => [],
                'controller_edit' => [],
                'app_list' => [
                    'index' => 'id',
                    'rows' => ['name'],
                    'flat' => true
                ],
                'action_list' => [
                    'index' => 'id'
                ]
            ];

            $info_controller = api::query('controller/info', $params['controller_info'], $settings['controller_info']);
            $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
            $list_action = api::query('action/list', $params['action_list'], $settings['action_list']);

            if ($_POST) {
                $validation = form::validation(array(
                    'name' => array(
                        'value' => $_POST['name'],
                        'validation' => array(
                            'empty' => array(
                                'data' => false,
                                'message' => 'вы не указали название должности'
                            ),
                            'regexp' => array(
                                'data' => '/^[a-z0-9_]+$/Uis',
                                'message' => 'допускаются только латинские буквы, цифры и символ &laquo;_&raquo;'
                            )
                        )
                    ),
                    'app' => [
                        'value' => $_POST['app'],
                        'validation' => [
                            'empty' => [
                                'data' => false,
                                'message' => 'выберите приложение'
                            ]
                        ]
                    ]
                ));

                if (empty($_POST['actions'])) {
                    $validation = false;
                    form::error('actions', 'контроллер должен иметь хотя бы одно действие');
                }

                if ($validation) {
                    if (!empty($info_controller)) {
                        $params['controller_edit']['id'] = $info_controller['id'];
                    }

                    $edit_controller = api::query('controller/edit', $params['controller_edit'], $settings['controller_edit']);

                    if (f::is_done($edit_controller)) {
                        header('Location: /controller/show/' . $info_controller['app']);
                    }
                }

                if (!empty($_POST['actions'])) {
                    $actions = $_POST['actions'];
                    unset($_POST['actions']);

                    foreach ($actions as $action) {
                        if(isset($_POST['actions'][$action])) {
                            $_POST['actions'][$action] = [
                                'id' => $action,
                                'name' => $list_action[$action]['name'],
                                'description' => $list_action[$action]['description']
                            ];
                        }

                        unset($list_action[$action]);
                    }
                }
            } else {
                if (!empty($info_controller)) {
                    $_POST = array_merge($_POST, $info_controller);
                }

                if (!empty($info_controller['actions'])) {
                    foreach ($info_controller['actions'] as $id => $action) {
                        unset($list_action[$id]);
                    }
                }
            }

            view::load('controller/form', [
                'info_controller' => $info_controller,
                'list_app' => $list_app,
                'list_action' => $list_action
            ]);
        } else {
            header('Location: /controller');
        }
    }

    /**
     * Action Delete (controller/delete) - set row 'del'=1 on ID element.
     * In terminology CRUD is Update.
     */
    public static function delete()
    {
        if (request::$params) {
            $params = array(
                'controller_delete' => array(
                    'id' => request::$params
                ),
                'controller_info' => [
                    'id' => request::$params
                ]
            );

            $settings = array(
                'controller_delete' => array(),
                'controller_info' => []
            );

            $delete_controller = api::query('controller/delete', $params['controller_delete'], $settings['controller_delete']);
            $info_controller = api::query('controller/info', $params['controller_info'], $settings['controller_info']);

            if (f::is_done($delete_controller)) {
                header('Location: /controller/show/' . $info_controller['app']);
            }
        } else {
            header('Location: /controller');
        }
    }

    /**
     * Action Restore (controller/restore) - set row 'del'=0 on ID element.
     * In terminology CRUD is Update.
     */
    public static function restore()
    {
        if (request::$params) {
            $params = array(
                'controller_restore' => array(
                    'id' => request::$params
                ),
                'controller_info' => [
                    'id' => request::$params
                ]
            );

            $settings = array(
                'controller_restore' => array(),
                'controller_info' => array()
            );

            $restore_controller = api::query('controller/restore', $params['controller_restore'], $settings['controller_restore']);
            $info_controller = api::query('controller/info', $params['controller_info'], $settings['controller_info']);

            if (f::is_done($restore_controller)) {
                header('Location: /controller/show/' . $info_controller['app']);
            }
        } else {
            header('Location: /controller');
        }
    }
}