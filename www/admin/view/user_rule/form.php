<?=view::load('user/tabs', array(), true)?>
<script type="text/javascript">
    var Controllers = {
        controller: '<?=(isset($controller)?$controller:'')?>',
        action: '<?=(isset($action)?$action:'')?>',
        app: <?=(isset($app)?$app:'1')?>,
        list: <?=json_encode($light_list_controller)?>,
        setSection: function(){
            if(!Controllers.controller) {
                $.each(Controllers.list, function(i, el){
                    if(el.app == Controllers.app) {
                        Controllers.controller = el.name;
                    }
                });
            }

            $('#controller').html('<option value="*">Полный доступ</option>');

            $.each(Controllers.list, function(i, el){
                if(el.app == Controllers.app) {
                    $('#controller').append('<option value="'+el.name+'" '+((el.name == Controllers.controller)?'selected':'')+'>'+el.description+'</option>')
                        .bind('change', function(){
                            Controllers.setAction($(this).val());
                        });
                }
            });

            Controllers.setAction(Controllers.controller);
        },
        setAction: function(section){
            var controller = Controllers.list[section];

            $('#action').html('<option value="">Не выбрано</option><option value="*">Полный доступ</option>');

            $.each(controller.actions, function(i, el){
                if(i)
                {
                    $('#action').append('<option value="'+el.name+'" '+((el.name == Controllers.action)?'selected':'')+'>'+el.description+'</option>');
                }
            });
        }
    };

    $(function(){
        $('.btn-group button').bind('click', function(){
            $('input[name=access]').val($(this).val());
        });

        $('#app').bind('change', function(){
            Controllers.app = $(this).val();
            Controllers.setSection();
        });

        Controllers.setSection();
    });
</script>

<?
if(request::$action == 'edit')
{
    ?>
    <legend>Редактирование правила "<span class="bold"><?=$title?>"</span></legend>
    <?
    if($_POST['del'])
    {
        ?>
        <p class="fc-red bold">Правило было удалено! <button class="btn btn-danger btn-mini" onclick="location='/user_rule/restore/<?=request::$params?>';">восстановить?</button></p><br/>
    <?
    }
}
else
{
    ?>
    <legend>Добавление правила</legend>
<?
}
?>

<form method="POST" class="form-horizontal">
    <div class="control-group">
        <label class="control-label"><span class="label label-info">Выберите действие:</span></label>
        <div class="controls">
            <div class="btn-group" data-toggle="buttons-radio">
                <button type="button" value="1" class="btn btn-info <?if(!isset($_POST['access']) || $_POST['access']){?>active<?}?>">Разрешить доступ</button>
                <button type="button" value="0" class="btn btn-info <?if(isset($_POST['access']) && !$_POST['access']){?>active<?}?>">Запретить доступ</button>
            </div>
            <?form::hidden('access')?>
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

    <div class="control-group <?if(form::has_error('controller')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Контроллер:</span></label>
        <div class="controls">
            <?form::select('controller', array(), array('class'=>'span3', 'id'=>'controller'))?>
            <div class="help-inline">&nbsp;<?form::notice('controller')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('action')){?>error<?}?>">
        <label class="control-label"><span class="label label-warning">Действие:</span></label>
        <div class="controls">
            <?form::select('action', array(), array('class'=>'span3', 'id'=>'action'))?>
            <div class="help-inline">&nbsp;<?form::notice('action')?></div>
            <div class="help-block">не обязательное поле, при условии что в контроллере выбран полный доступ</div>
        </div>
    </div>

    <hr/>

    <div><span class="label label-important">&nbsp;&nbsp;</span> - обязательно к заполнению</div>
    <div><span class="label label-warning">&nbsp;&nbsp;</span> - не обязательно к заполнению при определенном условии</div>
    <div><span class="label label-info">&nbsp;&nbsp;</span> - поле имеет значение по умолчанию</div>
    <div><span class="label label-inverse">&nbsp;&nbsp;</span> - не обязательно к заполнению</div>

    <div class="form-actions">
        <a class="btn" href="/user_rule"><i class="icon-arrow-left"></i> Отмена</a>
        <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Сохранить</button>
    </div>
</form>