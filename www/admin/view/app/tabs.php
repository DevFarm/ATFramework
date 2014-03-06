<?
$tabs = array(
    'app'	=> array(
        'app'	=> array('index','search','add','edit','delete')
    ),
);
?>
<ul class="nav nav-tabs">
    <li <?if(isset($tabs['app'][request::$controller]) && in_array(request::$action, $tabs['app'][request::$controller])){?>class="active"<?}?>><a href="/app"><i class="icon-hdd icon-black"></i> Приложения</a></li>
    <?
    switch (request::$controller)
    {
        case 'app':
        {
            ?>
            <a class="btn btn-success <?if(request::$action=='add'){?>active<?}?> right" href="/app/add"><i class="icon-plus icon-white"></i> Добавить приложение</a>
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