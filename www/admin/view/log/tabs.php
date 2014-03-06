<?
$tabs = array(
    'log'	=> array(
        'log'		=> array('index','search','delete'),
    ),
    'log_api'	=> array(
        'log_api'		=> array('index','search','delete'),
    ),
);
?>
<ul class="nav nav-tabs">
    <li <?if(isset($tabs['log'][request::$controller]) && in_array(request::$action, $tabs['log'][request::$controller])){?>class="active"<?}?>><a href="/log"><i class="icon-info-sign icon-black"></i> Логи приложения</a></li>
    <li <?if(isset($tabs['log_api'][request::$controller]) && in_array(request::$action, $tabs['log_api'][request::$controller])){?>class="active"<?}?>><a href="/log_api"><i class="icon-info-sign icon-black"></i> API логи</a></li>
    <?
    switch (request::$controller)
    {
        default:
            {
            break;
            }
    }
    ?>
</ul>