<ul class="nav nav-list">
    <?
    foreach($menu as $uri => $m)
    {
        ?>
        <li <?if(isset($m['controllers']) && in_array(request::$controller, $m['controllers'])){?> class="active"<?}?>>
            <a href="/<?=$uri?>" style="height: 20px; padding-top: 7px;"><i class="icon-<?=$m['icon']?>"></i> <?=$m['title']?> <i class="icon-chevron-right right"></i></a>
        </li>
    <?
    }
    ?>
</ul>