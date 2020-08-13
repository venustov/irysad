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

		if($protection->post['change_city']){
		
		$id = $protection->post['id'];
		
			if($protection->post['adm_uin']) $adm_uin = trim($protection->post['adm_uin']);
			else $adm_uin = $protection->post['admin_old'];
			
			if($protection->post['city']) $name = trim($protection->post['city']);
			else $name = $protection->post['name_old'];
			
			if($protection->post['icq']) $icq = trim($protection->post['icq']);
			else $icq = $protection->post['icq_old'];
			
			if($protection->post['phone']) $phone = trim($protection->post['phone']);
			else $phone = $protection->post['phone_old'];
			
			if($protection->post['skype']) $skype = trim($protection->post['skype']);
			else $skype = $protection->post['skype_old'];
			
			if($protection->post['country']) $country = trim($protection->post['country']);
			else $country = $protection->post['country_old'];
			
			if($protection->post['about']) $about = trim($protection->post['about']);
			else $about = $protection->post['about_old'];
			
			if($protection->post['contacts']) $contacts = trim($protection->post['contacts']);
			else $contacts = $protection->post['contacts_old'];
				
		$str_sql_query = "UPDATE cities SET
		name = '$name',
		dostavka = '$about',
		contacts = '$contacts',
		icq = '$icq',
		phone = '$phone',
		skype = '$skype',
		admin = '$adm_uin',
		country = '$country'
		WHERE id = '$id'";

				if(!mysql_query($str_sql_query,$link)) $err = 1;	//Ошибка при записи в Базу Данных
//		echo $str_sql_query;
		header("Location: cityadmin.php?err=$err");
		}
	}
mysql_close($link);
exit();
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body></body></html>