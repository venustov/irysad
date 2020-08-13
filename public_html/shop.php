<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$prev = $protection->get['prev'];

	if($_COOKIE['referer']) $_SESSION['referer'] = $_COOKIE['referer'];
	elseif($protection->get['referer']){
	$_SESSION['referer'] = $protection->get['referer'];
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $protection->get['referer'], $end_cookie, '/');
	}
//echo $referer;

include ('supple/server_root.php');
require ('supple/auth_db.php');

if(!isset($_SESSION['city'])){
	if ($_COOKIE['city']){
	$str_sql_query = 'SELECT name, icq, phone, admin, country FROM cities WHERE name = "'.$_COOKIE['city'].'"';
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if(!$number){
		$str_sql_query = "SELECT name, icq, phone, admin, country FROM cities WHERE capital = '1'";
		$result = mysql_query($str_sql_query) or die(mysql_error());
		$number = mysql_num_rows($result);
			if($number){
			$i = 0;
			
			$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
			setcookie('city', mysql_result($result,$i,"name"), $end_cookie, '/');
		
			$_SESSION['city'] = mysql_result($result,$i,"name");
			$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
			$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
				if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
			$_SESSION['country'] = mysql_result($result,$i,"country");
			}
		}
		else{
		$i = 0;
		
		$_SESSION['city'] = $_COOKIE['city'];
		$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
		$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
			if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
		$_SESSION['country'] = mysql_result($result,$i,"country");
		}
	}
	else {
	$str_sql_query = "SELECT name, icq, phone, admin, country FROM cities WHERE capital = '1'";
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
		
		$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
		setcookie('city', mysql_result($result,$i,"name"), $end_cookie, '/');
		
		$_SESSION['city'] = mysql_result($result,$i,"name");
		$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
		$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
			if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
		$_SESSION['country'] = mysql_result($result,$i,"country");
		}
	}
}
function MenuItem($url,$ancor){
	if(ereg('/'.$url,$_SERVER['REQUEST_URI'])) $class = 'mn2';
	else $class = 'mn1';
$str = '<div class="'.$class.'"><a href="'.$url.'">'.$ancor.'</a></div>';
return $str;
}
function ItemCard($id,$name,$is_from,$urozhay,$status,$zakup,$rozn,$sale,$ed_izmer,$lot,$desc,$fold,$fold_img,$img){
$small_photo = $fold.'/photoes/'.$fold_img.'/preview/'.$img.'.jpg';
	if(!file_exists($small_photo)) $small_photo = $fold.'/photoes/no_photo.jpg';
$big_photo = $fold.'/photoes/'.$fold_img.'/'.$img.'.jpg';
	if($ed_izmer == 1) $ed_izmer = 'кг';
	elseif($ed_izmer == 2) $ed_izmer = '100 гр';
	elseif($ed_izmer == 3) $ed_izmer = 'уп.';
	elseif($ed_izmer == 4) $ed_izmer = 'шт.';
	else $ed_izmer = 0;
	
//	if($rozn&&($rozn < $zakup)) $price = $rozn;
//	if(!$sale) $price = $rozn;
//	else $price = $zakup;
	
$str = '<form action="basket.php" method="post" name="purchase"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr align="right">
                <td colspan="2" valign="bottom" style="padding-right:10px; padding-top:10px;"><span class="item_title"><a href="/shop/items/'.$id.'.html" title="подробнее...">'.$name.'</a></span></td>
				<td>';
$x=0;
$basket = $_COOKIE['basket'];
	if(!$basket) $basket = $_SESSION['basket'];
$list = $_COOKIE['list'];
	if(!$list) $list = $_SESSION['list'];
	
	if($basket){
	$items = unserialize($basket);
		foreach($items as $key => $value){
			if($value['id'] == $id){
			$x=1;
			break;
			}
		}
	}
	if(!$x&&$list){
	$items = unserialize($list);
		foreach($items as $key => $value){
			if($value['id'] == $id){
			$x=2;
			break;
			}
		}
	}
	if(!$x) $str .= '&nbsp;';
	elseif($x == 1) $str .= '<a href="'.$_SERVER['REQUEST_URI'].'#basket"><img src="images/shopping_cart.png" alt="карта" width="24" height="24" border="0"></a>';
	elseif($x == 2) $str .= '<a href="'.$_SERVER['REQUEST_URI'].'#list_yak"><img src="images/database.png" alt="база" width="24" height="24" border="0"></a>';
$str .= '</td>
              </tr>
              <tr>
                <td width="30%" rowspan="6" valign="middle" style="padding-left:20px">';
	if(file_exists($big_photo)&&file_exists($small_photo)){
	$size = getimagesize($big_photo);
	$str .= '<a href="photo/'.$fold_img.'/'.$img.'" target="_blank" alt="'.$name.'" title="Увеличить" onClick="popupWin = window.open(this.href, \'main_photo\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,left=339,top=140,width='.$size[0].',height='.$size[1].'\'); popupWin.focus(); return false;">';
	}
$size = getimagesize($small_photo);
$str .= '<img src="'.$small_photo.'" alt="'.$name.'" width="'.$size[0].'" height="'.$size[1].'" border="0">';
	if(file_exists($big_photo)&&file_exists($small_photo)) $str .= '</a>';
$str .= '</td>
                <td align="right" valign="top" style="padding-right:10px; padding-top:20px;">';
	if($is_from != 'Не известно') $str .= '<a href="shop.php?is_from='.$is_from.'&prev=items" title="найти все продукты с таким происхождением...">'.$is_from.'</a>';
	else $str .= '&nbsp;';
$str .= '</td>
                <td rowspan="6" align="right" valign="top"><img src="images/shtrih.jpg" alt="штрих-код" width="24" height="80"></td>
              </tr>
              <tr>
                <td align="right" valign="bottom" style="padding-right:10px">';
	if($urozhay){
	$str .= '<a href="shop/urozhay/'.$urozhay.'/prev/items" title="найти все продукты урожая этого года...">Урожай '.$urozhay.' года</a>';
	}
	else $str .= '&nbsp;';
$str .= '</td>
              </tr>
              <tr>
                <td align="right" valign="bottom" style="padding-right:10px"><a href="shop/status/'.$status.'/prev/items" title="найти все такие продукты...">';
	if($status == 1) $str .= 'Есть в наличии';
	elseif($status == 2) $str .= 'Количество ограничено';
	elseif($status == 3) $str .= 'Ожидается скоро';
	else $str .= 'Временно отсутствует';
$str .= '</a></td>
              </tr>
			  <tr>
                <td align="right" valign="bottom" style="padding-right:10px">Минимальный заказ - '.$lot.' '.$ed_izmer.'</td>
              </tr>
              <tr>
                <td align="right" valign="bottom" style="padding-right:10px; padding-top:30px">';
	if($sale){
		if(!$_SESSION['logged_status']) $old_price = $rozn;
		else $old_price = 1.1*$zakup;
	$str .= '<span class="old_price">'.$old_price.' р';
		if($ed_izmer) $str .= '/'.$ed_izmer;
	$str .= '</span>';
	}
	else $str .= '&nbsp;';
$str .= '</td>
              </tr>
              <tr>
                <td align="right" valign="bottom" style="padding-right:10px"><span class="price">';

/*	В случае, если цены будет вбивать закупщик и расчитываться они будут от закупочной цены:
	if($sale) $akt_price = $zakup;
	elseif(!$_SESSION['logged_status']) $akt_price = $rozn;
	else $akt_price = ceil(1.1*$zakup);
*/

	if($sale) $akt_price = ceil(0.9*$zakup);
	elseif(!$_SESSION['logged_status']) $akt_price = $rozn;
	else $akt_price = $zakup;
	
$str .= $akt_price.' р';
	if($ed_izmer) $str .= '/'.$ed_izmer;
$str .= '</span></td>
              </tr>
              <tr>
                <td align="center" valign="bottom" class="sale" style="padding-left:20px">';
	if($sale) $str .= 'УЦЕНКА!';
	else $str .= '&nbsp;';
$str .= '</td>
                <td align="right" valign="bottom" style="padding-right:10px; padding-bottom:10px">';
	if(!$x){
		if($status<=2) $str .= '<input type="submit" name="add_basket" value="добавить в корзину"><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="name" value="'.$name.'"><input type="hidden" name="ed_izmer" value="'.$ed_izmer.'"><input type="hidden" name="lot" value="'.$lot.'"><input type="hidden" name="price" value="'.$akt_price.'">';
		else $str .= '<input type="submit" name="add_list" value="в лист ожидания"><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="name" value="'.$name.'"><input type="hidden" name="ed_izmer" value="'.$ed_izmer.'"><input type="hidden" name="lot" value="'.$lot.'"><input type="hidden" name="price" value="'.$akt_price.'">';
	}
	elseif($x == 1) $str .= '<a href="basket.php?id='.$id.'&del_from=basket">удалить из корзины</a>';
	elseif($x == 2) $str .= '<a href="basket.php?id='.$id.'&del_from=list">удалить из листа ожидания</a>';
$str .= '</td>
              </tr>
            </table></form>';
return $str;
}
function ItemList($class,$zakup,$rozn,$sale,$ed_izmer,$id,$name,$is_from,$lot,$urozhay,$status){
	if($ed_izmer == 1) $ed_izmer = 'кг';
	elseif($ed_izmer == 2) $ed_izmer = '100 гр';
	elseif($ed_izmer == 3) $ed_izmer = 'уп.';
	elseif($ed_izmer == 4) $ed_izmer = 'шт.';
	else $ed_izmer = 0;
	
//	if($rozn&&($rozn < $zakup)) $price = $rozn;
/*	В случае, если цены будет вбивать закупщик и расчитываться они будут от закупочной цены:
	if($sale) $akt_price = $zakup;
	elseif(!$_SESSION['logged_status']) $akt_price = $rozn;
	else $akt_price = ceil(1.1*$zakup);
*/

	if($sale) $akt_price = ceil(0.9*$zakup);
	elseif(!$_SESSION['logged_status']) $akt_price = $rozn;
	else $akt_price = $zakup;
	
$name = str_replace('"','&quot;',$name);

$str = '<tr class="'.$class.'"><td align="center">';
	if($status<=2) $str .= '<input type="checkbox" name="check_basket[]" value="'.$id.'|'.$name.'|'.$ed_izmer.'|'.$akt_price.'">';
	else $str .= '&nbsp;';
$str .= '</td>
     <td align="center"><div style="color:#000000;">';

$str .= $akt_price.' р';
	if($ed_izmer) $str .= '/'.$ed_izmer;
$str .= '</div></td>
     <td align="left" style="padding-left:20px;"><a href="/shop/list/'.$id.'.html" title="подробнее...">'.$name.'</a>';
	if($is_from||$urozhay||$status){
	$str .= ' (';
	$j=0;
		if($is_from&&($is_from != 'Не известно')){
		$str .= '<a href="shop.php?is_from='.$is_from.'&prev=list" title="найти все продукты с таким происхождением...">'.$is_from.'</a>';
		$j++;
		}
		if($j&&($urozhay||$status)) $str .= '; ';
	$j=0;
		if($urozhay){
		$str .= '<a href="shop.php?urozhay='.$urozhay.'&prev=list" title="найти все продукты урожая этого года...">Урожай '.$urozhay.' года</a>';
		$j++;
		}
		if($j&&$status) $str .= '; ';
		if($status){
		$str .= '<a href="shop.php?status='.$status.'&prev=list" title="найти все такие продукты...">';
			if($status == 1) $str .= 'Есть в наличии';
			elseif($status == 2) $str .= 'Количество ограничено';
			elseif($status == 3) $str .= 'Ожидается скоро';
			else $str .= 'Временно отсутствует';
		$str .= '</a>';
		}
	$str .= ')';
	}
$str .= '</td><td align="center">';
	if($status>2) $str .= '<input type="checkbox" name="check_list[]" value="'.$id.'|'.$name.'|'.$ed_izmer.'|'.$lot.'|'.$act_price.'">';
	else $str .= '&nbsp;';
$str .= '</td>
	 <td align="center">';
	if($sale) $str .= '<div style="color:#FF0000;"><b>УЦЕНКА!</b></div>';
	else $str .= '&nbsp;';
$str .= '</td>
   </tr>';
return $str;
}
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
<?php
include ('templates/header.tpl');
?>
<title>Ирий Сад :: магазин фрукты овощи. Доставка в городе <?php echo $_SESSION['city']; ?>.</title>
</head>

