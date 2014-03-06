<?
$tabs = array(
    'user'	=> array(
        'user'	=> array('index','search','add','edit','delete')
    ),
    'user_role'	=> array(
        'user_role'	=> array('index','search','add','edit','delete')
    ),
    'user_rule'	=> array(
        'user_rule'	=> array('index','search','add','edit','delete')
    )
);
?>
<ul class="nav nav-tabs">
    <li <?if(isset($tabs['user'][request::$controller]) && in_array(request::$action, $tabs['user'][request::$controller])){?>class="active"<?}?>><a href="/user"><i class="icon-user"></i> Пользователи</a></li>
    <li <?if(isset($tabs['user_role'][request::$controller]) && in_array(request::$action, $tabs['user_role'][request::$controller])){?>class="active"<?}?>><a href="/user_role"><i class="icon-refresh"></i> Должности</a></li>
    <li <?if(isset($tabs['user_rule'][request::$controller]) && in_array(request::$action, $tabs['user_rule'][request::$controller])){?>class="active"<?}?>><a href="/user_rule"><i class="icon-eye-open"></i> Права доступа</a></li>
    <?
    switch (request::$controller)
    {
        case 'user':
        {
            ?>
            <a class="btn btn-success <?if(request::$action=='add'){?>active<?}?> right" href="/user/add"><i class="icon-plus icon-white"></i> Добавить пользователя</a>
            <?
            break;
        }
        case 'user_role':
        {
            ?>
            <a class="btn btn-success <?if(request::$action=='add'){?>active<?}?> right" href="/user_role/add"><i class="icon-plus icon-white"></i> Добавить должность</a>
            <?
            break;
        }
        case 'user_rule':
        {
            ?>
            <a class="btn btn-success <?if(request::$action=='add'){?>active<?}?> right" href="/user_rule/add"><i class="icon-plus icon-white"></i> Добавить правило</a>
            <?
            break;
        }
        default:
            {
            break;
            }
    }
    ?>
</ul>