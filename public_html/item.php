<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$id = $protection->get['id'];
$prev = $protection->get['prev'];
	if(!$prev) $prev = 'items';

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
//Генерация ссылок на случайные статьи:	
$str_sql_query = "SELECT i.id, i.tags FROM items AS i WHERE i.status < '3' AND i.tags IS NOT NULL ORDER BY i.id DESC";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if($number){
	$link_1 = mt_rand(0,($number-1));
	$link_2 = mt_rand(0,($number-1));
	$link_3 = mt_rand(0,($number-1));
		while($link_2==$link_1){
		$link_2 = mt_rand(0,($number-1));
		}
		while(($link_3==$link_1)||($link_3==$link_2)){
		$link_3 = mt_rand(0,($number-1));
		}
	$tag_1 = mysql_result($result,$link_1,"i.tags");
	$link_1 = mysql_result($result,$link_1,"i.id");
	$tag_1 = explode(';',$tag_1);
	$num_1 = mt_rand(0,(count($tag_1)-1));
	$tag_1 = $tag_1[$num_1];
	
	$tag_2 = mysql_result($result,$link_2,"i.tags");
	$link_2 = mysql_result($result,$link_2,"i.id");
	$tag_2 = explode(';',$tag_2);
	$num_2 = mt_rand(0,(count($tag_2)-1));
	$tag_2 = $tag_2[$num_2];
	
	$tag_3 = mysql_result($result,$link_3,"i.tags");
	$link_3 = mysql_result($result,$link_3,"i.id");
	$tag_3 = explode(';',$tag_3);
	$num_3 = mt_rand(0,(count($tag_3)-1));
	$tag_3 = $tag_3[$num_3];

//конец блока
	}

