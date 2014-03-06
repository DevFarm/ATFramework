<script type="text/javascript">

    $(function(){
        var speech_fields = '#ur-id, #a-name, #c-description, #ac-description';

        $(speech_fields).bind('speechchange', function(){
            if($(this).val() == 'пусто' || $(this).val() == 'очистить')
            {
                $(this).val('');
            }

            $('#main-filter').submit();
        })
            .bind('webkitspeechchange', function(e){
                if($(this).val() == 'пусто' || $(this).val() == 'очистить')
                {
                    $(this).val('');
                }

                $('#main-filter').submit();
            });
    });

</script>

<form id="main-filter" class="form-horizontal" accept-charset="utf-8" method="get" action="/user_rule/search/">
    <div class="well filter">
        <div class="row-fluid">

            <div class="left well-small">
                <div><label class="bold">ID правила</label></div>
                <?=form::text('ur-id', array('id'=>'ur-id', 'placeholder'=>'Укажите ID', 'class'=>'input-small', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Приложение</label></div>
                <?=form::text('a-name', array('id'=>'a-name','placeholder'=>'Укажите приложение', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Контроллер</label></div>
                <?=form::text('c-description', array('id'=>'c-description','placeholder'=>'Укажите контроллер', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Действие</label></div>
                <?=form::text('ac-description', array('id'=>'ac-description','placeholder'=>'Укажите действие', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Доступ</label></div>
                <?=form::select('ur-access', array(null=>'Все', 0=>'Запрещен', 1=>'Разрешен'), array());?>
            </div>

        </div>
        <div class="well-small">
            <button type="submit" class="btn btn-inverse"><i class="icon-search icon-white"></i> Поиск</button>
            <button type="reset" class="btn btn-inverse"><i class="icon-remove icon-white"></i> Очистить</button>
        </div>
    </div>
</form>