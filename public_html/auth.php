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

require ('supple/mail.php');
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
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
$http = $protection->post['http'];

	if(!$http){
		if(!$HTTP_REFERER) $http = 'index.php';
		else{
			if(!eregi("^$SERVER_ROOTauth.php",$HTTP_REFERER)) $http = $HTTP_REFERER;
			else $http = 'index.php';
		}
	}
	
	if($protection->post['enter']){
		if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
		$uin = $protection->post['uin'];
		$pass = md5($protection->post['pass']);
			if($uin&&$pass&&preg_match('/^[0-9]+$/',$uin)&&preg_match('/^[a-zA-Z0-9]+$/',$pass)){
			require ('supple/auth_db.php');
			$str_sql_query = "SELECT * FROM customers WHERE uin = '$uin'";
			$result = mysql_query($str_sql_query, $link);
			$number = mysql_num_rows($result);
				if($number){
				$i = 0;
					if(($pass == mysql_result($result,$i,"password"))&&mysql_result($result,$i,"approve")){
					$_SESSION['logged_user'] = $uin;
					$_SESSION['logged_city'] = mysql_result($result,$i,"city");
					$_SESSION['logged_status'] = mysql_result($result,$i,"status");
					$_SESSION['logged_balance'] = mysql_result($result,$i,"balance");
					$_SESSION['autopay'] = mysql_result($result,$i,"autopay");
					header("Location: $http");
					exit();
					}
					elseif(!mysql_result($result,$i,"approve")){
					$cod_activate = mysql_result($result,$i,"cod_activate");
					$e_mail = mysql_result($result,$i,"e_mail_1");
//					$id = mysql_result($result,$i,"uin");
					$subject = 'Ссылка для подтверждения регистрации на сайте '.$site.'. Повторно';
					$message = 'Вы или кто-то от вашего имени зарегистрировался на сайте '.$SERVER_ROOT.' 
Чтобы подтвердить регистрацию, пройдите по этой ссылке:
		
'.$SERVER_ROOT.'reg.php?id='.$uin.'&cod='.$cod_activate.'
		
Вам необязательно отвечать на это письмо, т.к. оно сгенерировано роботом. 
С уважением, команда '.$site;
					send_mime_mail($site,$mail_host,'Новому пользователю',$e_mail,'CP1251','KOI8-R',$subject,$message);
					include('templates/head_enter.tpl');
					echo '<tr><td colspan="2" align="center" class="error"><b>Ваш аккаунт еще не активирован! На '.$e_mail.' выслано письмо для подтверждения регистрации. Пройдите по ссылке в сообщении и попытайтесь войти опять.</b></td></tr>';
					}
					else{
					include('templates/head_enter.tpl');
					echo '<tr><td colspan="2" align="center" class="error"><b>Неверный пароль! Либо не удалось соединиться с Базой Данных =(</b></td></tr>';
					}
				}
				else{
				include('templates/head_enter.tpl');
				echo '<tr><td colspan="2" align="center" class="error"><b>Клиенты с таким логином и паролем не обнаружены! Либо не удалось соединиться с Базой Данных =(</b></td></tr>';
				}
			}
			else{
			include('templates/head_enter.tpl');
			echo '<tr><td colspan="2" align="center" class="error"><b>Не заполнены обязательные поля либо они заполнены некорректно!</b></td></tr>';
			}
		}
		else exit();
	}
	else include('templates/head_enter.tpl');
echo '<input type="hidden" name="http" value="'.$http.'">';
include('templates/foot_enter.tpl');	
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>