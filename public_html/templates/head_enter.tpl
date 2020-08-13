<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<base href=<?php echo $SERVER_ROOT; ?>>

<meta name="robots" content="noindex,nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta http-equiv="Expires" content="0">

<title>Форма для авторизации на сайте. <?php echo $site; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="style.css" type="text/css" rel="stylesheet">
</head>

<body>
<table width="1000" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="40%" align="right" valign="middle" class="head_left"><h1>Потребительский кооператив &quot;Ирий Сад&quot;</h1>
	<h2>качественные продукты для сыроедов</h2>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo 'Тел.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">выбрать другой город</a></p></td>
    <td width="20%"><img src="images/head2_02.jpg" width="200" height="150"></td>
    <td width="40%" align="right" valign="bottom" class="head_right">&nbsp;</td>
  </tr>
<?php
include ('templates/menu.tpl');
?>
  <tr>
    <td colspan="3" class="menu"><table width="100%" height="650"  border="0" cellpadding="10" cellspacing="0">
      <tr>
        <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu">Вход в аккаунт члена клуба-кооператива</td>
        <td width="44%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu"><table width="200" border="0" cellpadding="2" cellspacing="0" bgcolor="#C9D6E7">
		<form action="" method="post" name="auth">