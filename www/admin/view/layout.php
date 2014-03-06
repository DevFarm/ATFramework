<!DOCTYPE html>
<html>
<head>
    <title>Панель администрирования</title>
    <link rel="stylesheet" href="/css/bootstrap.css"/>
    <link rel="stylesheet" href="/css/datepicker.css"/>

    <link rel="stylesheet" href="/css/base.css"/>
    <link rel="stylesheet" href="/css/main.css"/>

    <script src="/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="/js/jquery.tablesorter.min.js" type="text/javascript"></script>
    <script src="/js/bootstrap-datepicker.js" type="text/javascript"></script>

    <script src="/js/bootstrap.min.js" type="text/javascript"></script>

    <script src="/js/main.js" type="text/javascript"></script>
</head>
<body style="padding: 70px 20px 0 20px; min-width: 600px;">
<div class="navbar navbar-fixed-top" style="min-width: 600px;">
    <div class="navbar-inner" style="padding-left: 20px;">
        <a class="brand" href="/">ATFramework</a>

        <div class="nav-collapse collapse">
            <ul class="nav">
                <li class="active">
                    <a href="/user">Профиль</a>
                </li>
            </ul>
            <ul class="nav" style="float: right;">
                <li class="active">
                    <a href="/user"><?=auth::$user['name']?> <?=auth::$user['surname']?></a>
                </li>
                <li>
                    <a href="/logout"><i class="icon-off icon-white"></i> Выйти</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row-fluid">

    <aside class="left span2 well" style="padding: 8px 0;">
        <?
        $menu = array(
            'menu' => array(
                'main'       => array(
                    'title'       => 'Главная',
                    'icon'        => 'home',
                    'controllers' => array(
                        'main'
                    )
                ),
                'user'       => array(
                    'title'       => 'Пользователи',
                    'icon'        => 'user',
                    'controllers' => array(
                        'user',
                        'user_role',
                        'user_rule'
                    )
                ),
                'app'        => array(
                    'title'       => 'Приложения',
                    'icon'        => 'hdd',
                    'controllers' => array(
                        'app'
                    )
                ),
                'stat'       => array(
                    'title' => 'Статистика',
                    'icon'  => 'signal'
                ),
                'log'        => array(
                    'title'       => 'Логи',
                    'icon'        => 'info-sign',
                    'controllers' => array(
                        'log',
                        'log_api'
                    )
                ),
                'controller' => array(
                    'title'       => 'Контроллеры',
                    'icon'        => 'book',
                    'controllers' => array(
                        'controller'
                    )
                ),
                'action' => array(
                    'title'       => 'Действия',
                    'icon'        => 'play-circle',
                    'controllers' => array(
                        'action'
                    )
                ),
            )
        );
        echo view::load('menu-left', $menu, true);
        ?>
    </aside>

    <div class="span10">
        <?= $content; ?>
        <br/><br/>
    </div>

</div>

<div class="navbar navbar-fixed-bottom" style="min-width: 600px;">
    <div class="navbar-inner" style="padding-left: 20px;">
        <div class="nav-collapse collapse">
            <ul class="nav">
                <li><span class="navbar-text">&copy; 2013-<?=date('Y')?> ATFramework</span></li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>