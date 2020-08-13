<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

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
		else{
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
	
	if($_SESSION['city']){
	$str_sql_query = 'SELECT dostavka FROM cities WHERE name = "'.$_SESSION['city'].'"';
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
		$about = mysql_result($result,$i,"dostavka");

		$about = str_replace('<a href="http://www.','<a href="/go.php?be=1&li=',$about);
		$about = str_replace('<a href="http://','<a href="/go.php?li=',$about);
		
		$about = str_replace("<a href='http://www.","<a href='/go.php?be=1&li=",$about);
		$about = str_replace("<a href='http://","<a href='/go.php?li=",$about);
		
		$about = str_replace('<a href=http://www.','<a href=/go.php?be=1&li=',$about);
		$about = str_replace('<a href=http://','<a href=/go.php?li=',$about);
		$about = str_replace('
','<br><br>',$about);
		}
	}

	if($_COOKIE['referer']) $_SESSION['referer'] = $_COOKIE['referer'];
	elseif($protection->get['referer']){
	$_SESSION['referer'] = $protection->get['referer'];
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $protection->get['referer'], $end_cookie, '/');
	}

function MenuItem($url,$ancor){
	if(ereg('/'.$url,$_SERVER['REQUEST_URI'])) $class = 'mn2';
	else $class = 'mn1';
$str = '<div class="'.$class.'"><a href="'.$url.'">'.$ancor.'</a></div>';
return $str;
}
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
<?php
include ('templates/header.tpl');
?>

<title>Доставка продуктов в городе: <?php echo $_SESSION['city']; ?></title>
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
    <td width="200"><img src="images/head2_02.jpg" alt="Как оплатить заказ в магазине Ирий Сад" width="200" height="150"></td>
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
    <td colspan="3" class="menu"><table width="100%"  border="0" cellpadding="10" cellspacing="0">
      <tr>
        <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu">
<?php
include ('templates/info_menu.tpl');
?>
		</td>
        <td width="88%" valign="top" style="border-left:1px solid #FFFFFF;"><table width="100%"  border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;">
          <tr>
            <td><h2>Доставка продуктов в городе: <?php echo $_SESSION['city']; ?></h2></td>
          </tr>
          <tr>
            <td class="text"><p align="justify"><?php echo $about; ?></p></td>
          </tr>
        </table></td>
        </tr>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>