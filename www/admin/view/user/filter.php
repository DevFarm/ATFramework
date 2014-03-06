<script type="text/javascript">

    $(function(){
        var speech_fields = '#id, #email, #name, #surname, #middle_name';

        $(speech_fields).bind('speechchange', function(){
            $('#main-filter').submit();
        })
            .bind('webkitspeechchange', function(){
                $('#main-filter').submit();
            });
    });

</script>

<form id="main-filter" class="form-horizontal" accept-charset="utf-8" method="get" action="/user/search/">
    <div class="well filter">
        <div class="row-fluid">

            <div class="btn-group" data-toggle="buttons-radio">
                <button type="button" value="ru" class="btn btn-info active" onclick="$('html').attr({lang: 'ru'})">ru</button>
                <button type="button" value="en" class="btn btn-info " onclick="$('html').attr({lang: 'en'})">en</button>
            </div>

            <div class="left well-small">
                <div><label class="bold">ID</label></div>
                <?=form::text('id', array('placeholder'=>'Укажите ID', 'id'=>'id', 'class'=>'input-small', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Email</label></div>
                <?=form::text('email', array('placeholder'=>'Укажите Email', 'id'=>'email', 'class'=>'input-large', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Имя</label></div>
                <?=form::text('name', array('placeholder'=>'Укажите Имя', 'id'=>'name', 'class'=>'input-large', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Фамилия</label></div>
                <?=form::text('surname', array('placeholder'=>'Укажите Фамилию', 'id'=>'surname', 'class'=>'input-large', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Отчество</label></div>
                <?=form::text('middle_name', array('placeholder'=>'Укажите Отчество', 'id'=>'middle_name', 'class'=>'input-large', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Удаленные</label></div>
                <?=form::checkbox('del');?> - показывать
            </div>

        </div>
        <div class="well-small">
            <button type="submit" class="btn btn-inverse"><i class="icon-search icon-white"></i> Поиск</button>
            <button type="reset" class="btn btn-inverse"><i class="icon-remove icon-white"></i> Очистить</button>
        </div>
    </div>
</form>