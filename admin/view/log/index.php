<?=view::load('log/tabs', array(), true)?>
<?=view::load('log/filter', array(), true)?>
<script>
    $(document).ready(function() {
        $(".table").tablesorter({
            headers: {
                4: {
                    sorter: false
                }
            }
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
    <th>Категория</th>
    <th>Содержимое</th>
    <th>Дата</th>
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
                <td><?=$data['category']?></td>
                <td style="width: 200px;"><?=$data['text']?></td>
                <td><?=$data['date']?></td>
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