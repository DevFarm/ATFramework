<?=view::load('user/tabs', array(), true)?>
<?=view::load('user_rule/filter', array(), true)?>

<script type="text/javascript">

    $(function(){

    });

</script>

<table class="table table-striped table-bordered table-condensed tablesorter">
    <thead>
    <th>ID</th>
    <th>Приложение</th>
    <th>Контроллер</th>
    <th>Действие</th>
    <th>Доступ</th>
    <th width="100">Действия</th>
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
                    <a href="/app/edit/<?=$data['app']?>" title="Редактировать приложение"><?=$list_app[$data['app']]['name']?></a>
                    <?
                    if($data['app']==api::$app_id)
                    {
                        ?>
                        <span class="label label-info fs-mini">текущее</span>
                    <?
                    }

                    if($data['del'])
                    {
                        ?>
                        <span class="label label-important fs-mini">Удалено!</span>
                    <?
                    }
                    ?>
                    <div class="fs-small fc-greydark"><?=$list_app[$data['app']]['comment']?></div>
                </td>
                <td>
                    <?
                    if($data['controller'] == '*')
                    {
                        echo '<b>Полный доступ</b>';
                    }
                    else
                    {

                        echo f::alias_controller($data['controller'], $list_controller);
                    }
                    ?>
                </td>
                <td>
                    <?
                    if($data['action'] == '*')
                    {
                        echo '<b>Полный доступ</b>';
                    }
                    else
                    {
                        echo f::alias_action($data['controller'], $data['action'], $list_controller);
                    }
                    ?>
                </td>
                <td>
                    <?=($data['access'])?'<i class="icon icon-ok"></i> <span class="fc-green">Доступ разрешен</span>':'<i class="icon icon-lock"></i> <span class="fc-red">Доступ запрещен</span>'?>
                </td>
                <td>
                    <a href="/user_rule/edit/<?=$data['id']?>" class="btn btn-info"><i class="icon-edit icon-white"></i></a>
                    <button class="btn btn-danger" onclick="if(confirm('Вы подтверждаете свои действия?')){location='/user_rule/delete/<?=$data['id']?>';}"><i class="icon-trash icon-white"></i></button>
                </td>
            </tr>
        <?
        }
    }
    else
    {
        ?>
        <td colspan="5" style="text-align: center;"><b>Список правил пуст</b></td>
    <?
    }
    ?>
    </tbody>
</table>
<div class="pagination pagination-centered">
    <?view::pagination($page, $show, $count, '/'.request::$controller.'/'.request::$action.'/?page=ID')?>
</div>