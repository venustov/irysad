<?php
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

	if(!$protection->get['id']){
	header('Location: products.php');
	exit();	
	}
	else{
	header('Location: photo.php?id='.$protection->get['id']);
	exit();	
	}
?>