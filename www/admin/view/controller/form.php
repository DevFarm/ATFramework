<script type="text/javascript">

    var Action = {
        data: <?=json_encode($list_action)?>,
        set: function(){
            $('#action-list').empty();

            var index = 0;

            $.each(Action.data, function(i, el){
                if(el) {
                $('#action-list').append('<li><a href="#" id="action-'+i+'" data-name="'+el.name+'" data-description="'+el.description+'" class="action"><b>'+el.name+'</b>: '+el.description+'</a></li>');
                index++;
                }
            });

            if(!index)
            {
                $('#action-list').append('<li><a>Пусто</a></li>');
            }

            $('#action-list').append('<li class="divider"></li><li><a href="/action/add" target="_blank">Добавить действие</a></li>');

            $('.action').bind('click', function(){
                var id = $(this).attr('id');
                id = parseInt(id.replace('action-', ''));

                delete Action.data[id];
                $(this).parent().remove();

                if($('#action-list li a.action').length == 0)
                {
                    $('#action-list').prepend('<li><a>Пусто</a></li>');
                }

                $('#selected-actions').append('<span data-id="'+id+'" data-name="'+$(this).data('name')+'" data-description="'+$(this).data('description')+'"><br/><input type="hidden" name="actions[]" value="'+id+'" /><i class="icon-play"></i> <span class="content">'+$(this).html()+'</span> <i class="icon-trash" onclick="Action.del(this)"></i></span>');
                return false;
            });
        },
        del: function(el){
            el = $(el).parent().remove();

            Action.data[$(el).data('id')] = {
                "id": $(el).data('id'),
                "name": $(el).data('name'),
                "description": $(el).data('description')
            };

            Action.set();
        }
    };

    $(function(){
        $('#name').popover({
            trigger: 'focus',
            title: 'Название контроллера',
            content: 'Название должно быть указано латинскими буквами, т.к. оно является системным. А так же, допускается ввод цифр.'
        });

        Action.set();
    });

</script>

<?
if(request::$action == 'edit')
{
    ?>
    <legend>Редактирование контроллера "<span class="bold"><?=$info_controller['name']?></span>"</legend>
    <?
    if(!empty($info_controller['del']))
    {
        ?>
        <p class="fc-red bold">
            Контроллер был удален! <button class="btn btn-danger btn-mini" onclick="location='/controller/restore/<?=request::$params?>';">восстановить?</button>
        </p>
        <br/>
    <?
    }
}
else
{
    ?>
    <legend>Добавление контроллера</legend>
<?
}
?>

<form method="POST" class="form-horizontal">

    <div class="control-group <?if(form::has_error('name')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Название:</span></label>
        <div class="controls">
            <?form::text('name', array('class'=>'span3', 'id'=>'name'))?>
            <div class="help-inline">&nbsp;<?form::notice('name')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('description')){?>error<?}?>">
        <label class="control-label"><span class="label label-inverse">Описание:</span></label>
        <div class="controls">
            <?form::textarea('description', array('class'=>'span3', 'id'=>'description'))?>
            <div class="help-inline">&nbsp;<?form::notice('description')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('icon')){?>error<?}?>">
        <label class="control-label"><span class="label label-inverse">Иконка:</span></label>
        <div class="controls">
            <?form::text('icon', array('class'=>'span3', 'id'=>'icon'))?>
            <div class="help-inline">&nbsp;<?form::notice('icon')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('app')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Приложение:</span></label>
        <div class="controls">
            <?form::select('app', $list_app, array('class'=>'span3', 'id'=>'app'))?>
            <div class="help-inline">&nbsp;<?form::notice('app')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('actions')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Действия:</span></label>
        <div class="controls">
            <div>
                <div class="btn-group pull-left">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        Выбрать действия
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" id="action-list">
                    </ul>
                </div>
                <div class="help-inline">&nbsp;<?form::notice('actions')?></div>
                <br class="clearfix" />
                <div id="selected-actions">
                    <?
                    if(!empty($_POST['actions']))
                    {
                        $action = '';
                        foreach($_POST['actions'] as $id => $action)
                        {
                            ?>
                            <span data-id="<?=$id?>" data-name="<?=$action['name']?>" data-description="<?=$action['description']?>">
                                <br/>
                                <input type="hidden" name="actions[]" value="<?=$id?>" />
                                <i class="icon-play"></i>
                                <b><?=$action['name']?></b>:
                                <span class="content"><?=$action['description']?></span>
                                <i class="icon-trash" onclick="Action.del(this)"></i>
                            </span>
                        <?
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>

    <hr/>

    <div><span class="label label-important">&nbsp;&nbsp;</span> - обязательно к заполнению</div>
    <div><span class="label label-warning">&nbsp;&nbsp;</span> - не обязательно к заполнению при определенном условии</div>
    <div><span class="label label-info">&nbsp;&nbsp;</span> - поле имеет значение по умолчанию</div>
    <div><span class="label label-inverse">&nbsp;&nbsp;</span> - не обязательно к заполнению</div>

    <div class="form-actions">
        <a class="btn" href="/controller/show/<?=(isset($info_controller['app'])?$info_controller['app']:request::$params)?>"><i class="icon-arrow-left"></i> Отмена</a>
        <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Сохранить</button>
    </div>
</form>