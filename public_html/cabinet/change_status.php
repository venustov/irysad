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
	
		if(($protection->get['id'])||($protection->post['id'])){
			if($protection->post['zak_change']){
			$id = $protection->post['id'];
			$zakup = $protection->post['price_zak'];
			$str_sql_query = "UPDATE items SET
			price_1 = '$zakup'
			WHERE id = '$id'";
			mysql_query($str_sql_query, $link) or die(mysql_error());
			}
			elseif($protection->post['status_change']){
			$id = $protection->post['id'];
			$status = $protection->post['status'];
			$str_sql_query = "UPDATE items SET
			status = '$status'
			WHERE id = '$id'";
			mysql_query($str_sql_query, $link) or die(mysql_error());
			$status_old = $protection->post['status_old'];
				if(($status<=2)&&($status_old>2)){
				$sql_query = 'SELECT c.e_mail_1, i.name_1 FROM ne_wait_lists AS w, customers AS c, items AS i WHERE w.id_product="'.$id.'" AND i.id=w.id_product AND w.id_user=c.uin GROUP BY c.e_mail_1';
				$result = @mysql_query($sql_query,$link);
				$number = @mysql_num_rows($result);
					if($number){
					require ('../supple/mail.php');
					$subject = 'В продаже на сайте '.$site.' появился продукт из Вашего листа ожидания';
					$i=0;
						while($i<$number){
						$email = mysql_result($result,$i,"c.e_mail_1");
						$product = mysql_result($result,$i,"i.name");
						$customer = mysql_result($result,$i,"c.name_1");
							if(!$customer) $customer = 'Участнику кооператива Ирий Сад';
						$message = 'В нашем магазине появился продукт из Вашего листа ожидания: '.$product.'

Теперь его нужно как можно быстрее заказать, чтобы он опять не исчез =)
		
Вам необязательно отвечать на это письмо, т.к. оно сгенерировано роботом. 
С уважением, команда '.$site;
						send_mime_mail($site,$mail_host,$customer,$email,'CP1251','KOI8-R',$subject,$message);
						$i++;
						}
					}
				$sql_query = 'DELETE FROM ne_wait_lists WHERE id_product="'.$id.'"';
				@mysql_query($sql_query,$link);
				}
			}
		}
	mysql_close($link);
	}
header("Location: products.php");
exit();
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><meta name="robots" content="noindex, nofollow"></head><body></body></html>