<body>
<table width="1000" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_left"><h1>Потребительский кооператив &quot;Ирий Сад&quot;</h1>
	<h2>качественные продукты от сыроедов</h2>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo 'Тел.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">выбрать другой город</a></p></td>
    <td width="200"><img src="images/head2_02.jpg" alt="Ирий Сад - магазин для сыроедов" width="200" height="150"></td>
    <td width="400" align="right" valign="bottom" class="head_right">
<?php
	if(!isset($_SESSION['logged_user'])){
	include ('templates/enter_panel.tpl');
	}
	else include ('templates/exit_panel.tpl');
?>
	</td>
  </tr>
<?php
include ('templates/menu.tpl');
?>
  <tr>
    <td colspan="3" class="menu"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
          <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu">
<?php
include ('templates/shop_menu.tpl');
?>
<br>
<form name="search" method="post" action=""><table bgcolor="#7D94C0">
    <tr>
      <td><img src="images/search.png" alt="Поиск" width="16" height="16" border="0"></td>
      <td width="100%" align="center" bgcolor="#E9F0FB" style="color:#000000;">Быстрый поиск:</td>
    </tr>
    <tr>
      <td colspan="2" align="right"><select name="search_cat" style="width:128px;">
            <option value="0">Не важно</option>
            <option value="1" selected>Фрукты, овощи</option>
            <option value="2">Семена, орехи</option>
            <option value="3">Зелень, другое</option>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><select name="search_urozhay" style="width:128px;">
            <option value="0">Не важно</option>
            <option value="1" selected>Новый урожай</option>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><select name="search_nalichie" style="width:128px;">
            <option value="0">Не важно</option>
            <option value="1" selected>Есть в наличии</option>
            <option value="2">Нет в наличии</option>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><select name="search_from" style="width:128px;">
            <option value="0">Не важно</option>
            <option value="1" selected>Отечественные</option>
            <option value="2">Импортные</option>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><select name="search_sale" style="width:128px;">
            <option value="0">Не важно</option>
            <option value="1" selected>Уцененные</option>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><input name="search" type="submit" id="search" value="Применить" style="width:128px; height: 20px; padding-bottom: 15px;"></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><input name="clear" type="submit" id="reset" value="Сброс" style="width:128px; height: 20px; padding-bottom: 15px;"></td>
    </tr>
  </table>
