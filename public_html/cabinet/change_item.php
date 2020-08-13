<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
include ('../supple/server_root.php');
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){

	require ('../supple/translit.php');
	require ('../supple/capslock.php');
	require ('../supple/auth_db.php');

	require ('../supple/security.php');
	$protection=new security();
	$protection->get_decode();
	$protection->post_decode();
	
	$id = $protection->get['id'];
	$name = trim($protection->post['name']);
	$name = str_replace('"','\"',$name);
	$name = str_replace("'","\'",$name);

		if($protection->post['other_from']) $from = trim($protection->post['other_from']);
		else $from = $protection->post['from'];
		$category = $protection->post['category'];
		$month = $protection->post['month'];
		$urozhay = $protection->post['year'];
		$zakup = trim($protection->post['zakup']);
		$rozn = trim($protection->post['rozn']);
		$lot = round(abs(str_replace(',','.',trim($protection->post['lot']))),1);
		$sale = $protection->post['sale'];
		$ed_izmer = $protection->post['ed_izmer'];
		$status = $protection->post['status'];
		$photo = trim($protection->post['photo']);
		$tags = trim($protection->post['tags']);
		$desc = trim($protection->post['desc']);
		$desc = str_replace('"','\"',$desc);
		$desc = str_replace("'","\'",$desc);
		
		if((!$name)||(!$zakup)||(!$lot)||(!$ed_izmer)||(!$rozn)){
		header("Location: item_form.php?id=$id");
		exit();
		}
	
		if($protection->get['id']&&$protection->post['change_item']){
		
		$str_sql_query = "UPDATE items SET
		name = '$name',
		is_from = '$from',
		category = '$category',
		month = '$month',
		urozhay = '$urozhay',
		description = '$desc',
		price_1 = '$zakup',
		price_2 = '$rozn',
		sale = '$sale',
		status = '$status',
		ed_izmer = '$ed_izmer',
		lot = '$lot',
		photo_vk = '$photo',
		tags = '$tags'
		WHERE id = '$id'";
		mysql_query($str_sql_query, $link) or die(mysql_error());
		header("Location: products.php");
		exit();
		}
		elseif($protection->post['insert_item']){
		$city = $_SESSION['logged_city'];
	
		$str_sql_query = "INSERT INTO items
		(name,
		is_from,
		category,
		month,
		urozhay,
		description,
		city,
		price_1,
		price_2,
		sale,
		status,
		ed_izmer,
		lot,
		photo_vk,
		tags) VALUES
		('$name',
		'$from',
		'$category',
		'$month',
		'$urozhay',
		'$desc',
		'$city',
		'$zakup',
		'$rozn',
		'$sale',
		'$status',
		'$ed_izmer',
		'$lot',
		'$photo',
		'$tags')";
		mysql_query($str_sql_query, $link) or die(mysql_error());
		$id = mysql_insert_id();
		header("Location: go_to_photo.php?id=$id");
		exit();
		}
	}
mysql_close($link);
header("Location: item_form.php");
exit();
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body></body></html>