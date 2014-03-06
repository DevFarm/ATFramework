<?=view::load('user/tabs', array(), true)?>
<script>
    $(document).ready(function() {
        $(".table").tablesorter({
            headers: {
                3: {
                    sorter: false
                }
            }
        });
    });
</script>

<table class="table table-striped table-bordered table-condensed tablesorter">
    <thead>
    <th>ID</th>
    <th>Название</th>
    <th>Описание</th>
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
                    <?=$data['name']?>
                    <?
                    if($data['del'])
                    {
                        ?>
                        <span class="label label-important fs-mini">Удалена!</span>
                    <?
                    }
                    ?>
                </td>
                <td><?=$data['description']?></td>
                <td>
                    <a href="/user_role/edit/<?=$data['id']?>" class="btn btn-info"><i class="icon-edit icon-white"></i></a>
                    <button class="btn btn-danger" onclick="if(confirm('Вы подтверждаете свои действия?')){location='/user_role/delete/<?=$data['id']?>';}"><i class="icon-trash icon-white"></i></button>
                </td>
            </tr>
        <?
        }
    }
    else
    {
        ?>
        <td colspan="4" style="text-align: center;"><b>Список должностей пуст</b></td>
    <?
    }
    ?>
    </tbody>
</table>