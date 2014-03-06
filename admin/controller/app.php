<?php

/**
 * Admin Controller App of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 05.10.12
 * @version 2.0
 */
use classes\base\ATCore_Api as api;
use classes\base\ATCore_Form as form;
use classes\base\ATCore_String as string;
use classes\base\ATCore_View as view;
use classes\base\ATCore_F as f;

class Controller_App extends Controller_Base
{
    /**
     * Action Index (app/index) - show data list or other.
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
            'app_list' => array(),
            'app_count' => array()
        );
        $settings = array(
            'app_list' => array(),
            'app_count' => array()
        );

        $params['app_list'] = array_merge($params['app_list'], $pagination);

        $list_app = api::query('app/list', $params['app_list'], $settings['app_list']);
        $count_app = api::query('app/count', $params['app_count'], $settings['app_count']);
        $count_app = $count_app['count'];

        foreach ($list_app as $key => $info) {
            if ($info['last_access'] != '0000-00-00 00:00:00') {
                $list_app[$key]['last_access'] = string::convert_date(array(
                    'date' => $info['last_access'],
                    'date_reverse' => true,
                    'month_str' => true
                ));
            } else {
                $list_app[$key]['last_access'] = 'Не зафиксирован';
            }
        }

        view::load('app/index', array(
            'list' => $list_app,
            'page' => $params['app_list']['page'],
            'show' => $params['app_list']['show'],
            'count' => $count_app
        ));
    }

    /**
     * Action Search (app/search) - search data on GET parameters.
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
            'app_search' => $_GET,
            'app_count' => $_GET
        );
        $settings = array(
            'app_search' => array(
                'search' => array(
                    'id' => 'strict',
                    'name' => 'loose',
                    'api_key' => 'loose'
                )
            ),
            'app_count' => array(
                'search' => array(
                    'id' => 'strict',
                    'name' => 'loose',
                    'api_key' => 'loose'
                )
            )
        );

        $params['app_search'] = array_merge($params['app_search'], $pagination);

        $search_app = api::query('app/search', $params['app_search'], $settings['app_search']);
        $count_app = api::query('app/count', $params['app_count'], $settings['app_count']);
        $count_app = $count_app['count'];

        foreach ($search_app as $key => $info) {
            if ($info['last_access'] != '0000-00-00 00:00:00') {
                $search_app[$key]['last_access'] = string::convert_date(array(
                    'date' => $info['last_access'],
                    'date_reverse' => true,
                    'month_str' => true
                ));
            } else {
                $search_app[$key]['last_access'] = 'Не зафиксирован';
            }
        }

        view::load('app/index', array(
            'list' => $search_app,
            'page' => $params['app_search']['page'],
            'show' => $params['app_search']['show'],
            'count' => $count_app
        ));
    }

    /**
     * Action Add (app/add) - saving data from POST parameters.
     * In terminology CRUD is Create.
     */
    public static function add()
    {
        $params = array(
            'app_add' => $_POST
        );

        $settings = array(
            'app_add' => array()
        );

        if ($_POST) {
            $validation = form::validation(array(
                'name' => array(
                    'value' => $_POST['name'],
                    'validation' => array(
                        'empty' => array(
                            'message' => 'вы не ввели название приложения'
                        ),
                        'maxlength' => array(
                            'data' => 255,
                            'message' => 'вы ввели более 255 символов!'
                        )
                    )
                ),
                'comment' => array(
                    'value' => $_POST['comment'],
                    'validation' => array(
                        'maxlength' => array(
                            'data' => 255,
                            'message' => 'вы ввели более 255 символов!'
                        )
                    )
                ),
                'api_key' => array(
                    'value' => $_POST['api_key'],
                    'validation' => array(
                        'empty' => array(
                            'message' => 'вы не ввели API ключ'
                        ),
                        'minlength' => array(
                            'data' => 25,
                            'message' => 'вы ввели мение 25 символов!'
                        )
                    )
                )
            ));

            if (isset($_POST['new_rule']) && count($_POST['new_rule']) > 0) {
                foreach ($_POST['new_rule'] as $rule) {
                    if (empty($rule)) {
                        form::error('rule', 'все поля прав доступа должны быть заполнены');
                        $validation = false;
                    }
                }
            } else {
                form::error('rule', 'вы должны указать права доступа');
                $validation = false;
            }

            if ($validation) {
                $add_app = api::query('app/add', $params['app_add'], $settings['app_add']);

                if (f::is_done($add_app)) {
                    header('Location: /app');
                    exit;
                }

                switch (f::err_code($add_app)) {
                    case 'api_key exists':
                    {
                        form::error('api_key', f::api_reference($add_app['error']));
                        break;
                    }
                    default:
                        {
                        form::error('other_error', f::api_reference($add_app['error']));
                        break;
                        }
                }
            }
        } else {
            $api_key = api::query('app/generate_api_key', array());

            if (!empty($api_key['api_key'])) {
                $_POST = array_merge($_POST, $api_key);
            }
        }

        $rule_count = 1;

        if (isset($_POST['rule'])) {
            $rule_count = count($_POST['rule']);
        } elseif (isset($_POST['new_rule'])) {
            $rule_count = count($_POST['new_rule']);
        }

        view::load('app/form', array(
            'api_key' => $_POST['api_key'],
            'rule_count' => $rule_count
        ));
    }

