<?php

/**
 * Admin Controller User_role of ATFramework v2.0
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

class Controller_User_role extends Controller_Base
{
    /**
     * Action Index (user_role/index) - show data list or other.
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
            'user_role_list' => array()
        );
        $settings = array(
            'user_role_list' => array()
        );

        $params['user_role_list'] = array_merge($params['user_role_list'], $pagination);

        $list_user_role = api::query('user_role/list', $params['user_role_list'], $settings['user_role_list']);

        view::load('user_role/index', array('list' => $list_user_role));
    }

    /**
     * Action Search (user_role/search) - search data on GET parameters.
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
                'user_role_search' => $_GET
            );
            $settings = array(
                'user_role_search' => array()
            );

            $params = array_merge($params['user_role_search'], $pagination);

            $search_user_role = api::query('user_role/search', $params['user_role_search'], $settings['user_role_search']);

            if (f::is_done($search_user_role)) {
                unset($search_user_role['result']);
                view::load('user_role/search', array('list' => $search_user_role));
            } else {
                static::index();
            }
        } else {
            static::index();
        }
    }

    /**
     * Action Add (user_role/add) - saving data from POST parameters.
     * In terminology CRUD is Create.
     */
    public static function add()
    {
        $params = array(
            'user_role_add' => $_POST,
            'user_rule_list' => array(),
            'app_list' => array(),
            'section_list' => array()
        );

        $settings = array(
            'user_role_add' => array(),
            'user_rule_list' => array(
                'index' => 'id'
            ),
            'app_list' => array(
                'index' => 'id',
                'rows' => array('name'),
                'flat' => true
            ),
            'section_list' => array()
        );

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
                'description' => array(
                    'value' => $_POST['description'],
                    'validation' => array(
                        'empty' => array(
                            'data' => false,
                            'message' => 'вы не указали описание должности'
                        )
                    )
                )
            ));

            if (empty($_POST['full']) && empty($_POST['rule'])) {
                $validation = false;
                form::error('rule', 'вы не выбрали правила доступа');
            }

            if ($validation) {
                $add_user_role = api::query('user_role/add', $params['user_role_add'], $settings['user_role_add']);

                if (f::is_done($add_user_role)) {
                    $add_user_role_rule = api::query('user_role_rule/set', array(
                        'role' => $add_user_role['id'],
                        'rule' => $_POST['rule'],
                        'full' => $_POST['full']
                    ));

                    if (f::is_done($add_user_role_rule)) {
                        header('Location: /user_role');
                    }
                }
            }
        }

        $list_user_rule = api::query('user_rule/list', $params['user_rule_list'], $settings['user_rule_list']);
        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
        $list_section = api::query('section/list', $params['section_list'], $settings['section_list']);

        $list_user_role_rule = array();

        if (!empty($_POST['rule'])) {
            foreach ($_POST['rule'] as $rule) {
                $list_user_role_rule[$rule] = array(
                    'app_id' => $list_user_rule[$rule]['app'],
                    'app_name' => $list_app[$list_user_rule[$rule]['app']],
                    'access' => $list_user_rule[$rule]['access'],
                    'controller' => $list_user_rule[$rule]['controller'],
                    'action' => $list_user_rule[$rule]['action']
                );
            }

            $_POST['rule'] = $list_user_role_rule;
        }

        $mod_list_user_rule = array();

        foreach ($list_user_rule as $id => $rule) {
            $mod_list_user_rule[$rule['app']]['name'] = $list_app[$rule['app']];

            if (!isset($list_user_role_rule[$id])) {
                $controller = f::alias_controller($rule['controller'], $list_section);
                $action = f::alias_action($rule['controller'], $rule['action'], $list_section);

                $mod_list_user_rule[$rule['app']]['rules'][$id] = array(
                    'controller' => $controller,
                    'action' => $action,
                    'access' => $rule['access'],
                );
            }
        }

        $list_user_rule = $mod_list_user_rule;

        view::load('user_role/form', array(
            'list_user_rule' => $list_user_rule,
            'list_app' => $list_app,
            'list_section' => $list_section
        ));
    }

    /**
     * Action Edit (user_role/edit) - edit data on ID element.
     * In terminology CRUD is Update.
     */
    public static function edit()
    {
        if (request::$params) {
            $params = array(
                'user_role_info' => array(
                    'id' => request::$params
                ),
                'user_role_edit' => $_POST,
                'user_rule_list' => array(),
                'app_list' => array(),
                'section_list' => array()
            );

            $settings = array(
                'user_role_info' => array(
                    'join' => true
                ),
                'user_role_edit' => array(),
                'user_rule_list' => array(
                    'index' => 'id'
                ),
                'app_list' => array(
                    'index' => 'id',
                    'rows' => array('name'),
                    'flat' => true
                ),
                'section_list' => array()
            );

            $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
            $info_user_role = api::query('user_role/info', $params['user_role_info'], $settings['user_role_info']);
            $list_user_rule = api::query('user_rule/list', $params['user_rule_list'], $settings['user_rule_list']);
            $list_section = api::query('section/list', $params['section_list'], $settings['section_list']);

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
                    'description' => array(
                        'value' => $_POST['description'],
                        'validation' => array(
                            'empty' => array(
                                'data' => false,
                                'message' => 'вы не указали описание должности'
                            )
                        )
                    )
                ));

                if (empty($_POST['full']) && empty($_POST['rule'])) {
                    $validation = false;
                    form::error('rule', 'вы не выбрали правила доступа');
                }

                if ($validation) {
                    if (!empty($info_user_role)) {
                        $params['user_role_edit']['id'] = $info_user_role['id'];
                    }

                    $edit_user_role = api::query('user_role/edit', $params['user_role_edit'], $settings['user_role_edit']);

                    if (f::is_done($edit_user_role)) {
                        $set_user_role_rule = api::query('user_role_rule/set', array(
                            'role' => request::$params,
                            'rule' => $_POST['rule'],
                            'full' => $_POST['full']
                        ));

                        if (f::is_done($set_user_role_rule)) {
                            header('Location: /user_role');
                        }
                    }
                }

                $info_user_role['rule'] = $_POST['rule'];
            } else {
                $_POST = array_merge($_POST, $info_user_role);
            }

            if (!empty($info_user_role['rule'])) {
                foreach ($info_user_role['rule'] as $rule) {
                    unset($info_user_role['rule']);

                    if (!empty($list_user_rule[$rule])) {
                        $info_user_role['rules'][$list_user_rule[$rule]['app']][$rule] = $list_user_rule[$rule];
                    }
                }
            }

            foreach ($list_user_rule as $key => $rule) {
                $mod_list_user_rule[$rule['app']]['name'] = $list_app[$rule['app']];

                if (!isset($info_user_role['rules'][$rule['app']][$key])) {
                    $controller = f::alias_controller($rule['controller'], $list_section);
                    $action = f::alias_action($rule['controller'], $rule['action'], $list_section);

                    $mod_list_user_rule[$rule['app']]['rules'][$key] = array(
                        'controller' => $controller,
                        'action' => $action,
                        'access' => $rule['access'],
                    );
                } else {
                    $info_user_role['rule'][$key] = array(
                        'app_id' => $rule['app'],
                        'app_name' => $list_app[$rule['app']],
                        'access' => $rule['access'],
                        'controller' => $rule['controller'],
                        'action' => $rule['action']
                    );
                }
            }

            $list_user_rule = $mod_list_user_rule;

            if (!empty($info_user_role['rule'])) {
                $_POST['rule'] = $info_user_role['rule'];
            } else {
                unset($_POST['rule']);
            }

            view::load('user_role/form', array(
                'list_user_rule' => $list_user_rule,
                'list_app' => $list_app,
                'list_section' => $list_section
            ));
        } else {
            header('Location: /user_role');
        }
    }

    /**
     * Action Delete (user_role/delete) - set row 'del'=1 on ID element.
     * In terminology CRUD is Update.
     */
    public static function delete()
    {
        if (request::$params) {
            $params = array(
                'user_role_delete' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'user_role_delete' => array()
            );

            $delete_user_role = api::query('user_role/delete', $params['user_role_delete'], $settings['user_role_delete']);

            if (f::is_done($delete_user_role)) {
                header('Location: /user_role');
            }
        } else {
            header('Location: /user_role');
        }
    }

    /**
     * Action Restore (user_role/restore) - set row 'del'=0 on ID element.
     * In terminology CRUD is Update.
     */
    public static function restore()
    {
        if (request::$params) {
            $params = array(
                'user_role_restore' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'user_role_restore' => array()
            );

            $restore_user_role = api::query('user_role/restore', $params['user_role_restore'], $settings['user_role_restore']);

            if (f::is_done($restore_user_role)) {
                $url_redirect = (!empty(ATCore::$serv->http_referer) ? ATCore::$serv->http_referer : '/' . request::$controller . '/edit/' . request::$params);
                header('Location: ' . $url_redirect);
            }
        } else {
            header('Location: /user_role');
        }
    }
}