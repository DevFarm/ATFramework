<? $flag = 1 ?>
<div class="page-header">
    <h1>Рабочий стол <small>- выводит все контроллеры и их действия.</small></h1>
</div>

<div class="accordion" id="accordion2">
    <?
    foreach ($search_controller as $controller) {
        ?>
        <div class="accordion-group">
            <div class="accordion-heading" style="background-color: #eee">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?=$controller['name']?>" style="padding: 8px 0px 0px 15px">
                    <p class="fc-blacklight bold">
                        <i class="icon-<?=(!empty($controller['icon'])?$controller['icon']:'book')?>"></i>&nbsp;Пользователи<br/>
                    </p>
                </a>
                <blockquote style="margin: 0"><small><?=$controller['description']?></small></blockquote>
            </div>
            <div id="collapse_<?=$controller['name']?>" class="accordion-body collapse <?if($flag){ $flag = 0;?>in<?}?>">
                <div class="accordion-inner">

                    <?
                    if (!empty($controller['actions'])) {
                        foreach ($controller['actions'] as $action) {
                            switch($action['name']) {
                                case 'index':
                                case 'add':
                                case 'search':
                                {
                                    ?>
                                    <span style="display:inline-block; height: 40px; margin: 2px" class="label label-info">
                                        <i class="icon-<?=(!empty($action['icon'])?$action['icon']:'play-circle')?> icon-white"></i>&nbsp;<?=$action['description']?><br/>
                                        <a href="http://<?=ATCore::$serv->http_host?>/<?=$controller['name']?>/<?=$action['name']?>" class="fc-white"><?=ATCore::$serv->http_host?>/<?=$controller['name']?>/<?=$action['name']?></a>
                                    </span>
                                    <?
                                    break;
                                }
                                default:
                                {
                                    ?>
                                    <span style="display:inline-block; height: 40px; margin: 2px" class="label label-inverse">
                                        <i class="icon-<?=(!empty($action['icon'])?$action['icon']:'play-circle')?> icon-white"></i>&nbsp;<?=$action['description']?><br/>
                                        <?=ATCore::$serv->http_host?>/<?=$controller['name']?>/<?=$action['name']?> <abbr title="Нужен параметр">?</abbr>
                                    </span>
                                    <?
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?
    }
    ?>
</div>