<?=view::load('user/tabs', array(), true)?>
<script type="text/javascript">

    var app = 0;
    var Rule = {
        data: <?=json_encode($list_user_rule)?>,
        setRule: function(){
            $('#rule-list').empty();

            var app_rule = Rule.data[app],
                index = 0;

            if(!Rule.data[app]['rules'])
            {
                app_rule = Rule.data[app];
            }
            else
            {
                app_rule = Rule.data[app]['rules'];

                $.each(app_rule, function(i, el){
                    var controller	= ((el.controller=='*') ? '<b>Полный доступ</b>': el.controller),
                        action		= ((el.action=='*') ? ' - <b>полный доступ</b>': (el.action==''? '': ' - '+el.action))

                    $('#rule-list').append('<li><a href="#" id="rule-'+i+'" data-app-id="'+app+'" data-app="'+Rule.data[app]['name']+'" data-access="'+el.access+'" data-controller="'+el.controller+'" data-action="'+el.action+'" class="rule">'+controller+action+'</a></li>');
                    index++;
                });
            }

            if(!index)
            {
                $('#rule-list').append('<li><a>Пусто</a></li>');
            }

            $('#rule-list').append('<li class="divider"></li><li><a href="/user_rule/add" target="_blank">Добавить правило</a></li>');

            $('.rule').bind('click', function(){
                var id = $(this).attr('id');
                id = parseInt(id.replace('rule-', ''));

                delete Rule.data[app]['rules'][id];
                $(this).parent().remove();

                if($('#rule-list li a.rule').length == 0)
                {
                    $('#rule-list').prepend('<li><a>Пусто</a></li>');
                }

                var icon = (($(this).data('access')) ? 'plus' : 'minus');
                $('#selected-rules').append('<span data-id="'+id+'" data-app-id="'+$(this).data('app-id')+'" data-controller="'+$(this).data('controller')+'" data-action="'+$(this).data('action')+'" data-access="'+$(this).data('access')+'"><br/><input type="hidden" name="rule[]" value="'+id+'" /><i class="icon-'+icon+'-sign"></i> <b>'+$(this).data('app')+':</b> <span class="content">'+$(this).html()+'</span> <i class="icon-trash" onclick="Rule.delRule(this)"></i></span>');
                return false;
            });
        },
        delRule: function(el){
            el = $(el).parent().remove();

            if(!Rule.data[$(el).data('app-id')]['rules'])
            {
                Rule.data[$(el).data('app-id')]['rules'] = {};
            }

            Rule.data[$(el).data('app-id')]['rules'][$(el).data('id')] = {
                "controller": $(el).data('controller'),
                "action": $(el).data('action'),
                "access": $(el).data('access')
            };

            Rule.setRule();
        }
    };

    $(function(){
        $('#name').popover({
            trigger: 'focus',
            title: 'Имя должности',
            content: 'Имя должности должно быть указано латинскими буквами, т.к. оно является системным. А так же, допускается ввод цифр.'
        });

        for(var i in Rule.data) {
            if(!app)
            {
                app = i;
            }
        }

        Rule.setRule();

        $('#app').bind('change', function(){
            app = $(this).val();
            Rule.setRule();
        });
    });

</script>

<?
if(request::$action == 'edit')
{
    ?>
    <legend>Редактирование должности "<span class="bold"><?=$_POST['description']?>"</span></legend>
    <?
    if($_POST['del'])
    {
        ?>
        <p class="fc-red bold">Должность была удалена! <button class="btn btn-danger btn-mini" onclick="location='/user_role/restore/<?=request::$params?>';">восстановить?</button></p><br/>
    <?
    }
}
else
{
    ?>
    <legend>Добавление должности</legend>
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
        <label class="control-label"><span class="label label-important">Описание:</span></label>
        <div class="controls">
            <?form::text('description', array('class'=>'span3'))?>
            <div class="help-inline">&nbsp;<?form::notice('description')?></div>
            <div class="help-block">не более 255 символов</div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('rule')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Доступ:</span></label>
        <div class="controls">
            <div><?form::checkbox('full')?> - игнорирование правил (обеспечивает полный доступ ко всему).</div>
            <br/>
            <div>
                <div class="pull-left">
                    Выберите приложение:<br/>
                    <?form::select('', $list_app, array('id'=>'app'))?>&nbsp;
                </div>
                <br/>
                <div class="btn-group pull-left">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        Правила доступа
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" id="rule-list">
                    </ul>
                </div>
                <div class="help-inline">&nbsp;<?form::notice('rule')?></div>
                <br class="clearfix" />
                <div id="selected-rules">
                    <?
                    if(!empty($_POST['rule']))
                    {
                        $action = '';
                        foreach($_POST['rule'] as $id=>$rule)
                        {
                            if($rule['controller']=='*')
                            {
                                $controller	= '<b>Полный доступ</b>';
                            }
                            else
                            {
                                $controller = f::alias_controller($rule['controller'], $list_section);
                            }

                            if($rule['action']=='*')
                            {
                                $action	= ' - <b>полный доступ</b>';
                            }
                            else
                            {
                                if(!empty($rule['action']))
                                {
                                    $action	= ' - '.f::alias_action($rule['controller'], $rule['action'], $list_section);
                                }
                            }

                            $icon 		= (($rule['access']) ? 'plus' : 'minus');

                            ?>
                            <span data-id="<?=$id?>" data-app-id="<?=$rule['app_id']?>" data-controller="<?=f::alias_controller($rule['controller'], $list_section)?>" data-action="<?=f::alias_action($rule['controller'], $rule['action'], $list_section)?>" data-access="<?=$rule['access']?>">
									<br/>
									<input type="hidden" name="rule[]" value="<?=$id?>" />
									<i class="icon-<?=$icon?>-sign"></i>
									<b><?=$rule['app_name']?>:</b>
									<span class="content"><?=$controller.$action?></span>
									<i class="icon-trash" onclick="Rule.delRule(this)"></i>
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
    <div><span class="label label-inverse">&nbsp;&nbsp;</span> - не обязательно к заполнению</div>

    <div class="form-actions">
        <a class="btn" href="/user_role"><i class="icon-arrow-left"></i> Отмена</a>
        <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Сохранить</button>
    </div>
</form>