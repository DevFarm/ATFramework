<?=view::load('controller/tabs', array(), true)?>
<?=view::load('controller/filter', array(), true)?>
<p class="bold">Контроллеры и действия приложения "<?=$info_app['name']?>".</p>

<script type="text/javascript">

    $(function(){
        $(".table").tablesorter({
            headers: {
                1: {
                    sorter: false
                },
                2: {
                    sorter: false
                }
            }
        });
    });
</script>

<table class="table table-striped table-bordered table-condensed tablesorter">
    <thead>
    <th style="width: 500px">Контроллер</th>
    <th>Список действий</th>
    <th>Иконка</th>
    <th width="100">Действия</th>
    </thead>
    <tbody>
    <?
    if(!empty($list))
    {
        foreach($list as $controller => $data)
        {
            ?>
            <tr>
                <td>
                    <div class="bold">
                        <span class="<? if($data['del']) {?>fl_through<?}?>"><?=$data['name'];?></span>
                        <? if($data['del']) {?><span class="label label-important fs-mini">Удален</span><?}?>
                    </div>
                    <span class="fs-small"><?=$data['description'];?></span>
                </td>
                <td>
                    <?
                    if(!empty($data['actions']))
                    {
                        foreach($data['actions'] as $action)
                        {
                            ?>
                                <span class="label label-<?=((!$action['del'])?'inverse':'important')?>">
                                    <a href="/action/edit/<?=$action['id']?>" class="fc-white">
                                        <?=$action['name']?>:
                                        <span class="fs-small"><?=$action['description']?></span>
                                    </a>
                                </span>&nbsp;
                            <?
                        }
                    }
                    ?>
                </td>
                <td><i class="icon-<?=$data['icon']?>"></i></td>
                <td>
                    <a href="/controller/edit/<?=$controller?>" class="btn btn-info"><i class="icon-edit icon-white"></i></a>
                    <button class="btn btn-danger" onclick="if(confirm('Вы подтверждаете свои действия?')){location='/controller/delete/<?=$data['id']?>';}"><i class="icon-trash icon-white"></i></button>
                </td>
            </tr>
        <?
        }
    }
    ?>
    </tbody>
</table>
<div class="pagination pagination-centered">
    <?//view::pagination($page, $show, $count, '/'.request::$controller.'/'.request::$action.'/?page=ID')?>
</div>