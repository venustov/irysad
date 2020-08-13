<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
$title = 'Генерация прайс-листа для группы "вКонтакте"';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">

include ('../supple/server_root.php');
require ('../supple/auth_db.php');

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$price_stena = 'ОРИЕНТИРОВОЧНЫЙ(*) ассортимент на '.date("d.m.Y").':
(Розничная цена / цена для членов кооператива и держателей клубной карты)

';
$str_sql_query = 'SELECT * FROM items WHERE city="'.$_SESSION['city'].'" AND status<3 and name LIKE "%карта" ORDER BY name DESC';
$result = @mysql_query($str_sql_query);
$number = @mysql_num_rows($result);
	if($number){
	$i=0;
		while($i<$number){
		$name = mysql_result($result,$i,"name");
		$price_1 = mysql_result($result,$i,"price_1");
		setlocale(LC_CTYPE, 'ru_RU.cp1251');
		$price_stena.='* '.strtoupper($name).' - '.$price_1.' рублей
';
		$i++;
		}
	$price_stena.='
';
	}
$str_sql_query = 'SELECT * FROM items WHERE city="'.$_SESSION['city'].'" AND status<3 and name NOT LIKE "%карта" ORDER BY category DESC, name ASC';
$result = @mysql_query($str_sql_query);
$number = @mysql_num_rows($result);
	if($number){
	$i=0;
		while($i<$number){
		$name = mysql_result($result,$i,"name");
		$price_1 = mysql_result($result,$i,"price_1");
		$price_2 = mysql_result($result,$i,"price_2");
		$sale = mysql_result($result,$i,"sale");
		$ed_izmer = mysql_result($result,$i,"ed_izmer");
			if($ed_izmer == 1) $ed_izmer = 'кг';
			elseif($ed_izmer == 2) $ed_izmer = '100 гр';
			elseif($ed_izmer == 3) $ed_izmer = 'уп.';
			elseif($ed_izmer == 4) $ed_izmer = 'шт.';
			else $ed_izmer = 0;
		$price_stena.='* '.$name.' - ';
			if(!$sale) $price_stena.=$price_2.'/';
		$price_stena.=$price_1.' рублей';
			if($ed_izmer) $price_stena.=' за '.$ed_izmer;
			if($sale) $price_stena.=' - РАСПРОДАЖА!';
		$price_stena.='
';
		$i++;
		}
	$price_stena.='
(*) Указаны ориентировочные цены (предыдущего дня). Узнать фактические цены и наличие конкретного продукта можно по тел.: 8-965-797-58-24 с 9-00 до 20-00.

Санкт-Петербург, Сенной рынок, павильон, место №104а';
	}

$str_sql_query = 'SELECT * FROM items WHERE city="'.$_SESSION['city'].'" AND status<3 ORDER BY category ASC, name ASC';
$result = @mysql_query($str_sql_query);
$number = @mysql_num_rows($result);

	if($number){
	$i=0;
	$cat=0;
		while($i<$number){
		$category = mysql_result($result,$i,"category");
			if($category!=$cat){
				if($i) $price.='|}
}}
';
			$price.='{{Hider|';
				if($category==1) $price.='ФРУКТЫ, ОВОЩИ
';
				elseif($category==2) $price.='ОРЕХИ, СЕМЕНА, ЗЛАКИ
';
//				elseif($category==3) $price.='ЗЕЛЕНЬ, СУХОФРУКТЫ, ДРУГОЕ
				elseif($category==3) $price.='ДРУГОЕ
';
			$price.='{|
|-
! Наименование !! Страна !! Кооп.цена !! Розн.цена !! Примечание
';
			}
		$id = mysql_result($result,$i,"id");
		$name = str_replace('"','',mysql_result($result,$i,"name"));
		$is_from = mysql_result($result,$i,"is_from");
		$urozhay = mysql_result($result,$i,"urozhay");
		$zakup = mysql_result($result,$i,"price_1");
		$rozn = mysql_result($result,$i,"price_2");
		$sale = mysql_result($result,$i,"sale");
			if($sale){
				$rozn_price = ceil(0.9*$zakup);
				$koop_price = ceil(0.9*$zakup);
			}
			else{
				$rozn_price = $rozn;
				$koop_price = $zakup;
			}
		$ed_izmer = mysql_result($result,$i,"ed_izmer");
			if($ed_izmer == 1) $ed_izmer = 'кг';
			elseif($ed_izmer == 2) $ed_izmer = '100 гр';
			elseif($ed_izmer == 3) $ed_izmer = 'уп.';
			elseif($ed_izmer == 4) $ed_izmer = 'шт.';
			else $ed_izmer = 0;
//		$lot = mysql_result($result,$i,"lot");
		$price.='|-
| ['.$SERVER_ROOT.'shop/u'.$_SESSION['logged_user'].'/'.$id.'.html|'.$name.'] || ';
			if($is_from&&($is_from!='Не известно')) $price.=$is_from;
		$price.=' || '.$koop_price.' р';
			if($ed_izmer) $price.='/'.$ed_izmer;
		$price.=' || '.$rozn_price.' р';
			if($ed_izmer) $price.='/'.$ed_izmer;
		$price.=' || ';
			if($rozn&&($rozn < $zakup))	$price.='РАСПРОДАЖА';
		$price.='
';
		$cat = $category;
		$i++;
		}
	$price.='|}
}}
';
	}
mysql_close($link);
?>
<?php
include ('templates/header.tpl');
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Внесение нового заказа:</div>
</div>
<div id="data">
 <table width="600" cellspacing="1">
  <thead>
   <tr>
     <td width="50%">Прайс лист для сообщения на стену вКонтакте (скопируйте текст из этой формы)</td>
     </tr>
  </thead>
  <tbody>
   <tr align="center" class="r1">
     <td><textarea name="textarea" cols="100" rows="25"><?php echo $price_stena; ?></textarea></td>
	 </tr>
  </tbody>
 </table>
 <table width="600" cellspacing="1">
  <thead>
   <tr>
     <td width="50%">Прайс лист для страницы вКонтакте (скопируйте текст из этой формы)</td>
     </tr>
  </thead>
  <tbody>
   <tr align="center" class="r1">
     <td><textarea name="textarea" cols="100" rows="25"><?php echo $price; ?></textarea></td>
	 </tr>
  </tbody>
 </table>
</div>
<?php
include ('templates/footer.tpl');
?>
