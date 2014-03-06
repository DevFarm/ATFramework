<?=view::load('app/tabs', array(), true)?>
<?=view::load('app/filter', array(), true)?>

<script>
    $(function(){
        $(".table").tablesorter({
            headers: {
                5: {
                    sorter: false
                }
            }
        });

        var speech_fields = '#id, #name, #api_key';

        $(speech_fields).bind('speechchange', function(){
            $('#main-filter').submit();
        })
            .bind('webkitspeechchange', function(){
                $('#main-filter').submit();
            });

        var settings = {
            format: 'dd.mm.yyyy',
            weekStart: 1
        };

        $('#from').datepicker(settings);
        $('#to').datepicker(settings);
    });
</script>

<table class="table table-striped table-bordered table-condensed tablesorter">
    <thead>
    <th>ID</th>
    <th>Название</th>
    <th>API ключ</th>
    <th>Последний доступ</th>
    <th>Комментарий</th>
    <th width="80">Действия</th>
    </thead>
    <tbody>
    <?
    if($list)
    {
        foreach($list as $data)
        {
            ?>
            <tr>
                <td><?=$data['id']?></td>
                <td>
                    <?=$data['name']?>
                    <?
                    if($data['id'] == api::$app_id)
                    {
                        ?>
                        <span class="label label-info fs-mini">Текущее</span>
                    <?
                    }

                    if($data['del'])
                    {
                        ?>
                        <span class="label label-important fs-mini">Удалено!</span>
                    <?
                    }
                    ?>
                </td>
                <td><?=$data['api_key']?></td>
                <td><?=$data['last_access']?></td>
                <td><?=$data['comment']?></td>

                <td>
                    <a href="/app/edit/<?=$data['id']?>" class="btn btn-info"><i class="icon-edit icon-white"></i></a>
                    <button class="btn btn-danger" onclick="if(confirm('Вы подтверждаете свои действия?')){location='/app/delete/<?=$data['id']?>';}"><i class="icon-trash icon-white"></i></button>
                </td>
            </tr>
        <?
        }
    }
    else
    {
        ?>
        <td colspan="6" style="text-align: center;"><b>Список приложений пуст</b></td>
    <?
    }
    ?>
    </tbody>
</table>
<div class="pagination pagination-centered">
    <?view::pagination($page, $show, $count, '/'.request::$controller.'/'.request::$action.'/?page=ID')?>
</div>