    /**
     * Action Edit (app/edit) - edit data on ID element.
     * In terminology CRUD is Update.
     */
    public static function edit()
    {
        if (request::$params) {
            $params = array(
                'app_info' => array(
                    'id' => request::$params
                ),
                'app_edit' => $_POST
            );

            $settings = array(
                'app_info' => array(
                    'join' => true
                ),
                'app_edit' => array()
            );

            if ($_POST) {
                $validation = form::validation(array(
                    'comment' => array(
                        'value' => $_POST['comment'],
                        'validation' => array(
                            'maxlength' => array(
                                'data' => 255,
                                'message' => 'вы ввели более 255 символов!'
                            )
                        )
                    ),
                    'api_key' => array(
                        'value' => $_POST['api_key'],
                        'validation' => array(
                            'empty' => array(
                                'message' => 'вы не ввели API ключ'
                            ),
                            'minlength' => array(
                                'data' => 25,
                                'message' => 'вы ввели мение 25 символов!'
                            )
                        )
                    )
                ));

                if (isset($_POST['rule']) && count($_POST['rule']) > 1) {
                    foreach ($_POST['rule'] as $rule) {
                        if (empty($rule)) {
                            form::error('rule', 'все поля прав доступа должны быть заполнены');
                            $validation = false;
                        }
                    }
                }

                if (isset($_POST['new_rule']) && count($_POST['new_rule']) > 1) {
                    foreach ($_POST['new_rule'] as $rule) {
                        if (empty($rule)) {
                            form::error('rule', 'все поля прав доступа должны быть заполнены');
                            $validation = false;
                        }
                    }
                }

                if ($validation) {
                    $params['app_edit']['id'] = request::$params;

                    if (!empty($_POST['new_rule'])) {
                        $_POST['rule'] += $_POST['new_rule'];
                        unset($_POST['new_rule']);
                    }

                    if (!empty($_POST['new_access'])) {
                        $_POST['access'] += $_POST['new_access'];
                        unset($_POST['new_access']);
                    }

                    //debug::vardump($_POST,'post',1);

                    $edit_app = api::query('app/edit', $params['app_edit'], $settings['app_edit']);

                    if (f::is_done($edit_app)) {
                        header('Location: /app');
                        exit;
                    }

                    switch (f::err_code($edit_app)) {
                        case 'api_key exists':
                        {
                            form::error('api_key', f::api_reference($edit_app['error']));
                            break;
                        }
                        default:
                            {
                            form::error('other_error', f::api_reference($edit_app['error']));
                            break;
                            }
                    }
                }
            } else {
                $info_app = api::query('app/info', $params['app_info'], $settings['app_info']);

                if (!empty($info_app)) {
                    $_POST = array_merge($_POST, $info_app);
                }
            }
        } else {
            header('Location: /app');
            exit;
        }

        $rule_count = 1;

        if (isset($_POST['rule'])) {
            $rule_count = count($_POST['rule']);
        } elseif (isset($_POST['new_rule'])) {
            $rule_count = count($_POST['new_rule']);
        }

        view::load('app/form', array('rule_count' => $rule_count));
    }

    /**
     * Action Delete (app/delete) - set row 'del'=1 on ID element.
     * In terminology CRUD is Update.
     */
    public static function delete()
    {
        if (request::$params) {
            $params = array(
                'app_delete' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'app_delete' => array()
            );

            $delete_app = api::query('app/delete', $params['app_delete'], $settings['app_delete']);

            if (f::is_done($delete_app)) {
                header('Location: /app');
            }
        } else {
            header('Location: /app');
        }
    }

    /**
     * Action Restore (app/restore) - set row 'del'=0 on ID element.
     * In terminology CRUD is Update.
     */
    public static function restore()
    {
        if (request::$params) {
            $params = array(
                'app_restore' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'app_restore' => array()
            );

            $restore_app = api::query('app/restore', $params['app_restore'], $settings['app_restore']);

            if (f::is_done($restore_app)) {
                $url_redirect = (!empty(ATCore::$serv->http_referer) ? ATCore::$serv->http_referer : '/' . request::$controller . '/edit/' . request::$params);
                header('Location: ' . $url_redirect);
            }
        } else {
            header('Location: /app');
        }
    }
}