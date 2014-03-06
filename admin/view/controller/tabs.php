<?
$tabs = array(
    'controller' => array(
        'controller' => array('index','search','add','show','edit','delete')
    )
);
?>
<ul class="nav nav-tabs">
    <li><a href="/controller" class="btn-sm"><i class="icon icon-arrow-left"></i> Вернуться</a></li>
    <li <?if(isset($tabs['controller'][request::$controller]) && in_array(request::$action, $tabs['controller'][request::$controller])){?>class="active"<?}?>><a href="/controller/show/<?=intval(request::$params)?>"><i class="icon-book"></i> Контроллеры</a></li>
    <?
    switch (request::$controller)
    {
        case 'controller':
        {
            ?>
            <a class="btn btn-success <?if(request::$action=='add'){?>active<?}?> right" href="/controller/add/<?=intval(request::$params)?>"><i class="icon-plus icon-white"></i> Добавить контроллер</a>
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