</form>
<?php
	if($basket||$list){
	echo '<br>
  <table width="100%" bgcolor="#7D94C0">';
		if($basket){
		echo '<tr>
      <td><img src="images/basket.png" alt="Ваша корзина" width="16" height="16" border="0" id="basket"></td>
      <td width="100%" align="center" bgcolor="#E9F0FB" style="color:#000000;">Ваша корзина:</td>
    </tr>
	';
		$items = unserialize($basket);
			foreach($items as $key => $value){
			$name = str_replace('"','&quot;',$value['name']);
			echo '<tr>
      <td><a href="basket.php?id='.$value['id'].'&del_from=basket"><img src="images/remove.png" width="16" height="16" border="0" alt="Удалить из корзины" title="Удалить из корзины"></a></td>
      <td width="100%" align="left" style="color:#000000;"><a href="'.$_SERVER['REQUEST_URI'].'#'.$value['id'].'">'.$value['name'].'</a></td>
    </tr>';
			}
		}
		if($list){
		echo '<tr>
      <td><img src="images/list.png" alt="Ваш лист ожидания" width="16" height="16" border="0" id="list_yak"></td>
      <td width="100%" align="center" bgcolor="#E9F0FB" style="color:#000000;">Лист ожидания:</td>
    </tr>
	';
		$items = unserialize($list);
			foreach($items as $key => $value){
			$name = str_replace('"','&quot;',$value['name']);
			echo '<tr>
      <td><a href="basket.php?id='.$value['id'].'&del_from=list"><img src="images/remove_from_list.png" alt="удалить из листа ожидания" width="16" height="16" border="0" alt="Удалить из листа ожидания" title="Удалить из листа ожидания"></a></td>
      <td width="100%" align="left" style="color:#000000;"><a href="'.$_SERVER['REQUEST_URI'].'#'.$value['id'].'">'.$value['name'].'</a></td>
    </tr>';
			}
		}
		echo '
    <tr>
      <td align="right"><a href="purchase.php"><img src="images/accept.png" alt="Сделать заказ (уточнить количество) и (или) подтвердить ваш лист ожидания" title="Сделать заказ (уточнить количество) и (или) подтвердить ваш лист ожидания" width="16" height="16" border="0"></a></td>
      <td width="100%" align="left" bgcolor="#E9F0FB"><a href="purchase.php" title="Сделать заказ (уточнить количество) и (или) подтвердить ваш лист ожидания">Подтвердить</a></td>
    </tr>
  </table>';
	}