$str_sql_query = 'SELECT * FROM items WHERE id="'.$id.'"';
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
$i = 0;
	if($number){
	$name = mysql_result($result,$i,"name");
	$is_from = mysql_result($result,$i,"is_from");
	$month = mysql_result($result,$i,"month");
	$urozhay = mysql_result($result,$i,"urozhay");
	$desc = str_replace('
','<br/><br/>',mysql_result($result,$i,"description"));
	$zakup = mysql_result($result,$i,"price_1");
	$rozn = mysql_result($result,$i,"price_2");
	$sale = mysql_result($result,$i,"sale");
	$status = mysql_result($result,$i,"status");
	$ed_izmer = mysql_result($result,$i,"ed_izmer");
	$lot = mysql_result($result,$i,"lot");
	$fold = mysql_result($result,$i,"fold");;
	$images = mysql_result($result,$i,"images");;
	$label = mysql_result($result,$i,"label_img");;
	}
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
<?php
include ('templates/header.tpl');
?>
<title>Ирий Сад :: магазин фрукты овощи. <?php
	if($name) echo $name.'. ';
?>Доставка в городе <?php echo $_SESSION['city']; ?>.</title>

<?php
     if (!defined('_SAPE_USER')){
        define('_SAPE_USER', '1802cc91baed5a3f9fcc8490d028aa02');
     }
     require_once(realpath($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php'));
     $sape = new SAPE_client();
?>

</head>

<body>
<table width="1000" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_left"><h1>Потребительский кооператив &quot;Ирий Сад&quot;</h1>
	<h2>качественные продукты для сыроедов</h2>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo 'Тел.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">выбрать другой город</a></p></td>
    <td width="200"><img src="images/head2_02.jpg" width="200" height="150"></td>
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
    <td colspan="3" class="menu"><table width="100%" height="650"  border="0" cellpadding="0" cellspacing="0">
      <tr>
          <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu">
<?php
include ('templates/shop_menu.tpl');
?>
<br>
<form name="search" method="post" action="shop.php"><table bgcolor="#7D94C0">
    <tr>
      <td align="center">Быстрый поиск: </td>
    </tr>
    <tr>
      <td align="right"><select name="search_cat" style="width:128px;">
          <option value="0">Не важно</option>
          <option value="1" selected>Фрукты, овощи</option>
          <option value="2">Семена, орехи</option>
          <option value="3">Зелень, другое</option>
      </select></td>
    </tr>
    <tr>
      <td align="right"><select name="search_urozhay" style="width:128px;">
          <option value="0">Не важно</option>
          <option value="1" selected>Новый урожай</option>
          <option value="2">Старый урожай</option>
      </select></td>
    </tr>
    <tr>
      <td align="right"><select name="search_nalichie" style="width:128px;">
          <option value="0">Не важно</option>
          <option value="1" selected>Есть в наличии</option>
          <option value="2">Нет в наличии</option>
      </select></td>
    </tr>
    <tr>
      <td align="right"><select name="search_from" style="width:128px;">
          <option value="0">Не важно</option>
          <option value="1" selected>Отечественные</option>
          <option value="2">Импортные</option>
      </select></td>
    </tr>
    <tr>
      <td align="right"><select name="search_sale" style="width:128px;">
          <option value="0">Не важно</option>
          <option value="1" selected>Уцененные</option>
      </select></td>
    </tr>
    <tr>
      <td align="right"><input name="search" type="submit" id="search" value="Применить" style="width:128px; height: 20px; padding-bottom: 15px;"></td>
    </tr>
    <tr>
      <td align="right"><input name="clear" type="submit" id="reset" value="Сброс" style="width:128px; height: 20px; padding-bottom: 15px;"></td>
    </tr>
  	<tr>
      <td><? echo $sape->return_links(1); ?></td>
  	</tr>
 	<tr>
      <td><? echo $sape->return_links(1); ?></td>
  	</tr>	
  </table></form></td>
        <td width="88%" valign="top" style="border-left:1px solid #FFFFFF; padding-left:10px; padding-right:10px;">
<?php
	if($number){
		if($ed_izmer == 1) $ed_izmer = 'кг';
		elseif($ed_izmer == 2) $ed_izmer = '100 гр';
		elseif($ed_izmer == 3) $ed_izmer = 'уп.';
		elseif($ed_izmer == 4) $ed_izmer = 'шт.';
		else $ed_izmer = 0;
		
/*	if($rozn&&($rozn < $zakup)) $price = $rozn;
	else $price = $zakup;
	
	if(!$_SESSION['logged_status']) $akt_price = 1.5*$price;
	else $akt_price = $price;
*/
		if($sale) $price = ceil(0.9*$zakup);
		elseif(!$_SESSION['logged_status']) $price = $rozn;
		else $price = $zakup;
					
	echo '<form name="item" method="post" action="basket.php"><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;">
          <tr align="center" bgcolor="#f0f0f0">
            <td colspan="3" style="padding-top:5px; padding-bottom:5px; color:#000000;"><span class="item_title">'.$name.'</span></td>
            </tr>';
		if($sale) echo '<tr align="center">
            <td colspan="3" class="sale">РАСПРОДАЖА!</td>
          </tr>';
//	$img = preg_match('/^([0-9]{1,}_[0-9]{3,})/',$images,$regs);
	$images = explode('-',$images);
	$img = $img_fold.'/photoes/'.$fold.'/'.$images[0].'.jpg';
		if(file_exists($img)){
		$size = getimagesize($img);
			if($size[0]>400){
			$size_1 = 400;
			$size_2 = (400*$size[1])/$size[0];
			}
			else{
			$size_1 = $size[0];
			$size_2 = $size[1];
			}
		echo '<tr>
            <td width="50%" align="right" valign="top" style="padding-top:4px; padding-bottom:4px; padding-right:5px;"><a href="photo.php?fold='.$fold.'&photo='.$images[0].'" target="_blank" alt="'.$name.'" title="Увеличить" onClick="popupWin = window.open(this.href, \'main_photo\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,left=339,top=140,width='.$size[0].',height='.$size[1].'\'); popupWin.focus(); return false;"><img src="'.$img.'" width='.$size_1.' height='.$size_2.' border=0></a></td>
            <td width="45%" valign="top" style="padding-left:5px;">';
	$label_img = $img_fold.'/photoes/'.$fold.'/labels/'.$label.'.jpg';
	$label_preview = $img_fold.'/photoes/'.$fold.'/labels/preview/'.$label.'.jpg';
		if((file_exists($label_img))&&(file_exists($label_preview))){
		$size_img = getimagesize($label_img);
		$size_preview = getimagesize($label_preview);
			if($size_img[0]<=$size_preview[0]){
			echo '<img src="'.$label_img.'" width='.$size_img[0].' height='.$size_img[1].' vspace=5 border=0 align="right" alt="Наклейка" title="Наклейка">';
			}
			else{
			echo '<a href="photo.php?fold='.$fold.'/labels/&photo='.$label.'" target="_blank" alt="Наклейка" title="Увеличить" onClick="popupWin = window.open(this.href, \'main_photo\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,left=339,top=140,width='.$size_img[0].',height='.$size_img[1].'\'); popupWin.focus(); return false;"><img src="'.$label_preview.'" width='.$size_preview[0].' height='.$size_preview[1].' vspace=5 border=0 align="right" alt="Наклейка" title="Наклейка"></a>';
			}
		}
		elseif((file_exists($label_img))||(file_exists($label_preview))){
		$img = $label_img;
		$fold_label = $fold.'/labels';
			if(!file_exists($img)){
			$img = $label_preview;
			$fold_label = $fold.'/labels/preview';
			}
		$size = getimagesize($img);
			if($size[0]>150){
			$size_1 = 150;
			$size_2 = (150*$size[1])/$size[0];
			echo '<a href="photo.php?fold='.$fold_label.'&photo='.$label.'" target="_blank" alt="Наклейка" title="Увеличить" onClick="popupWin = window.open(this.href, \'main_photo\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,left=339,top=140,width='.$size[0].',height='.$size[1].'\'); popupWin.focus(); return false;"><img src="'.$img.'" width='.$size_1.' height='.$size_2.' vspace=5 border=0 align="right" alt="Наклейка" title="Наклейка"></a>';
			}
			else{
			$size_1 = $size[0];
			$size_2 = $size[1];
			echo '<img src="'.$img.'" width='.$size_1.' height='.$size_2.' vspace=5 border=0 align="right" alt="Наклейка" title="Наклейка">';
			}
		}
			if($desc) echo '<span class="text_item">'.$desc.'</span><br>';
			else echo '&nbsp;';
		echo 'Наиболее популярные продукты (запросы): ';
			if($link_1!=$id) echo '<a href="/shop/'.$link_1.'.html">'.$tag_1.'</a>';
			if($link_2!=$id) echo ' <a href="/shop/'.$link_2.'.html">'.$tag_2.'</a>';
			if($link_3!=$id) echo ' <a href="/shop/'.$link_3.'.html">'.$tag_3.'</a>';
		echo '</td>';
		echo '<td width="5%" align="right" valign="bottom" style="padding-top:4px; padding-bottom:4px;"><img src="images/shtrih.jpg" width="20" height="80"></td>
          </tr>';
		}
		else{
		echo '<tr>
            <td width="95%" colspan="2" align="center" style="padding-left:20px;">';
			if($desc) echo '<span class="text_item">'.$desc.'</span>';
			else echo '&nbsp;';
		echo 'Наиболее популярные продукты (запросы): ';
			if($link_1!=$id) echo '<a href="/items/'.$link_1.'.html">'.$tag_1.'</a>';
			if($link_2!=$id) echo ' <a href="/items/'.$link_2.'.html">'.$tag_2.'</a>';
			if($link_3!=$id) echo ' <a href="/items/'.$link_3.'.html">'.$tag_3.'</a>';
		echo '</td>';
		echo '<td width="5%" align="right" valign="bottom" style="padding-top:4px; padding-bottom:4px;"><img src="images/shtrih.jpg" width="20" height="80"></td>
          </tr>';
		}
		if ($images&&(count($images)>1)){
		echo '<tr>
            <td colspan="3" align="center" style="padding-bottom:4px;">';
			for($i=1;$i<count($images);$i++){
			$img = $img_fold.'/photoes/'.$fold.'/preview/'.$images[$i].'.jpg';
			$big_img = $img_fold.'/photoes/'.$fold.'/'.$images[$i].'.jpg';
			$size = getimagesize($img);
				if (file_exists($big_img)){
				$size_big = getimagesize($big_img);
				echo '<a href="photo.php?fold='.$fold.'&photo='.$images[$i].'" target="_blank" onClick="popupWin = window.open(this.href, \'main_photo\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,left=339,top=140,width='.$size_big[0].',height='.$size_big[1].'\'); popupWin.focus(); return false;">';
				}
			echo '<img src="'.$img.'" width='.$size[0].' height='.$size[1].' hspace="2" border="0"';
				if(file_exists($big_img)) echo ' alt="'.$name.'" title="увеличить фото"';
				else echo ' alt="'.$name.'" title="'.$name.'"';	
			echo '>';
				if (file_exists($big_img)) echo "</a>";
			}
		echo "</td>
          </tr>";
		}
	echo '<tr bgcolor="#f0f0f0">
            <td width="50%" align="right" valign="bottom" style="padding-right:5px; padding-bottom:3px;"><span class="text_item">Цена:</span></td>
            <td colspan="2" style="padding-left:5px;"><span class="price">'.$price.'</span> <span class="text_item">';
		if($rozn&&($rozn < $zakup)){
		$old_price = $zakup;
			if(!$_SESSION['logged_status']) $old_price *= 1.5;
		echo '(</span><span class="old_price">'.$old_price.'</span><span class="text_item">) ';
		}
	echo 'р';
		if($ed_izmer) echo '/'.$ed_izmer;
	echo '</span> </td>
          </tr>
          <tr>
            <td width="50%" align="right" style="padding-right:5px;"><span class="text_item">Страна происхождения:</span></td>
            <td colspan="2" style="padding-left:5px;"><span class="text_item">';
		if($is_from&&($is_from != 'Не известно')) echo '<a href="shop.php?is_from='.$is_from.'&prev='.$prev.'" title="найти все продукты с таким происхождением...">'.$is_from.'</a>';
		else echo 'Не известно';
	echo '</span></td>
          </tr>
          <tr bgcolor="#f0f0f0">
            <td width="50%" align="right" style="padding-right:5px;"><span class="text_item">Урожай:</span></td>
            <td colspan="2" style="padding-left:5px;"><span class="text_item">';
		if($urozhay){
			switch($month){
			case 1: echo 'Январь ';
			break;
			case 2: echo 'Февраль ';
			break;
			case 3: echo 'Март ';
			break;
			case 4: echo 'Апрель ';
			break;
			case 5: echo 'Май ';
			break;
			case 6: echo 'Июнь ';
			break;
			case 7: echo 'Июль ';
			break;
			case 8: echo 'Август ';
			break;
			case 9: echo 'Сентябрь ';
			break;
			case 10: echo 'Октябрь ';
			break;
			case 11: echo 'Ноябрь ';
			break;
			case 12: echo 'Декабрь ';
			break;
			}
		echo '<a href="shop.php?urozhay='.$urozhay.'&prev='.$prev.'" title="найти все продукты этого урожая...">'.$urozhay.' года</a>';
		}
		else echo 'Не известно';
	echo '</span></td>
          </tr>
          <tr>
            <td width="50%" align="right" style="padding-right:5px;"><span class="text_item">Наличие:</span></td>
            <td colspan="2" style="padding-left:5px;"><span class="text_item">';
		if($status){
		echo '<a href="shop.php?status='.$status.'&prev='.$prev.'" title="найти все такие продукты...">';
			if($status == 1) echo 'Есть в наличии';
			elseif($status == 2) echo 'Количество ограничено';
			elseif($status == 3) echo 'Ожидается скоро';
			else echo 'Временно отсутствует';
		echo '</a>';
		}
		else echo 'Не известно';
	echo '</span></td>
            </tr>';
		if((!$sale)||($sale)&&($status <= 2)){
		echo '<tr align="center" bgcolor="#f0f0f0">
            <td colspan="3" style="padding-top:5px; padding-bottom:10px;"><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="name" value="'.$name.'"><input type="hidden" name="ed_izmer" value="'.$ed_izmer.'"><input type="hidden" name="lot" value="'.$lot.'"><input type="hidden" name="price" value="'.$price.'"><input type="submit" ';
			if(!$status||($status <= 2)) echo 'name="add_basket" value="Добавить в корзину"';
			else echo 'name="add_list" value="Добавить в лист ожидания"';
		echo '></td>
          </tr>';
		}
	echo '</table></form>';
	}
	else echo '<span class="text_item">Продукт не найден =(</span>';
?>
		</td>
        </tr>
        <tr align="center">
          <td colspan="2"><? echo $sape->return_links(); ?></td>
        </tr>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>