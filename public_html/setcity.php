<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$HTTP_REFERER = $_SERVER['HTTP_REFERER'];

	if($protection->post['change_city']){
	
	$new_city = $protection->post['new_city'];
	$old_city = $_SESSION['city'];
	
		if($new_city != $old_city){
		$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
		unset($_SESSION['city']);
//		session_destroy();
		setcookie('city', $new_city, $end_cookie, '/');
		}
	}
	
header("Location: $HTTP_REFERER");
exit();
?>
<html><head><meta name="robots" content="noindex, nofollow"></head><body></body></html>