<?php
ob_start();
if (isset($_COOKIE['subs'])) {
	if (file_exists("emails.txt")) {
    	if (count(file("emails.txt"))>$_COOKIE['subs']) {
    		echo 'alert("У вас появились новые подписчики! Автоматический переход будет осуществлен в след. версиях.! ;)");';
        	setcookie("subs",count(file("emails.txt")),time()+2592000);
    	}
 	}
}
ob_end_flush();
?>