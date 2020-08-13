<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
include ('../supple/server_root.php');
require ('../supple/auth_db.php');
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];

function delNews($id){
$str_sql_query = "DELETE FROM news WHERE id = '$id'";
@mysql_query($str_sql_query);
return true;
}

	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
	include ('../supple/mail.php');
	
	require ('../supple/security.php');
	$protection=new security();
	$protection->get_decode();
	$protection->post_decode();
	
	$change = $protection->post['change_news'];
		if(!$change) $change = $protection->get['change_news'];
	
		if($protection->get['id']&&$change){
		$id = $protection->get['id'];
		
		$approve = $protection->post['approve'];
		$title = trim($protection->post['title']);
		$desc = trim($protection->post['desc']);
		$access = $protection->post['access'];
//		$newsmaker = $_SESSION['logged_user'];
	
			if((!$title)||(!$desc)){
			header("Location: news_form.php?id=$id");
			exit();
			}
			else{
			$str_sql_query = "UPDATE news SET
			title = '$title',
			description = '$desc',
			access = '$access',
			approve = '$approve'
			WHERE id = '$id'";
			mysql_query($str_sql_query, $link) or die(mysql_error());
				if(($_SESSION['logged_status'] != 4)&&($approve)){
				$subject = 'Изменена новость на сайте '.$site;
				$message = 'Ссылка на страницу: '.$SERVER_ROOT.'news/'.$id.'
Проверьте информацию и активируйте: '.$SERVER_ROOT.$adminka.'/news_form.php?id='.$id.'&change_news=true';
				@send_mime_mail($site,$robot_mail,$site,$adm_mail,'CP1251','KOI8-R',$subject,$message);
				}
			header("Location: index.php");
			}
		}
		elseif($protection->post['insert_news']){
	
		$approve = $protection->post['approve'];
		$title = trim($protection->post['title']);
		$desc = trim($protection->post['desc']);
		$access = $protection->post['access'];
		$newsmaker = $_SESSION['logged_user'];
		
			if((!$title)||(!$desc)){
			header("Location: news_form.php");
			exit();
			}
			else{
			$str_sql_query = "INSERT INTO news
			(title,
			description,
			access,
			approve,
			newsmaker) VALUES
			('$title',
			'$desc',
			'$access',
			'$approve',
			'$newsmaker')";
			mysql_query($str_sql_query, $link) or die(mysql_error());
			$id = mysql_insert_id();
				if($_SESSION['logged_status'] != 4){
				$subject = 'Добавлена новость на сайте '.$site;
				$message = 'Ссылка на страницу: '.$SERVER_ROOT.'news/'.$id.'
Проверьте информацию и активируйте: '.$SERVER_ROOT.$adminka.'/news_form.php?id='.$id.'&change_news=true';
				@send_mime_mail($site,$robot_mail,$site,$adm_mail,'CP1251','KOI8-R',$subject,$message);
				}
			header("Location: index.php");
			exit();
			}
		}
		elseif(($protection->get['del_news'])&&($protection->post['approve_del'])){
		$id = $protection->get['id'];
			if(($_SESSION['logged_status'] > 1)&&($_SESSION['logged_status'] < 4)){
			$str_sql_query = "SELECT * FROM news WHERE id = '$id'";
			$result = mysql_query($str_sql_query);
			$number = mysql_num_rows($result);
				if($number){
				$newsmaker = mysql_result($result,$i,"newsmaker");
					if($newsmaker == $_SESSION['logged_user']){
					delNews($id);
					}
				}
			}
			elseif($_SESSION['logged_status'] == 4){
			delNews($id);
			}
		header("Location: index.php");
		exit();
		}
		elseif(($protection->get['del_news'])&&(!$protection->post['no_del'])){
		include ('templates/del_approve.tpl');
		exit();
		}
	header("Location: index.php");
	}
mysql_close($link);
exit();
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body></body></html>