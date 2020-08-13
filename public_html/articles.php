<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

	if($_COOKIE['referer']) $_SESSION['referer'] = $_COOKIE['referer'];
	elseif($protection->get['referer']){
	$_SESSION['referer'] = $protection->get['referer'];
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $protection->get['referer'], $end_cookie, '/');
	}
//echo $referer;

include ('supple/server_root.php');
require ('supple/auth_db.php');
require ('supple/translit.php');
require ('supple/capslock.php');

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

$str_sql_query = "SELECT a.id, a.title, a.category, a.lat_cat FROM articles AS a WHERE a.approve = '1' ORDER BY a.id DESC";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if($number){
	$i = 0;
	$cat_arr[0] = mysql_result($result,$i,"a.category");
		while($i < $number){
		$cat = mysql_result($result,$i,"a.category");
		$n = 0;
			foreach($cat_arr as $value){
				if($cat == $value){
				$n++;
				}
			}
			if(!$n) array_push($cat_arr, $cat);
		$i++;
		}
	}
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
<?php
include ('templates/header.tpl');
?>

<title>Ирий Сад: Статьи, посвященные сыроедению (СЕ), сыромоноедению (СМЕ), голоданию, здоровому образу жизни (ЗОЖ), духовному развитию и т.д.</title>
</head>

<body>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_left"><h1>Потребительский кооператив &quot;Ирий Сад&quot;</h1>
	<h2>качественные продукты для сыроедов</h2>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo 'Тел.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">выбрать другой город</a></p></td>
    <td width="200"><img src="images/head2_02.jpg" alt="Кооператив сыроедов" width="200" height="150"></td>
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
    <td colspan="3" class="menu"><table width="100%" border="0" cellpadding="10" cellspacing="0">
      <tr>
        <td width="20%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu">
<?php
	if(is_array($cat_arr)){
		for($i=0; $i<count($cat_arr); $i++){
			if(ereg(capslock(translit($cat_arr[$i])),$_SERVER['REQUEST_URI'])) $class = 'mn2';
			else $class = 'mn1';
		echo '<div class="'.$class.'"><a href="/articles.php?cat='.capslock(translit($cat_arr[$i])).'">'.$cat_arr[$i].'</a></div>';
		}
	}
?>
		</td>
        <td width="80%" align="left" valign="top" class="text" style="border-left:1px solid #FFFFFF;">
		<table width="100%">
		  <tr>
		    <td align="center">
<?php
	if($number){
	$s = $protection->get['s'];	//номер страницы
	$k = 50;	//количество наименований на одной странице
		if(!$s) $s = 1;
	$n = ceil($number/$k);		//количество страниц
	$i = 1;
		if(($n > 1)&&($s != 1)){
		$prev = $s - 1;
		echo '<a href="?s='.$prev.'&sort='.$sort.'">Предыдущая <<</a> ';
		}
		while ($i <= $n){
			if($i == $s) echo '[ '.$i.' ]';
			else echo '[<a href="?s='.$i.'&sort='.$sort.'"> '.$i.' </a>]';
		$i++;
		}
		if(($n > 1)&&($n != $s)){
		$next = $s + 1;
		echo ' <a href="?s='.$next.'&sort='.$sort.'">&gt;&gt; Следующая</a>';
		}
	}
	else echo '&nbsp;';
?>
			</td>
		  </tr>
<?php
$category = $protection->get['cat'];
	if($number){
	echo '<tr>
		    <td><ul>';
	$i = ($s-1)*$k;
		while ($i < $number && $i < ($s*$k)){
		$id = mysql_result($result,$i,"a.id");
		$article = mysql_result($result,$i,"a.title");
		$lat_cat = mysql_result($result,$i,"a.lat_cat");
		$cat = mysql_result($result,$i,"a.category");
		
			if(($category == $lat_cat)||(!$category)) echo '<li><a href="/articles/'.$id.'.html">'.$article.'</a></li>';
		$i++;
		}
	echo '</ul></td>
		  </tr>';
	}
?>
		</table></td>
        </tr>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>