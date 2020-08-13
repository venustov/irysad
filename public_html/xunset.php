<?php
session_start();
//include ('supple/server_root.php');
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
	if(isset($_SESSION['logged_user'])){
//	$_SESSION = array();
	unset($_SESSION['logged_user']);
	session_destroy();
	}
header('Location: '.$HTTP_REFERER);
exit();
?>
<html><head><meta name="robots" content="noindex, nofollow"></head><body></body></html>