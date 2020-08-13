<?php
require ('supple/auth_db.php');
$i = 0;
$number = 180;
$email_1 = 'info@irysad.com';
$email_2 = 'irysad.spb@gmail.com';
$pass = 'irysad';
$referer = 999;
$city = 'Санкт-Петербург';
$country = 'Россия';
	while ($i < $number){
	$cod_activate = mt_rand(10000,99999);
	$sql_query = "INSERT INTO customers (e_mail_1,e_mail_2,password,up_uin,city,cod_activate,country) VALUES ('$email_1','$email_2','$pass','$referer','$city','$cod_activate','$country')";
	mysql_query($sql_query);
	$i++;
	}
?>