<div class="page-header">
    <h1>Рабочий стол <small>- выводит все контроллеры и их действия.</small></h1>
</div>
<?=view::load('user/tabs', array(), true)?>

<?=view::load('user/filter', array(), true)?>

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

<table class="table table-striped table-bordered table-condensed tablesorter">
    <thead>
    <th>ID</th>
    <th>Email</th>
    <th>Имя</th>
    <th>Фамилия</th>
    <th>Отчество</th>
    <th>Должность</th>
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
                    <?
                    if($data['del'])
                    {
                        echo '<s>'.$data['email'].'</s>';
                    }
                    else
                    {
                        echo $data['email'];
                    }

                    if(auth::$user['id'] == $data['id'])
                    {
                        ?>
                        <span class="label label-info fs-mini">Это Вы!</span>
                    <?
                    }

                    if($data['del'])
                    {
                        ?>
                        <span class="label label-important fs-mini">Удален!</span>
                    <?
                    }
                    ?>
                </td>
                <td><?=$data['name']?></td>
                <td><?=$data['surname']?></td>
                <td><?=$data['middle_name']?></td>
                <td><?=(isset($user_role_list[$data['role']]) ? $user_role_list[$data['role']]['description'] : 'Не назначена')?></td>

                <td>
                    <a href="/user/edit/<?=$data['id']?>" class="btn btn-info"><i class="icon-edit icon-white"></i></a>
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