<!DOCTYPE html>
<html lang="en">
<head>
    <title>Административное приложение ABTOTOP.RU</title>
    <link rel="stylesheet" href="/css/bootstrap.css" />
    <link rel="stylesheet" href="/css/base.css" />
    <script src="/js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
<div class="row" style="padding-top: 100px;">
    <div class="span4" style="float: none; margin: 0 auto;">
        <div class="well">
            <label class="bold">Вход в панель администрирования</label>
            <hr/>
            <form method="post" action="">
                <div class="alert alert-error <?if(!isset($_POST['error'])){?>hide<?}?>">
                    <a class="close" data-dismiss="alert" href="#">&times;</a>Неверный Логин или Пароль!
                </div>
                <?=form::text('email', array('class'=>'span3', 'placeholder'=>'Логин'))?>
                <?=form::password('password', array('class'=>'span3', 'placeholder'=>'Пароль'))?>
                <div class="clearfix"></div>
                <label class="checkbox">
                    <?=form::checkbox('outsider')?> Чужой компьютер
                </label>
                <button class="btn-info btn" type="submit">Войти</button>
            </form>
            <div><span class="fs-mini">Development of AHead of Technologies.</span></div>
        </div>
    </div>
</div>
</body>
</html>