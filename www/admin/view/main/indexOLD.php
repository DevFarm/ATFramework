<ul class="nav nav-tabs">
    <li class="active">
        <a href="#">Главная</a>
    </li>
</ul>



<?
payment::$login    = 'zoovet_ru';
payment::$password = 'paymayhappy2000_twenty';
payment::$type     = 'robokassa';
payment::init();

if($_POST)
{
    $data = array(
        'OutSum'       => ($_POST['price'] * $_POST['count']),
        'InvId'        => 12,
        'Desc'         => $_POST['description'],
        'Shp_item'     => 3,
        'IncCurrLabel' => 'RapidaOceanSvyaznoyR',
        'Culture'      => 'ru'
    );

    payment::process($data);
    debug::vardump($_POST, 'post');
}
?>
<form method="post">
    <div>Что покупаем? <?=form::text('description')?></div>
    <div>Почем бирем? <?=form::text('price')?> руб.</div>
    <div>В каком кол-ве? <?=form::text('count')?> шт.</div>
    <button>купить</button>
</form>

<?if($_POST){?>
    <p class="bold">Итого к оплате: <?=payment::$data['OutSum']?> руб.</p>


    <form action="https://merchant.roboxchange.com/Index.aspx" method="POST">
        <input type="hidden" name="MrchLogin" value="<?=payment::$data['MrchLogin']?>">
        <input type="hidden" name="OutSum" value="<?=payment::$data['OutSum']?>">
        <input type="hidden" name="InvId" value="<?=payment::$data['InvId']?>">
        <input type="hidden" name="Desc" value="<?=payment::$data['Desc']?>">
        <input type="hidden" name="SignatureValue" value="<?=payment::$data['SignatureValue']?>">
        <input type="hidden" name="Shp_item" value="<?=payment::$data['Shp_item']?>">
        <input type="hidden" name="IncCurrLabel" value="<?=payment::$data['IncCurrLabel']?>">
        <input type="hidden" name="Culture" value="<?=payment::$data['Culture']?>">

        <input type="hidden" name="Email" value="sumatorhak@gmail.com">

        <input type="submit" value="Отмена"><input type="submit" value="Далее">
    </form>
<?}?>