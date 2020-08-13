<?php
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
  <tr align="center">
    <td colspan="3" class="menu"><a href="/">Главная</a> | <a href="info.php">Информация</a> | <a href="shop/items/">Магазин</a> | <a href="howpay.php">Доставка и оплата</a> |  <a href="contacts.php">Контакты</a> | <a href="articles.php">СЕ Статьи</a>
<?php
$basket = $_COOKIE['basket'];
	if(!$basket) $basket = $_SESSION['basket'];
$list = $_COOKIE['list'];
	if(!$list) $list = $_SESSION['list'];
	
	if($basket||$list){
	$items = 0;
		if($basket) $items += count(unserialize($basket));
		if($list) $items += count(unserialize($list));
	echo ' | <a href="purchase.php"><span style="color: #FFFF00;">Корзина ['.$items.']</span></a>';
	}
?>
	</td>
  </tr>
  <tr>
         <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="5" style="border-top:1px solid #FF0000; border-left:1px solid #FF0000; border-right:1px solid #FF0000; border-bottom:1px solid #FF0000;">
          <tr>
<!--            <td class="text"><p align="center">ВНИМАНИЕ! ПРОДАЖА ПРОДУКТОВ ОСУЩЕСТВЛЯЕТСЯ ТАКЖЕ И НА НАШЕЙ ТОРГОВОЙ ТОЧКЕ (<a href="images/map.jpg" target="_blank" title="Схема прохода к торговой точке Ирий Сад (СПб)">СПБ, СЕННОЙ РЫНОК, ПАВИЛЬОН, МЕСТО №104</a>)!</p></td>
-->
<td>&nbsp;</td>
          </tr>
        </table></td>
  </tr>