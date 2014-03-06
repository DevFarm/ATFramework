<script type="text/javascript">

    $(function(){
        $(".table").tablesorter({
            headers: {
                6: {
                    sorter: false
                }
            }
        });
    });
</script>

<p class="bold">Выберите приложение для управления его контроллерами и действиями.</p>

<table class="table table-striped table-bordered table-condensed tablesorter">
    <thead>
    <th>Приложение</th>
    <th>Кол-во контроллеров</th>
    <th>Кол-во действий</th>
    <th width="100">Действия</th>
    </thead>
    <tbody>
    <?
    if(!empty($list))
    {
        foreach($list as $app => $data)
        {
            ?>
            <tr>
                <td>
                    <a href="/app/edit/<?=$app;?>" title="Редактировать приложение"><?=$list_app[$app]['name']?></a>
                    <?
                    if($app == api::$app_id)
                    {
                        ?>
                            <span class="label label-info fs-mini">Текущее</span>
                        <?
                    }
                    ?>
                </td>
                <td><?=count($data['controllers']);?></td>
                <td>
                    <?
                    $countActions = 0;
                    if(!empty($data['controllers']))
                    {
                        foreach($data['controllers'] as $controller)
                        {
                            $countActions += count($controller['actions']);
                        }
                    }
                    echo $countActions;
                    ?>
                </td>
                <td>
                    <a href="/controller/show/<?=$app?>" class="btn btn-info"><i class="icon-eye-open icon-white"></i></a>
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