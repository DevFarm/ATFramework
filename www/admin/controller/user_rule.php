<?php

/**
 * Admin Controller User_rule of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_Api as api;
use classes\base\ATCore_Form as form;
use classes\base\ATCore_String as string;
use classes\base\ATCore_View as view;
use classes\base\ATCore_F as f;

class Controller_User_rule extends Controller_Base
{
    /**
     * Action Index (user_rule/index) - show data list or other.
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
            'user_rule_list' => array(),
            'user_rule_count' => array(),
            'app_list' => array(),
            'controller_list' => array()
        );
        $settings = array(
            'user_rule_list' => array(),
            'user_rule_count' => array(),
            'app_list' => array(
                'index' => 'id',
                'rows' => array(
                    'name',
                    'comment'
                )
            ),
            'controller_list' => array()
        );

        $params['user_rule_list'] = array_merge($params['user_rule_list'], $pagination);

        $list_user_rule = api::query('user_rule/list', $params['user_rule_list'], $settings['user_rule_list']);
        $count_user_rule = api::query('user_rule/count', $params['user_rule_count'], $settings['user_rule_count']);
        $count_user_rule = $count_user_rule['count'];

        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
        $list_controller = api::query('controller/light_list', $params['controller_list'], $settings['controller_list']);

        view::load('user_rule/index', array(
            'list' => $list_user_rule,
            'list_app' => $list_app,
            'list_controller' => $list_controller,
            'page' => $params['user_rule_list']['page'],
            'show' => $params['user_rule_list']['show'],
            'count' => $count_user_rule
        ));
    }

    /**
     * Action Search (user_rule/search) - search data on GET parameters.
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

        $_GET['c-description'] = str_ireplace('полный доступ', '*', (isset($_GET['c-description']) ? $_GET['c-description'] : ''));
        $_GET['ur-action'] = str_ireplace('полный доступ', '*', (isset($_GET['ur-action']) ? $_GET['ur-action'] : ''));

        $_POST = array_merge($_POST, $_GET);

        $params = array(
            'user_rule_search' => $_POST,
            'user_rule_count' => $_POST,
            'app_list' => array(),
            'controller_list' => array()
        );
        $settings = array(
            'user_rule_search' => array(
                'search' => array(
                    'ur-id' => 'strict',
                    'a-name' => 'loose',
                    'c-description' => 'loose',
                    'ac-description' => 'loose'
                )
            ),
            'user_rule_count' => array(
                'search' => array(
                    'ur-id' => 'strict',
                    'a-name' => 'loose',
                    'c-description' => 'loose',
                    'ac-description' => 'loose'
                )
            ),
            'app_list' => array(
                'index' => 'id',
                'rows' => array(
                    'name',
                    'comment'
                )
            ),
            'controller_list' => array()
        );

        $_POST['c-description'] = str_ireplace('*', 'полный доступ', $_POST['c-description']);
        $_POST['ur-action'] = str_ireplace('*', 'полный доступ', $_POST['ur-action']);

        $params['user_rule_search'] = array_merge($params['user_rule_search'], $pagination);

        $search_user_rule = api::query('user_rule/search', $params['user_rule_search'], $settings['user_rule_search']);
        $count_user_rule = api::query('user_rule/count', $params['user_rule_count'], $settings['user_rule_count']);
        $count_user_rule = $count_user_rule['count'];

        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
        $list_controller = api::query('controller/list', $params['controller_list'], $settings['controller_list']);

        view::load('user_rule/index', array(
            'list' => $search_user_rule,
            'list_app' => $list_app,
            'list_controller' => $list_controller,
            'page' => $params['user_rule_search']['page'],
            'show' => $params['user_rule_search']['show'],
            'count' => $count_user_rule
        ));
    }

    /**
     * Action Add (user_rule/add) - saving data from POST parameters.
     * In terminology CRUD is Create.
     */
    public static function add()
    {
        $params = array(
            'user_rule_add' => $_POST,
            'app_list' => array(),
            'controller_light_list' => array()
        );

        $settings = array(
            'user_rule_add' => array(),
            'app_list' => array(
                'index' => 'id',
                'rows' => array('name'),
                'flat' => true
            ),
            'controller_light_list' => array()
        );

        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
        $light_list_controller = api::query('controller/light_list', $params['controller_light_list'], $settings['controller_light_list']);

        if ($_POST) {
            $validation = form::validation(array(
                'app' => array(
                    'value' => (isset($_POST['app']) ? $_POST['app'] : ''),
                    'validation' => array(
                        'empty' => array(
                            'message' => 'вы не выбрали приложение'
                        )
                    )
                ),
                'controller' => array(
                    'value' => (isset($_POST['controller']) ? $_POST['controller'] : ''),
                    'validation' => array(
                        'empty' => array(
                            'message' => 'вы не указали контроллер'
                        )
                    )
                )
            ));

            if (isset($_POST['controller']) && $_POST['controller'] != '*' && empty($_POST['action'])) {
                $validation = false;
                form::error('action', 'вы не указали действие');
            }

            if ($validation) {
                $add_user_rule = api::query('user_rule/add', $params['user_rule_add'], $settings['user_rule_add']);

                if (f::is_done($add_user_rule)) {
                    header('Location: /user_rule');
                }
            }
        } else {
            $_POST['access'] = 1;
        }

        view::load('user_rule/form', array(
            'list_app' => $list_app,
            'light_list_controller' => $light_list_controller
        ));
    }

    /**
     * Action Edit (user_rule/edit) - edit data on ID element.
     * In terminology CRUD is Update.
     */
    public static function edit()
    {
        if (request::$params) {
            $params = array(
                'user_rule_info' => array(
                    'id' => request::$params
                ),
                'user_rule_edit' => $_POST,
                'app_list' => array(),
                'controller_light_list' => array()
            );

            $settings = array(
                'user_rule_info' => array(),
                'user_rule_edit' => array(),
                'app_list' => array(
                    'index' => 'id',
                    'rows' => array('name'),
                    'flat' => true
                ),
                'controller_light_list' => array()
            );

            $info_user_rule = api::query('user_rule/info', $params['user_rule_info'], $settings['user_rule_info']);
            $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
            $light_list_controller = api::query('controller/light_list', $params['controller_light_list'], $settings['controller_light_list']);

            if ($_POST) {
                $validation = form::validation(array(
                    'app' => array(
                        'value' => $_POST['app'],
                        'validation' => array(
                            'empty' => array(
                                'message' => 'вы не выбрали приложение'
                            )
                        )
                    ),
                    'controller' => array(
                        'value' => $_POST['controller'],
                        'validation' => array(
                            'empty' => array(
                                'message' => 'вы не указали контроллер'
                            )
                        )
                    )
                ));

                if ($_POST['controller'] != '*' && empty($_POST['action'])) {
                    $validation = false;
                    form::error('action', 'вы не указали действие');
                }

                if ($validation) {
                    if (!empty($info_user_rule)) {
                        $params['user_rule_edit']['id'] = $info_user_rule['id'];
                    }

                    $edit_user_rule = api::query('user_rule/edit', $params['user_rule_edit'], $settings['user_rule_edit']);

                    if (f::is_done($edit_user_rule)) {
                        header('Location: /user_rule');
                    }
                }
            } else {
                if (!empty($info_user_rule)) {
                    $_POST = array_merge($_POST, $info_user_rule);
                }
            }

            $title = $list_app[$info_user_rule['app']] . ' - ';

            $controller = (isset($info_user_rule['controller']) ? $info_user_rule['controller'] : '');

            if ($controller == '*') {
                $title .= 'полный доступ';
            } else {
                $title .= f::alias_controller($controller, $light_list_controller);
            }

            $action = (isset($info_user_rule['action']) ? $info_user_rule['action'] : '');

            if ($action == '*') {
                $title .= '/полный доступ';
            } elseif (!empty($action)) {
                $title .= '/' . f::alias_action($controller, $action, $light_list_controller);
            }

            view::load('user_rule/form', array(
                'list_app' => $list_app,
                'light_list_controller' => $light_list_controller,
                'title' => $title,
                'app' => $_POST['app'],
                'controller' => $_POST['controller'],
                'action' => $_POST['action']
            ));
        } else {
            header('Location: /user_rule');
        }
    }

    /**
     * Action Delete (user_rule/delete) - set row 'del'=1 on ID element.
     * In terminology CRUD is Update.
     */
    public static function delete()
    {
        if (request::$params) {
            $params = array(
                'user_rule_delete' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'user_rule_delete' => array()
            );

            $delete_user_rule = api::query('user_rule/delete', $params['user_rule_delete'], $settings['user_rule_delete']);

            if (f::is_done($delete_user_rule)) {
                header('Location: /user_rule');
            }
        } else {
            header('Location: /user_rule');
        }
    }

    /**
     * Action Restore (user_rule/restore) - set row 'del'=0 on ID element.
     * In terminology CRUD is Update.
     */
    public static function restore()
    {
        if (request::$params) {
            $params = array(
                'user_rule_restore' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'user_rule_restore' => array()
            );

            $restore_user_rule = api::query('user_rule/restore', $params['user_rule_restore'], $settings['user_rule_restore']);

            if (f::is_done($restore_user_rule)) {
                $url_redirect = (!empty(ATCore::$serv->http_referer) ? ATCore::$serv->http_referer : '/' . request::$controller . '/edit/' . request::$params);
                header('Location: ' . $url_redirect);
            }
        } else {
            header('Location: /user_rule');
        }
    }
}