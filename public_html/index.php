<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

	if($_COOKIE['referer']&&(!$protection->get['cleaning'])) $_SESSION['referer'] = $_COOKIE['referer'];
	elseif($protection->get['referer']){
	$_SESSION['referer'] = $protection->get['referer'];
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $protection->get['referer'], $end_cookie, '/');
	}
//echo $referer;
require ('supple/server_root.php');
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
function ItemIndex($img_fold,$info){
$small_photo = $img_fold.'/photoes/'.$info['fold'].'/preview/'.$info['img'].'.jpg';
	if(!file_exists($small_photo)) $small_photo = $img_fold.'/photoes/no_photo.jpg';

$ed_izmer = $info['ed_izmer'];	
	if($ed_izmer == 1) $ed_izmer = 'кг';
	elseif($ed_izmer == 2) $ed_izmer = '100 гр';
	elseif($ed_izmer == 3) $ed_izmer = 'уп.';
	elseif($ed_izmer == 4) $ed_izmer = 'шт.';
	else $ed_izmer = 0;

$str = '<table width="100%" bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;">
                <tr align="right">
                  <td height="53" colspan="2" valign="top" style="padding-right:10px; padding-top:10px;"><span class="item_title"><a href="/shop/items/'.$info['id'].'.html" title="подробнее...">'.$info['name'].'</a></span></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><img src="images/pix.gif" width="0" height="150" border="0" alt="'.$SERVER_ROOT.'"></td>
				  <td width="100%" align="center" valign="middle" style="padding-left:15px"><a href="/shop/items/'.$info['id'].'.html" title="'.$info['name'].'"><img src="'.$small_photo.'" border="0" alt="'.$info['name'].'"></a></td>
                  <td rowspan="2" align="right" valign="top"><img src="images/shtrih.jpg" width="24" height="80" alt="'.$SERVER_ROOT.'"></td>
                </tr>
                <tr>
                  <td colspan="2" align="right" valign="bottom" style="padding-right:10px"><span class="price">'.$info['price'].' р';
	if($ed_izmer) $str .= '/'.$ed_izmer;
$str .= '</span></td>
                </tr>
              </table>';
return $str;
}
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
<?php
include ('templates/header.tpl');
?>
<meta name="google-site-verification" content="4Ddnb6bGTXVOMI_sRyBOYpYupxv8OtQjHyf5cnSYa-g">
<title>Ирий Сад. Фрукты, овощи, зелень, орехи, семена. Доставка домой и в офисы. Санкт-Петербург</title>
<?php
	 global $sape;
     if (!defined('_SAPE_USER')){
        define('_SAPE_USER', '1802cc91baed5a3f9fcc8490d028aa02');
     }
     require_once(realpath($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php'));
     $sape = new SAPE_client();
?>
</head>

<body>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_index_left"><h1>Потребительский кооператив &quot;Ирий Сад&quot;</h1>
	<h2>качественные живые продукты жителям городов</h2>
    <p>Кооператив &quot;Ирий Сад&quot; - это возможность для его членов получать качественную живую пищу, по ее себестоимости, без торговой наценки. Более того, участники кооператива &quot;Ирий Сад&quot;, занимающиеся популяризацией видового питания (сыроедения), получают материальную компенсацию за приобретённые продукты. Таким образом живая пища для людей, живущих в городе, становится тоже бесплатной и более того...</p>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo 'Тел.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">выбрать другой город</a></p></td>
    <td width="200" align="right"><img src="images/head_02.jpg" width="200" height="313" alt="<?php echo $site; ?>"></td>
    <td width="400" align="right" valign="bottom" class="head_index_right">
<?php
	if(!isset($_SESSION['logged_user'])) include('templates/enter_panel.tpl');
	else include('templates/exit_panel.tpl');
?>
	</td>
  </tr>
  <tr>
    <td><img src="images/pix.gif" width="400" height="1" alt="<?php echo $site; ?>"></td>
    <td><img src="images/pix.gif" width="200" height="1" alt="<?php echo $site; ?>"></td>
    <td><img src="images/pix.gif" width="400" height="1" alt="<?php echo $site; ?>"></td>
  </tr>
<?php
include ('templates/menu.tpl');
?>
  <tr>
    <td colspan="3" class="menu"><table width="100%" border="0" cellpadding="0" cellspacing="0">
<?php
$sql_query = 'SELECT i.id, i.name, i.price_1, i.price_2, i.sale, i.ed_izmer, i.fold, i.images, COUNT(b.id_product) AS quantity FROM items AS i, ne_baskets AS b WHERE i.city="'.$_SESSION['city'].'" AND i.id=b.id_product AND i.status<="2" GROUP BY b.id_product ORDER BY quantity DESC LIMIT 8';
$result = mysql_query($sql_query);
$number = mysql_num_rows($result);
	if($number){
	echo '
      <tr>
        <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu"><a href="shop/items/">Продукты</a></td>
        <td width="100%" align="right" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
';
	$i=0;
	$m=4;	//	Количество столбцов
		while($i<$number){
		$j = 1;
			while($j<=$m&&$i<$number){
			$id = mysql_result($result,$i,"i.id");
			$name = str_replace('"','&quot;',mysql_result($result,$i,"i.name"));
			$zakup = mysql_result($result,$i,"price_1");
//				if(!$_SESSION['logged_status']) $zakup *= 1.5;
			$rozn = mysql_result($result,$i,"price_2");
//				if(!$_SESSION['logged_status']) $rozn *= 1.5;
			$sale = mysql_result($result,$i,"sale");
			
/*	В случае, если цены будет вбивать закупщик и расчитываться они будут от закупочной цены:
				if($sale) $price = $zakup;
				elseif(!$_SESSION['logged_status']) $price = $rozn;
				else $price = ceil(1.1*$zakup);
*/			
				if($sale) $price = ceil(0.9*$zakup);
				elseif(!$_SESSION['logged_status']) $price = $rozn;
				else $price = $zakup;
				
			$ed_izmer = mysql_result($result,$i,"i.ed_izmer");
			$fold_img = mysql_result($result,$i,"i.fold");
				if(ereg("([0-9]{1,}_[0-9]{3,})-",mysql_result($result,$i,"i.images"),$regs)) $img = $regs[1];
				if(!$img) $img = mysql_result($result,$i,"i.images");
				
			$info = array('id'=>$id,'name'=>$name,'price'=>$price,'ed_izmer'=>$ed_izmer,'fold'=>$fold_img,'img'=>$img);
				if($j==1) echo '          <tr>
';
			echo '            <td width="25%" valign="middle">';
			echo ItemIndex($img_fold,$info);
			echo '</td>
';
				if($j==($m+1)) echo '</tr>';
			$img = '';
			$j++;
			$i++;
			}
		}
		if($j==2) echo '<td width="25%">&nbsp;</td><td width="25%">&nbsp;</td><td width="25%">&nbsp;</td></tr>';
		elseif($j==3) echo '<td width="25%">&nbsp;</td><td width="25%">&nbsp;</td></tr>';
		elseif($j==4) echo '<td width="25%">&nbsp;</td></tr>';
	echo '
</table></td>
        </tr>
      ';
	}
$sql_query = 'SELECT id, date, description FROM news WHERE approve="1" AND access="0" ORDER BY date DESC LIMIT 4';
$result = mysql_query($sql_query);
$number = mysql_num_rows($result);
	if($number){
	require ('supple/normal_date.php');
	echo '
	 <tr>
        <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu"><a href="news.php">Новости</a></td>
        <td width="100%" align="right" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>';
	$i=0;
		while ($i<$number){
		$id = mysql_result($result,$i,"id");
		$date = NormalDate(mysql_result($result,$i,"date"));
		$desc = substr(mysql_result($result,$i,"description"),0,150);
		
		$desc = preg_replace('/<a href=[\"\']{0,1}http[s]{0,1}:\/\/[\S]*[\'\"]{0,1}>([\S\s]*)<\/a>/','\1',$desc);
		
		echo '
            <td width="25%" align="right" valign="top"><table width="100%" bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="8" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;">
              <tr>
                <td align="right" valign="top" class="item_title"><a href="'.$SERVER_ROOT.'news/'.$id.'">'.$date.'</a></td>
              </tr>
              <tr>
                <td height="90" align="right" valign="top"><a href="'.$SERVER_ROOT.'news/'.$id.'">'.$desc.' ...</a></td>
              </tr>
            </table></td>
          ';
		$i++;
		}
	echo '</tr>
        </table></td>
      </tr>';
	}
?>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>