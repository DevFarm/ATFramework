<?=view::load('app/tabs', array(), true)?>
<script type="text/javascript">

    var rule = <?=$rule_count?>;

    $(function(){
        $('#api_key').popover({
            trigger: 'focus',
            title: 'Инфо по API ключу',
            content: 'API ключ - это средство для контроля доступа к API функциям системы и защиты от не санкционированного доступа к ним.<br/>Каждый API ключ, обладает определенными правами доступа к API функциям.<br/>Обладая API ключом, вы можете удаленно получить информацию с сервера и/или изменить ее (если имеете доступ к таким функциям). '
        });

        $('#add-rule').bind('click', function(){
            $('#rule-block').append('<div><input type="text" name="new_rule['+rule+']" /> <input type="checkbox" value="1" name="new_access['+rule+']" /> -  доступ разрешен <button type="button" class="btn btn-danger btn-mini remove-rule"><i class="icon-remove"></i></button><br/></div>');
            rule++;
            $('.remove-rule').one('click', function(){
                $(this).parent().remove();
            });
        });

        $('.remove-rule').bind('click', function(){
            $(this).parent().remove();
        });
    });

</script>

<?
if(request::$action == 'edit')
{
    ?>
    <legend>Редактирование приложения <span class="bold">&laquo;<?=$_POST['name']?>&raquo;</span></legend>
    <?
    if(!empty($_POST['del']))
    {
        ?>
        <p class="fc-red bold">
            Приложение было удалено! <button class="btn btn-danger btn-mini" onclick="location='/app/restore/<?=request::$params?>';">восстановить?</button>
        </p>
        <br/>
    <?
    }
}
else
{
    ?>
    <legend>Добавление приложения</legend>
<?
}
?>

<form method="POST" class="form-horizontal">
    <?
    if(form::has_error('other_error'))
    {
        ?>
        <div class="control-group error">
            <div class="help-inline"><i class="icon-warning-sign"></i> <b><?form::notice('other_error')?></b></div>
            <div class="help-block">К сожалению такое случается, отчет об ошибке сохранен и в скором времени, ошибка будет устранена!</div>
        </div>
    <?
    }
    ?>

    <div class="control-group <?if(form::has_error('name')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Название:</span></label>
        <div class="controls">
            <?form::text('name', array('class'=>'span3'))?>
            <div class="help-inline">&nbsp;<?form::notice('name')?></div>
            <div class="help-block">Подсказка: Не более 255 символов</div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('comment')){?>error<?}?>">
        <label class="control-label"><span class="label label-inverse">Описание:</span></label>
        <div class="controls">
            <?form::text('comment', array('class'=>'span3'))?>
            <div class="help-inline">&nbsp;<?form::notice('comment')?></div>
            <div class="help-block">Подсказка: Не более 255 символов</div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('api_key')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">API ключ:</span></label>
        <div class="controls">
            <?form::text('api_key', array('class'=>'span3', 'id'=>'api_key'))?>
            <div class="help-inline">&nbsp;<?form::notice('api_key')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <hr/>

    <div class="control-group <?if(form::has_error('rule')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Правила доступа:</span></label>
        <div class="controls">
            <div id="rule-block">
                <?
                if(isset($_POST['rule']))
                {
                    foreach($_POST['rule'] as $key => $rule)
                    {
                        ?>
                        <div>
                            <?form::text('rule[]')?>
                            <?form::checkbox('access[]')?> - доступ разрешен
                            <button type="button" class="btn btn-danger btn-mini remove-rule"><i class="icon-remove"></i></button>
                            <br/>
                        </div>
                    <?
                    }
                }

                if(isset($_POST['new_rule']))
                {
                    foreach($_POST['new_rule'] as $key => $rule)
                    {
                        ?>
                        <div>
                            <?form::text('new_rule[]')?>
                            <?form::checkbox('new_access[]')?> - доступ разрешен
                            <button type="button" class="btn btn-danger btn-mini remove-rule"><i class="icon-remove"></i></button>
                            <br/>
                        </div>
                    <?
                    }
                }

                if(empty($_POST['rule']) && empty($_POST['new_rule']))
                {
                    ?>
                    <div>
                        <?form::text('new_rule[]')?>
                        <?form::checkbox('new_access[]')?> - доступ разрешен
                        <button type="button" class="btn btn-danger btn-mini remove-rule"><i class="icon-remove"></i></button>
                        <br/>
                    </div>
                <?
                }
                ?>
            </div>
            <div class="help-inline">&nbsp;<?form::notice('rule')?></div>
            <div class="help-block"></div>
            <br/>
            <button type="button" class="btn btn-mini" id="add-rule"><i class="icon-plus-sign"></i> Добавить правило</button>
        </div>
    </div>

    <hr/>

    <div><span class="label label-important">&nbsp;&nbsp;</span> - обязательно к заполнению</div>
    <div><span class="label label-warning">&nbsp;&nbsp;</span> - не обязательно к заполнению при определенном условии</div>
    <div><span class="label label-info">&nbsp;&nbsp;</span> - поле имеет значение по умолчанию</div>
    <div><span class="label label-inverse">&nbsp;&nbsp;</span> - не обязательно к заполнению</div>

    <div class="form-actions">
        <a class="btn" href="/app"><i class="icon-arrow-left"></i> Отмена</a>
        <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Сохранить</button>
    </div>

    <?form::hidden('del')?>
</form>