<?=view::load('user/tabs', array(), true)?>
<script type="text/javascript">

    $(function(){
        $('#email').popover({
            trigger: 'focus',
            title: 'Инфо по Email-адресу',
            content: 'Указанный Email-адрес пользователя, служит так же и логином для авторизации в системе!'
        });

        $('#password').popover({
            trigger: 'focus',
            title: 'Составление пароля',
            content: 'Пароль должен быть не мение 6 символов и содержать цифры и буквы!'
        });
    });

</script>


<?
if(request::$action == 'edit')
{
    ?>
    <legend>Редактирование пользователя <span class="bold"><?=$_POST['surname']?> <?=$_POST['name']?></span></legend>
    <?
    if(!empty($_POST['del']))
    {
        ?>
        <p class="fc-red bold">
            Пользователь был удален! <button class="btn btn-danger btn-mini" onclick="location='/user/restore/<?=request::$params?>';">восстановить?</button>
        </p>
        <br/>
    <?
    }
}
else
{
    ?>
    <legend>Добавление пользователя</legend>
<?
}
?>

<form method="POST" class="form-horizontal">
    <div class="control-group <?if(form::has_error('email')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">E-mail:</span></label>
        <div class="controls">
            <?form::text('email', array('class'=>'span3', 'id'=>'email'))?>
            <div class="help-inline">&nbsp;<?form::notice('email')?></div>
            <div class="help-block">Например: admin@<?=ATCore::$serv->http_host?></div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('login')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Логин:</span></label>
        <div class="controls">
            <?form::text('login', array('class'=>'span3', 'id'=>'login'))?>
            <div class="help-inline">&nbsp;<?form::notice('login')?></div>
            <div class="help-block">Производное от фамилии, имени или от чего то еще...</div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('name')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Имя:</span></label>
        <div class="controls">
            <?form::text('name', array('class'=>'span2'))?>
            <div class="help-inline">&nbsp;<?form::notice('name')?></div>
            <div class="help-block">Например: Иван</div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('surname')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Фамилия:</span></label>
        <div class="controls">
            <?form::text('surname', array('class'=>'span2'))?>
            <div class="help-inline">&nbsp;<?form::notice('surname')?></div>
            <div class="help-block">Например: Иванов</div>
        </div>
    </div>

    <div class="control-group ">
        <label class="control-label"><span class="label label-inverse">Отчество:</span></label>
        <div class="controls">
            <?form::text('middle_name', array('class'=>'span3'))?>
            <div class="help-inline">&nbsp;</div>
            <div class="help-block">Например: Иванович</div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('role')){?>error<?}?>">
        <label class="control-label"><span class="label label-inverse">Должность:</span></label>
        <div class="controls">
            <?form::select('role', $user_role_list, array('class'=>'span3'))?>
            <div class="help-inline">&nbsp;<?form::notice('role')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <hr />

    <div class="control-group <?if(form::has_error('password')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Пароль:</span></label>
        <div class="controls">
            <?form::password('password', array('class'=>'span3', 'id'=>'password'))?>
            <div class="help-inline">&nbsp;<?form::notice('password')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="control-group <?if(form::has_error('repassword')){?>error<?}?>">
        <label class="control-label"><span class="label label-important">Пароль повторно:</span></label>
        <div class="controls">
            <?form::password('repassword', array('class'=>'span3'))?>
            <div class="help-inline">&nbsp;<?form::notice('repassword')?></div>
            <div class="help-block"></div>
        </div>
    </div>

    <hr/>

    <div><span class="label label-important">&nbsp;&nbsp;</span> - обязательно к заполнению</div>
    <div><span class="label label-warning">&nbsp;&nbsp;</span> - не обязательно к заполнению при определенном условии</div>
    <div><span class="label label-info">&nbsp;&nbsp;</span> - поле имеет значение по умолчанию</div>
    <div><span class="label label-inverse">&nbsp;&nbsp;</span> - не обязательно к заполнению</div>

    <div class="form-actions">
        <a class="btn" href="/user"><i class="icon-arrow-left"></i> Отмена</a>
        <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Сохранить</button>
    </div>
</form>