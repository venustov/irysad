<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
include ('../supple/server_root.php');
require ('../supple/auth_db.php');
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];

	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
	include ('../supple/mail.php');
	include ('../supple/passgen.php');
	include ('../supple/imgresize.php');
	include ('../supple/translit.php');
	include ('../supple/capslock.php');	
	
	require ('../supple/security.php');
	$protection=new security();
	$protection->get_decode();
	$protection->post_decode();
	
	$id = $protection->post['id'];
		if(!$id) $id = $protection->get['id'];
		
		if($protection->post['upload']&&is_uploaded_file($_FILES['file']['tmp_name'])){
			if($id) $img = '../'.$img_fold.'/articles/'.$id.'.jpg';
			else{
				if($protection->post['pic']) $pic = $protection->post['pic'];
				else $pic = passgen(8);
			$img = '../'.$img_fold.'/articles/'.$pic.'.jpg';
			}
		$size = getimagesize($_FILES['file']['tmp_name']);
			if(($size[0]>180)||($size[1]>180)){
				if($size[0] > $size[1]){
				$x = (180*$size[1])/$size[0];
				img_resize($_FILES['file']['tmp_name'],$img,180,$x) or die("Ошибка при копировании картинки!");
				}
				else {
				$x = (180*$size[0])/$size[1];
				img_resize($_FILES['file']['tmp_name'],$img,$x,180) or die("Ошибка при копировании картинки!");
				}
			}
			else copy($_FILES['file']['tmp_name'],$img) or die("Ошибка при копировании картинки!");
			if($id){
			$str_sql_query = "UPDATE articles SET
			approve = '0'
			WHERE id = '$id'";
			mysql_query($str_sql_query, $link) or die(mysql_error());
				
			$subject = 'Изменена фотография в статье на сайте '.$site;
			$message = 'Ссылка на страницу: '.$SERVER_ROOT.'articles/'.$id.'.html
Проверьте информацию и активируйте: '.$SERVER_ROOT.$adminka;
			@send_mime_mail($site,$robot_mail,$site,$adm_mail,'CP1251','KOI8-R',$subject,$message);
			header("Location: article_form.php?id=$id&pic=$id");
			exit();
			}
			else{
			header("Location: article_form.php?pic=$pic");
			exit();
			}
		}
		elseif($protection->get['del_pic']){
		$pic = $protection->get['pic'];
			if($pic){
			$img = '../'.$img_fold.'/articles/'.$pic;
				if (file_exists($img)&&unlink($img)){
				header("Location: article_form.php?id=$id");
				exit();
				}
			}
			else{
			header("Location: article_form.php");
			exit();
			}
		}
		elseif($protection->post['insert_article']||$protection->post['change_article']){
	
		$approve = $protection->post['approve'];
		$article = trim($protection->post['article']);
		$epigraf = trim($protection->post['epigraf']);
		
		if($protection->post['other_cat']){
			$cat = trim($protection->post['other_cat']);
			$cat = str_replace("'","\'",$cat);
		}
		else $cat = $protection->post['cat'];
		$lat_cat = capslock(translit($cat));
		
		$desc = trim($protection->post['desc']);
		$tags = $protection->post['tags'];
/*		
//		$desc = preg_replace('/<a href="http:\/\/[^www.]/','<a href="/go.php?li=',$desc);
		$desc = str_replace('<a href="http://www.','<a href="/go.php?be=1&li=',$desc);
		$desc = str_replace('<a href="http://','<a href="/go.php?li=',$desc);
//		$desc = preg_replace("/<a href='http:\/\/[^www.]/","<a href='/go.php?li=",$desc);
		$desc = str_replace("<a href='http://www.","<a href='/go.php?be=1&li=",$desc);
		$desc = str_replace("<a href='http://","<a href='/go.php?li=",$desc);
//		$desc = preg_replace('/<a href=http:\/\/[^www.]/','<a href=/go.php?li=',$desc);
		$desc = str_replace('<a href=http://www.','<a href=/go.php?be=1&li=',$desc);
		$desc = str_replace('<a href=http://','<a href=/go.php?li=',$desc);
*/		
		$desc = str_replace("'","\'",$desc);
		
		$author = str_replace("'","\'",trim($protection->post['author']));
//		$author = str_replace('"','\"',$author);
		
		$link_on = trim($protection->post['link_on']);
		$ankor = trim($protection->post['ankor']);
		$pic = $protection->post['pic'];
		$maker = $_SESSION['logged_user'];
		
			if((!$article)||(!$desc)||(!$author)) $err = 3;
			elseif((preg_match('/<a href=[^>]+[^<]+<\/a>/',$article))||(preg_match('/<a href=[^>]+[^<]+<\/a>/',$ankor))) $err = 2;
			elseif((($link_on)&&(!$ankor))|(($ankor)&&(!$link_on))) $err = 1;
			
			if($err){
			header("Location: article_form.php?id=$id&pic=$pic&err=$err");
			exit();
			}
			
			if($protection->post['insert_article']){
			$str_sql_query = "INSERT INTO articles
			(title,
			epigraf,
			description,
			author,
			maker,
			approve,
			link,
			ankor,
			category,
			lat_cat,
			tags) VALUES
			('$article',
			'$epigraf',
			'$desc',
			'$author',
			'$maker',
			'$approve',
			'$link_on',
			'$ankor',
			'$cat',
			'$lat_cat',
			'$tags')";
			mysql_query($str_sql_query, $link) or die(mysql_error());
			$id = mysql_insert_id();
			}
			elseif($protection->post['change_article']){
			$str_sql_query = "UPDATE articles SET
			title = '$article',
			epigraf = '$epigraf',
			description = '$desc',
			author = '$author',
			approve = '$approve',
			link = '$link_on',
			ankor = '$ankor',
			category = '$cat',
			lat_cat = '$lat_cat',
			tags = '$tags'
			WHERE id = '$id'";
			mysql_query($str_sql_query, $link) or die(mysql_error());
			}
			
			
		$temp_img = '../'.$img_fold.'/articles/'.$pic.'.jpg';
		$img = '../'.$img_fold.'/articles/'.$id.'.jpg';
		
			if(is_uploaded_file($_FILES['file']['tmp_name'])){
			$size = getimagesize($_FILES['file']['tmp_name']);
			
				if(($size[0]>180)||($size[1]>180)){
					if($size[0] > $size[1]){
					$x = (180*$size[1])/$size[0];
					img_resize($_FILES['file']['tmp_name'],$img,180,$x) or die("Ошибка при копировании картинки!");
					}
					else {
					$x = (180*$size[0])/$size[1];
					img_resize($_FILES['file']['tmp_name'],$img,$x,180) or die("Ошибка при копировании картинки!");
					}
				}
				else copy($_FILES['file']['tmp_name'],$img) or die("Ошибка при копировании картинки!");
				
				if((file_exists($temp_img))&&($pic != $id)) unlink($temp_img);
			}
			elseif(($pic)&&($pic != $id)&&(file_exists($temp_img))){
			copy($temp_img,$img) or die("Ошибка при копировании картинки!");
			unlink($temp_img);
			}

		
			if($_SESSION['logged_status'] != 4){
			$subject = 'Добавлена или изменена статья на сайте '.$site;
			$message = 'Ссылка на страницу: '.$SERVER_ROOT.'articles/'.$id.'.html
Проверьте информацию и активируйте: '.$SERVER_ROOT.$adminka.'/article_form.php?id='.$id;
			@send_mime_mail($site,$robot_mail,$site,$adm_mail,'CP1251','KOI8-R',$subject,$message);
			}
		header("Location: articles.php");
		exit();
		}
	header("Location: article_form.php?id=$id&pic=$pic");
	exit();
	}
mysql_close($link);
exit();
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body></body></html>