?>
        </td>
        <td width="88%" valign="top" style="border-left:1px solid #FFFFFF;">
<?php
$str_sql_query = 'SELECT * FROM items WHERE city="'.$_SESSION['city'].'"';

	if($protection->post['search']){
	
		if($protection->post['search_cat'] == 1) $str_sql_query .= ' AND category = "1"';
		elseif($protection->post['search_cat'] == 2) $str_sql_query .= ' AND category = "2"';
		elseif($protection->post['search_cat'] == 3) $str_sql_query .= ' AND category = "3"';

		if($protection->post['search_urozhay'] == 1){
		$year = date("Y");
			if(date("n")<=6){
			$str_sql_query .= ' AND urozhay = "'.$year.'" OR (urozhay="'.($year-1).'" AND month>"'.(date("n")+6).'") OR (urozhay="'.$year.'" AND month = "0")';
			}
			else $str_sql_query .= ' AND (urozhay="'.($year).'" AND month>"'.(date("n")-6).'") OR (urozhay="'.$year.'" AND month = "0")';
		}

		if($protection->post['search_nalichie'] == 1) $str_sql_query .= ' AND status < "3"';
		elseif($protection->post['search_nalichie'] == 2) $str_sql_query .= ' AND status >= "3"';
		
		if($protection->post['search_from'] == 1) $str_sql_query .= ' AND is_from LIKE "Россия%"';
		elseif($protection->post['search_from'] == 2) $str_sql_query .= ' AND is_from NOT LIKE "Россия%"';
	
		if($protection->post['search_sale'] == 1) $str_sql_query .= ' AND price_2 > "0" AND price_2 < price_1';
	}
	elseif($protection->get['is_from']&&(!$protection->post['clear'])) $str_sql_query .= ' AND is_from = "'.$protection->get['is_from'].'"';
	elseif($protection->get['urozhay']&&(!$protection->post['clear'])) $str_sql_query .= ' AND urozhay = "'.$protection->get['urozhay'].'"';
	elseif($protection->get['status']&&(!$protection->post['clear'])) $str_sql_query .= ' AND status = "'.$protection->get['status'].'"';

