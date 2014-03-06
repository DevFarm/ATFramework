<?
$tabs = array(
    'action' => array(
        'action' => array('index','search','add','edit','delete')
    )
);
?>
<ul class="nav nav-tabs">
    <li <?if(isset($tabs['action'][request::$controller]) && in_array(request::$action, $tabs['action'][request::$controller])){?>class="active"<?}?>><a href="/action"><i class="icon-play-circle"></i> Действия</a></li>
    <?
    switch (request::$controller)
    {
        case 'action':
        {
            ?>
            <a class="btn btn-success <?if(request::$action=='add'){?>active<?}?> right" href="/action/add"><i class="icon-plus icon-white"></i> Добавить действие</a>
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