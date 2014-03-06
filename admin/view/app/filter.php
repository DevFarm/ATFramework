<form id="main-filter" class="form-horizontal" accept-charset="utf-8" method="get" action="/app/search/">
    <div class="well filter">
        <div class="row-fluid">

            <div class="left well-small">
                <div><label class="bold">ID приложения</label></div>
                <?=form::text('id', array('id'=>'id', 'placeholder'=>'Укажите ID', 'class'=>'input-small', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Название</label></div>
                <?=form::text('name', array('id'=>'name','placeholder'=>'Укажите название', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">API ключ</label></div>
                <?=form::text('api_key', array('id'=>'api_key','placeholder'=>'Укажите API ключ', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
            </div>

            <div class="left well-small">
                <div><label class="bold">Последний доступ</label></div>
                <div class="input-append date" id="from" data-date="<?=date('d.m.Y')?>" data-date-format="dd-mm-yyyy">
                    с <input class="input-small" type="text" value="<?=date('d.m.Y')?>" readonly=""><span class="add-on"><i class="icon-calendar"></i></span>
                </div>

                <div class="input-append date" id="to" data-date="<?=date('d.m.Y')?>" data-date-format="dd-mm-yyyy">
                    по <input class="input-small" type="text" value="<?=date('d.m.Y')?>" readonly=""><span class="add-on"><i class="icon-calendar"></i></span>
                </div>
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