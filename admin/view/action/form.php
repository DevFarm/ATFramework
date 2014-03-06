<?
if(request::$action == 'edit')
{
    ?>
    <legend>Редактирование действия "<span class="bold"><?=$info_action['name']?></span>"</legend>
    <?
    if(!empty($info_action['del']))
    {
        ?>
        <p class="fc-red bold">
            Действие было удалено! <button class="btn btn-danger btn-mini" onclick="location='/action/restore/<?=request::$params?>';">восстановить?</button>
        </p>
        <br/>
    <?
    }
}
else
{
    ?>
    <legend>Добавление действия</legend>
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

    <hr/>

    <div><span class="label label-important">&nbsp;&nbsp;</span> - обязательно к заполнению</div>
    <div><span class="label label-warning">&nbsp;&nbsp;</span> - не обязательно к заполнению при определенном условии</div>
    <div><span class="label label-info">&nbsp;&nbsp;</span> - поле имеет значение по умолчанию</div>
    <div><span class="label label-inverse">&nbsp;&nbsp;</span> - не обязательно к заполнению</div>

    <div class="form-actions">
        <a class="btn" href="/action"><i class="icon-arrow-left"></i> Отмена</a>
        <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Сохранить</button>
    </div>
</form>