<?php

use classes\base\ATCore_Api as api;
use classes\base\ATCore_Form as form;
use classes\base\ATCore_String as string;
use classes\base\ATCore_View as view;
use classes\base\ATCore_F as f;

class Controller_User extends Controller_Base
{
    public static function index()
    {
        $page = 1;
        $show = 20;

        if (isset($_GET['page'])) {
            $page = intval($_GET['page']);
        }
        if (isset($_GET['show'])) {
            $show = intval($_GET['show']);
        }

        $params = array(
            'user_list' => array(
                'page' => $page,
                'show' => $show
            ),
            'user_role_list' => array()
        );

        $settings = array(
            'user_list' => array(),
            'user_role_list' => array(
                'index' => 'id',
                'rows' => array('description')
            )
        );

        $user_list = api::query('user/list', $params['user_list'], $settings['user_list']);
        $user_count = api::query('user/count');
        $user_count = $user_count['count'];

        $user_role_list = api::query('user_role/list', $params['user_role_list'], $settings['user_role_list']);

        view::load('user/index', array(
            'list' => $user_list,
            'user_role_list' => $user_role_list,
            'page' => $page,
            'show' => $show,
            'count' => $user_count
        ));
    }

    public static function search()
    {
        $page = 1;
        $show = 20;

        if (isset($_GET['page'])) {
            $page = intval($_GET['page']);
        }
        if (isset($_GET['show'])) {
            $show = intval($_GET['show']);
        }

        $_POST = $_GET;

        $params = array(
            'user_search' => $_POST,
            'user_role_list' => array()
        );

        $settings = array(
            'user_search' => array(
                'search' => array(
                    'name' => 'loose',
                    'surname' => 'loose',
                    'middle_name' => 'loose',
                )
            ),
            'user_role_list' => array(
                'index' => 'id'
            )
        );
        $user_search = api::query('user/search', $params['user_search'], $settings['user_search']);
        $user_role_list = api::query('user_role/list', $params['user_role_list'], $settings['user_role_list']);
        $user_count = api::query('user/count');
        $user_count = $user_count['count'];

        unset($user_search['result']);
        view::load('user/index', array(
            'list' => $user_search,
            'user_role_list' => $user_role_list,
            'page' => $page,
            'show' => $show,
            'count' => $user_count
        ));
    }

    public static function add()
    {
        $params = array(
            'user_role_list' => array()
        );

        $settings = array(
            'user_role_list' => array(
                'index' => 'id',
                'rows' => array('description'),
                'flat' => true
            )
        );
        $user_role_list = api::query('user_role/list', $params['user_role_list'], $settings['user_role_list']);

        if ($_POST) {
            $validation = form::validation(array(
                'email' => array(
                    'value' => trim($_POST['email']),
                    'validation' => array(
                        'empty' => array(
                            'data' => false,
                            'message' => 'вы не указали email адрес!'
                        ),
                        'regexp' => array(
                            'data' => '/^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/i',
                            'message' => 'вы ввели не корректный email адрес!'
                        )
                    )
                ),
                'name' => array(
                    'value' => trim($_POST['name']),
                    'validation' => array(
                        'empty' => array(
                            'data' => false,
                            'message' => 'вы не указали имя пользователя!'
                        ),
                        'regexp' => array(
                            'data' => '/^([\D])*$/si',
                            'message' => 'вы ввели не корректное имя пользователя!'
                        )
                    )
                ),
                'surname' => array(
                    'value' => trim($_POST['surname']),
                    'validation' => array(
                        'empty' => array(
                            'data' => false,
                            'message' => 'вы не указали фамилию пользователя!'
                        ),
                        'regexp' => array(
                            'data' => '/^([\D])*$/si',
                            'message' => 'вы ввели не корректную фамилию пользователя!'
                        )
                    )
                ),
                'password' => array(
                    'value' => $_POST['password'],
                    'validation' => array(
                        'empty' => array(
                            'data' => false,
                            'message' => 'пароль не должен быть пустым!'
                        ),
                        'regexp' => array(
                            'data' => '/^[a-zA-Z0-9]+$/',
                            'message' => 'пароль должен содержать буквы и цифры!'
                        ),
                        'minlength' => array(
                            'data' => 6,
                            'message' => 'пароль не должен быть менее 6ти символов!'
                        )
                    )
                ),
                'repassword' => array(
                    'value' => $_POST['repassword'],
                    'validation' => array(
                        'match' => array(
                            'data' => $_POST['password'],
                            'message' => 'пароли не совпадают!'
                        )
                    )
                )
            ));

            if ($validation) {
                $_POST['password'] = md5(auth::SALT . $_POST['password']);
                unset($_POST['repassword']);

                $add = api::query('user/add', $_POST);

                if (f::is_done($add)) {
                    header('Location: /user');
                }
            }
        }

        view::load('user/form', array('user_role_list' => $user_role_list));
    }

