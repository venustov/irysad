<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
include ('../supple/server_root.php');
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
	
	require ('../supple/auth_db.php');
	require ('../supple/security.php');
	$protection=new security();
	$protection->get_decode();
	$protection->post_decode();

	$uin = $_SESSION['logged_user'];
	
		if($protection->post['change_info']){
	
		$name_1 = trim($protection->post['name_1']);
		$name_2 = trim($protection->post['name_2']);
		$name_3 = trim($protection->post['name_3']);
		$icq = trim($protection->post['icq']);
		$vkontakte = trim($protection->post['vkontakte']);
		$phone_1 = trim($protection->post['phone_1']);
		$phone_2 = trim($protection->post['phone_2']);
		$skype = trim($protection->post['skype']);
		$country = trim($protection->post['country']);
		$city = trim($protection->post['city']);
		$address = trim($protection->post['address']);
		$email = trim($protection->post['email']);
		$about = trim($protection->post['about']);
		$autopay = trim($protection->post['autopay']);
	
			if((!$name_1)&&(!$name_2)&&(!$name_3)&&(!$icq)&&(!$vkontakte)&&(!$phone_1)&&(!$phone_2)&&(!$skype)&&(!$country)&&(!$city)&&(!$address)&&(!$email)&&(!$about)){
			header("Location: persona.php");
			exit();
			}
			else{
			$str_sql_query = 'UPDATE customers SET
			name_1 = \''.$name_1.'\',
			name_2 = \''.$name_2.'\',
			name_3 = \''.$name_3.'\',
			icq = \''.$icq.'\',
			vkontakte = \''.$vkontakte.'\',
			phone_1 = \''.$phone_1.'\',
			phone_2 = \''.$phone_2.'\',
			skype = \''.$skype.'\',
			country = \''.$country.'\',
			address = \''.$address.'\',
			e_mail_2 = \''.$email.'\',
			city = \''.$city.'\',
			about = \''.$about.'\',
			autopay = \''.$autopay.'\'
			WHERE uin = \''.$uin.'\'';

				if(!mysql_query($str_sql_query, $link)) $err = 1;	//Ошибка при записи в Базу Данных
//			echo $str_sql_query;
			header("Location: persona.php?err=$err");
			}
		}
		elseif($protection->post['change_pass']){
		
		$pass_1 = $protection->post['pass1'];
		$pass_2 = $protection->post['pass2'];
		
			if($pass_1 != $pass_2) $err = 2;	//Пароли в соответствующих полях дожны быть одинаковы!
			elseif($pass_1&&(strlen($pass_1) < 8)) $err = 3;	//В пароле слишком маленькое количество символов!
			elseif($pass_1&&(!preg_match('/^[a-zA-Z0-9]+$/',$pass_1))) $err = 4;	//В пароле присутствуют недопустимые символы!
			elseif($pass_1){
			$md5_pass = md5($pass_1);
			$str_sql_query = "UPDATE customers SET password = '$md5_pass' WHERE uin = '$uin'";
				if(!mysql_query($str_sql_query, $link)) $err = 5;	//Ошибка при записи в Базу Данных
				else $err = 6;	//Ваш пароль успешно изменен!
			}
		header("Location: persona.php?err=$err");
		}
	}
mysql_close($link);
exit();
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body></body></html>