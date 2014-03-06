<?=view::load('action/tabs', array(), true)?>
<?=view::load('action/filter', array(), true)?>
<script type="text/javascript">

    $(function(){
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
    <th>#</th>
    <th>Название</th>
    <th>Описание</th>
    <th>Иконка</th>
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
                    <span class="<? if($data['del']) {?>fl_through<?}?>"><?=$data['name'];?></span>
                    <? if($data['del']) {?><span class="label label-important fs-mini">Удалено</span><?}?>
                </td>
                <td><?=$data['description']?></td>
                <td><i class="icon-<?=$data['icon']?>"></i></td>
                <td>
                    <a href="/action/edit/<?=$data['id']?>" class="btn btn-info"><i class="icon-edit icon-white"></i></a>
                    <button class="btn btn-danger" onclick="if(confirm('Вы подтверждаете свои действия?')){location='/action/delete/<?=$data['id']?>';}"><i class="icon-trash icon-white"></i></button>
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