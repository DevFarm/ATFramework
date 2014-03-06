<?=view::load('log/tabs', array(), true)?>

<table class="table table-striped table-bordered table-condensed tablesorter">
    <thead>
    <th>ID</th>
    <th>Приложение</th>
    <th>Контроллер</th>
    <th>Действие</th>
    <th>Содержимое</th>
    <th>Дата</th>
    <th>IP-адрес</th>
    <th width="80">Действия</th>
    </thead>
    <tbody>
    <?
    if(!empty($list))
    {
        foreach($list as $data)
        {
            ?>
            <tr>
                <td><?=$data['id']?></td>
                <td>
                    <?
                    if($data['app'])
                    {
                        ?>
                        <a href="/app/edit/<?=$data['app']?>"><?=$list_app[$data['app']]['name']?></a>
                        <?
                        if($data['app']==$current_app_id)
                        {
                            ?>
                            <span class="label label-info fs-mini">текущее</span>
                        <?
                        }
                        ?>
                        <div class="fs-small fc-greydark"><?=$list_app[$data['app']]['comment']?></div>
                    <?
                    }
                    else
                    {
                        ?>
                        <div class="fs-small fc-greydark">не определено</div>
                    <?
                    }
                    ?>
                </td>
                <td><?=$data['controller']?></td>
                <td><?=$data['action']?></td>
                <td style="width: 200px;"><?=$data['text']?></td>
                <td><?=$data['date']?></td>
                <td><?=$data['ip']?></td>
                <td>
                    <button class="btn btn-danger" onclick="if(confirm('Вы подтверждаете свои действия?')){location='/user/delete/<?=$data['id']?>';}"><i class="icon-trash icon-white"></i></button>
                </td>
            </tr>
        <?
        }
    }
    ?>
    </tbody>
</table>
<div class="pagination pagination-centered">
    <?view::pagination($page, $show, $count, '/'.request::$controller.'/'.request::$action.'/?page=ID')?>
</div>