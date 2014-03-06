<div>
    <form class="form-horizontal" method="get" action="/log/search/">
        <div class="well filter">
            <div class="row-fluid">

                <div class="left well-small">
                    <div><label class="bold">ID лога</label></div>
                    <?=form::text('id', array('placeholder'=>'Укажите ID', 'class'=>'input-small', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
                </div>

                <div class="left well-small">
                    <div><label class="bold">Категория</label></div>
                    <?=form::text('category', array('placeholder'=>'Укажите категория', 'class'=>'input-xlarge', 'x-webkit-speech'=>'', 'speech'=>'', 'autocomplete'=>'off'));?>
                </div>

                <div class="left well-small">
                    <div><label class="bold">Дата</label></div>
                    <div class="input-append date" id="from" data-date="<?=date('d.m.Y')?>" data-date-format="dd-mm-yyyy">
                        с <input class="input-small" type="text" value="<?=date('d.m.Y')?>" readonly=""><span class="add-on"><i class="icon-calendar"></i></span>
                    </div>

                    <div class="input-append date" id="to" data-date="<?=date('d.m.Y')?>" data-date-format="dd-mm-yyyy">
                        по <input class="input-small" type="text" value="<?=date('d.m.Y')?>" readonly=""><span class="add-on"><i class="icon-calendar"></i></span>
                    </div>
                </div>

            </div>
            <div class="well-small">
                <button type="submit" class="btn btn-inverse"><i class="icon-search icon-white"></i> Поиск</button>
                <button type="reset" class="btn btn-inverse"><i class="icon-remove icon-white"></i> Очистить</button>
            </div>
        </div>
    </form>
</div>