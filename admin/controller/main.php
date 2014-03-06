<?php

/**
 * Admin Controller Main of ATFramework v2.0
 *
 * @author Sumatorhak <sumatorhak@gmail.com>
 * @copyright Copyright Yury Fedotov
 * @date 19.02.14
 * @version 2.0.1
 */
use classes\base\ATCore_View as view;
use classes\base\ATCore_Api as api;

class Controller_Main extends Controller_Base
{
    public static function index()
    {
        $pagination = [
            'page' => 1,
            'show' => 20
        ];

        if (isset($_GET['page'])) {
            $pagination['page'] = intval($_GET['page']);
        }
        if (isset($_GET['show'])) {
            $pagination['show'] = intval($_GET['show']);
        }

        $params = ['controller_search' => [
            'ca-app' => 1 //ADM app
        ]];
        $settings = ['controller_search' => []];

        $params['controller_search'] = array_merge($params['controller_search'], $pagination);

        $search_controller = api::query('controller/search', $params['controller_search'], $settings['controller_search']);
        view::load('main/index', [
            'search_controller' => $search_controller
        ]);
    }
}