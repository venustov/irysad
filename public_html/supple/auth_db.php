<?php
$sdb_name = "localhost";
$user_name = "newedem";
$user_password = "QUJruhAr8DZuemaq";
$db_name = "newedem";

$link = mysql_connect($sdb_name, $user_name, $user_password) or die(mysql_error());
mysql_select_db($db_name, $link) or die(mysql_error());
mysql_query("SET NAMES cp1251") or die(mysql_error());
?>