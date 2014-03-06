<script type="text/javascript">

    $(function(){
        var speech_fields = '#name, #description';

        $(speech_fields).bind('speechchange', function(){
            if($(this).val() == 'пусто' || $(this).val() == 'очистить')
            {
                $(this).val('');
            }

            $('#main-filter').submit();
        })
            .bind('webkitspeechchange', function(){
                if($(this).val() == 'пусто' || $(this).val() == 'очистить')
                {
                    $(this).val('');
                }

                $('#main-filter').submit();
            });
    });

</script>

<form id="main-filter" class="form-horizontal" accept-charset="utf-8" method="get" action="/action/search/">
    <div class="well filter">
        <div class="row-fluid">

            <div class="btn-group" data-toggle="buttons-radio">
                <button type="button" value="ru" class="btn btn-info active" onclick="$('html').attr({lang: 'ru'})">ru</button>
                <button type="button" value="en" class="btn btn-info " onclick="$('html').attr({lang: 'en'})">en</button>
            </div>

            <div class="left well-small">
                <div><label class="bold">Название</label></div>
                <?=form::text('name', array('placeholder'=>'Название действия', 'id'=>'name', 'class'=>'input-large', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Описание</label></div>
                <?=form::text('description', array('placeholder'=>'Описание действия', 'id'=>'description', 'class'=>'input-large', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
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