    public static function edit()
    {
        if (request::$params) {
            $params = array(
                'user_info' => array(
                    'id' => request::$params
                ),
                'user_role_list' => array()
            );

            $settings = array(
                'user_info' => array(),
                'user_role_list' => array(
                    'index' => 'id',
                    'rows' => array('description'),
                    'flat' => true
                )
            );
            $user_info = api::query('user/info', $params['user_info'], $settings['user_info']);
            $user_role_list = api::query('user_role/list', $params['user_role_list'], $settings['user_role_list']);

            if ($_POST) {
                $validation = form::validation(array(
                    'email' => array(
                        'value' => trim($_POST['email']),
                        'validation' => array(
                            'empty' => array(
                                'data' => false,
                                'message' => 'вы не указали email адрес!'
                            ),
                            'regexp' => array(
                                'data' => '/^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/i',
                                'message' => 'вы ввели не корректный email адрес!'
                            )
                        )
                    ),
                    'name' => array(
                        'value' => trim($_POST['name']),
                        'validation' => array(
                            'empty' => array(
                                'data' => false,
                                'message' => 'вы не указали имя пользователя!'
                            ),
                            'regexp' => array(
                                'data' => '/^([\D])*$/si',
                                'message' => 'вы ввели не корректное имя пользователя!'
                            )
                        )
                    ),
                    'surname' => array(
                        'value' => trim($_POST['surname']),
                        'validation' => array(
                            'empty' => array(
                                'data' => false,
                                'message' => 'вы не указали фамилию пользователя!'
                            ),
                            'regexp' => array(
                                'data' => '/^([\D])*$/si',
                                'message' => 'вы ввели не корректную фамилию пользователя!'
                            )
                        )
                    )
                ));

                if ($validation) {
                    $_POST['id'] = $user_info['id'];

                    if (!empty($_POST['password'])) {
                        $validation = form::validation(array(
                            'password' => array(
                                'value' => $_POST['password'],
                                'validation' => array(
                                    'empty' => array(
                                        'data' => false,
                                        'message' => 'пароль не должен быть пустым!'
                                    ),
                                    'regexp' => array(
                                        'data' => '/^[a-zA-Z0-9]+$/',
                                        'message' => 'пароль должен содержать буквы и цифры!'
                                    ),
                                    'minlength' => array(
                                        'data' => 6,
                                        'message' => 'пароль не должен быть менее 6ти символов!'
                                    )
                                )
                            ),
                            'repassword' => array(
                                'value' => $_POST['repassword'],
                                'validation' => array(
                                    'match' => array(
                                        'data' => $_POST['password'],
                                        'message' => 'пароли не совпадают!'
                                    )
                                )
                            )
                        ));
                    }

                    if ($validation) {
                        $data = $_POST;

                        if (!empty($data['password'])) {
                            $data['password'] = md5(auth::SALT . $data['password']);
                        }

                        $edit = api::query('user/edit', $data);

                        if (f::is_done($edit)) {
                            header('Location: /user');
                        }
                    }
                }
            } else {
                $_POST = array_merge($_POST, $user_info);
                unset($_POST['password']);
            }

            view::load('user/form', array('user_role_list' => $user_role_list));
        } else {
            header('Location: /user');
        }
    }

    public static function delete()
    {
        if (request::$params) {
            $params = array(
                'user_delete' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'user_delete' => array()
            );

            $delete = api::query('user/delete', $params['user_delete'], $settings['user_delete']);
            if (f::is_done($delete)) {
                header('Location: /user');
            }
        } else {
            header('Location: /user');
        }
    }

    public static function restore()
    {
        if (request::$params) {
            $params = array(
                'user_restore' => array(
                    'id' => request::$params
                )
            );

            $settings = array(
                'user_restore' => array()
            );

            $restore = api::query('user/restore', $params['user_restore'], $settings['user_restore']);
            if (f::is_done($restore)) {
                $url_redirect = (!empty(ATCore::$serv->http_referer) ? ATCore::$serv->http_referer : '/' . request::$controller . '/edit/' . request::$params);
                header('Location: ' . $url_redirect);
            }
        } else {
            header('Location: /user');
        }
    }
}