$str_sql_query .= ' ORDER BY status ASC, name ASC';
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if($number){
		if($prev == 'items'){
		echo '<table width="100%"  border="0" cellspacing="10" cellpadding="0">';
		$i = 0;
		$m = 2;	//	Количество столбцов
			while($i < $number){
			$j = 1;
				while($j <= $m && $i < $number){
				
				$id = mysql_result($result,$i,"id");
				$name = str_replace('"','&quot;',mysql_result($result,$i,"name"));
				$is_from = mysql_result($result,$i,"is_from");
				$urozhay = mysql_result($result,$i,"urozhay");
				$desc = mysql_result($result,$i,"description");
				$zakup = mysql_result($result,$i,"price_1");
				$rozn = mysql_result($result,$i,"price_2");
				$sale = mysql_result($result,$i,"sale");
				$status = mysql_result($result,$i,"status");
				$ed_izmer = mysql_result($result,$i,"ed_izmer");
				$lot = mysql_result($result,$i,"lot");
				$fold_img = mysql_result($result,$i,"fold");
					if(ereg("([0-9]{1,}_[0-9]{3,})-",mysql_result($result,$i,"images"),$regs)) $img = $regs[1];
					if(!$img) $img = mysql_result($result,$i,"images");
				
// Здесь надо поставить условие, чтобы уценненые товары, которых нет на складе, не отображались в прайсе
//					if(((!$rozn)||($rozn >= $zakup))||($rozn&&($rozn < $zakup)&&($status < 3))){
					if((!$sale)||(($sale)&&($status < 3))){
/*То есть: показывать, если нет розничной цены или розничная цена больше закупочной.
Либо если есть розничная цена и она меньше закупочной, но только в том случае, когда товар есть в наличии*/
						if($j == 1) echo '<tr>';
					echo '<td width="50%" bgcolor="#';
						if($status<=2) echo 'FFFFFF';
						else echo 'E5E3F9';
					echo '" valign="middle" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;">';
					echo ItemCard($id,$name,$is_from,$urozhay,$status,$zakup,$rozn,$sale,$ed_izmer,$lot,$desc,$img_fold,$fold_img,$img);
					echo '</td>';
					$j++;
						if($j == ($m+1)) echo '</tr>';
					}
// Здесь его закончить
				$img = '';
				$i++;
				}
			}
			if($j == 2) echo '<td width="50%">&nbsp;</td></tr>';
		echo '</table>';
		}
		elseif($prev == 'list'){
		echo '<div id="data"><form action="basket.php" method="post" name="purchase">
 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <thead>
   <tr>
    <td width="8%">В корзину</td>
    <td width="14%">Цена</td>
    <td width="60%">Наименование</td>
    <td width="8%">В лист ожидания</td>
    <td width="10%">Примечание</td>
    </tr>
  </thead>
  <tbody>';
		$i = 0;
			while ($i < $number){
		
			$id = mysql_result($result,$i,"id");
			$name = mysql_result($result,$i,"name");
			$is_from = mysql_result($result,$i,"is_from");
			$urozhay = mysql_result($result,$i,"urozhay");
			$desc = mysql_result($result,$i,"description");
			$zakup = mysql_result($result,$i,"price_1");
			$rozn = mysql_result($result,$i,"price_2");
			$sale = mysql_result($result,$i,"sale");
			$status = mysql_result($result,$i,"status");
			$ed_izmer = mysql_result($result,$i,"ed_izmer");
			$lot = mysql_result($result,$i,"lot");
		
				if((ceil($i/2)*2) != $i) $class = 'r2';
				else $class = 'r1';
				
//				if(((!$rozn)||($rozn >= $zakup))||($rozn&&($rozn < $zakup)&&($status < 3))){
				if((!$sale)||(($sale)&&($status < 3))){
				
				echo ItemList($class,$zakup,$rozn,$sale,$ed_izmer,$id,$name,$is_from,$lot,$urozhay,$status);
				}
			$i++;
			}
		echo '
  </tbody>
 </table>
<input type="submit" name="go_purchase" value="Применить и перейти к оформлению"></form>
</div>';
		}
	}
	else echo '<table width="100%"  border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;">
          <tr>
            <td class="text"><p align="justify">В Базе Данных не обнаружено позиций =(</p>
          <p align="justify">Возможно, филиал в выбранном вами городе еще не работает.</p></td>
          </tr>
        </table>';
?>
</td>
        </tr>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>