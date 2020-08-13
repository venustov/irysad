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
				if(!isset($_SESSION['referer'])) if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
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
$str_sql_query = 'SELECT * FROM cities WHERE name != "'.$_SESSION['city'].'" ORDER BY name ASC';
$result = mysql_query($str_sql_query) or die(mysql_error());
$number = mysql_num_rows($result);
	if($number){
	$i = 0;
		while($i < $number){
		$name = mysql_result($result,$i,"name");
		$city_arr[] = $name;
		$i++;
		}
	}
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
<?php
include ('templates/header.tpl');
?>

<title>Ирий Сад: живая пища для сыроедов городов. Выбор города.</title>
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
    <td colspan="3" class="menu"><table width="100%" height="650"  border="0" cellpadding="10" cellspacing="0">
      <tr>
        <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu">Выберите город:</td>
        <td width="44%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu"><table width="80%" border="0" cellpadding="10" cellspacing="0" bgcolor="#C9D6E7">
		<form name="setcity" method="post" action="setcity.php">
          <tr align="right">
            <td colspan="3" class="error">Ваш город: 
<?php
echo $_SESSION['city'];
?></td>
          </tr>
<?php
	if(is_array($city_arr)){
	echo '<tr>
            <td width="100%" align="right" class="text">Изменить:</td>
            <td align="right">
              <select name="new_city">';
		for($i=0; $i<count($city_arr); $i++){
		echo '<option value="'.$city_arr[$i].'"';
			if(!$i) echo ' selected';
		echo '>'.$city_arr[$i].'</option>';
		}
	echo '</select>
            </td>
            <td align="right"><input name="change_city" type="submit" id="change_city" value="Да!"></td>
          </tr>';
	}
?>
		</form>
        </table></td>
        <td width="44%" align="right" valign="top" class="text" style="border-left:1px solid #FFFFFF;">Если вашего города нет в списке доступных городов, но вы бы хотели его видеть и в дальнейшем открыть филиал магазина у себя в городе, то свяжитесь с администратором сайта по ICQ# <?php echo $icq; ?> с тем, чтобы обговорить все детали. </td>
        </tr>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>