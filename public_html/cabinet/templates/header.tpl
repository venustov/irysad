<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
include ('../supple/server_root.php');

function MenuItem($url,$ancor){
	if(ereg('/cabinet/'.$url,$_SERVER['REQUEST_URI'])) $class = 'mn2';
	else $class = 'mn1';
$str = '<div class="'.$class.'"><a href="'.$url.'">'.$ancor.'</a></div>';
return $str;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="robots" content="noindex,nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="style.css" type="text/css" rel="stylesheet">

<title>Ирий Сад: Кабинет пользователя. <?php echo $title; ?></title>
</head>

<body>

<div class="holder" style="padding:0 25px 0 25px">
<table width="100%" cellspacing="0" id="nav">
 <tr>
  <th width="20%" align="center"><div><a href="<?php echo $SERVER_ROOT; ?>"><img src="../images/logo.gif" width="76" height="41" border="0" align="left"></a>Кооператив<br />
    Новый<br />Эдем</div></th>
  <td width="42%" height="67">&nbsp;</td>
  <td width="12%"><a href="<?php echo $SERVER_ROOT; ?>talking/" target="_blank"><img src="../images/ico03.gif" width="35" height="32"><br />
    <b>Форум</b></a></td>
  <td width="12%"><a href="../unset.php"><img src="../images/ico01.gif" width="34" height="32"><br />
    <b>Выход из кабинета</b></a></td>
 </tr>
</table>
</div>
<div style="background-color:#fff;padding:20px 25px 20px 25px">
<div class="holder">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" align="center" valign="middle"><table cellspacing="0" width="100%" id="dgvr3">
      <tr>
        <td width="25%">UIN: <b><?php echo $_SESSION['logged_user']; ?></b><br><nobr>Баланс: <b><?php echo $_SESSION['logged_balance']; ?></b> руб. [<b>+0</b> руб.]</nobr></td>
        <th width="40%">Ссылка для приглашения новых пользователей от Вашего имени:<br /><b><?php echo $SERVER_ROOT; ?>u<?php echo $_SESSION['logged_user']; ?></b></th>
        <td width="35%">Ваш следующий клубный взнос составляет: <b>0</b>&nbsp;рублей. <nobr>Ваш бонус: <b>0</b>&nbsp;рублей. День оплаты: <b>10</b> число.</nobr></td>
      </tr>
	</table></td>
  </tr>
  <tr>
    <td width="200" height="100%" valign="top">
        <div class="hdr1"><div class="hdr2" style="background-position:0 0">Панель управления</div></div>
<?php
echo MenuItem('index.php','Новости');
echo MenuItem('persona.php','Персональная информация');
echo MenuItem('yournet.php','Ваша сеть');
echo MenuItem('basket.php','Ваш текущий заказ');
echo MenuItem('wait_list.php','Лист ожидания');
echo MenuItem('articles.php','Статьи по СМЕ');
echo MenuItem('stats.php','Ваша статистика');
echo MenuItem('create_price.php','Прайс-лист для групп вКонтакте');

	if($_SESSION['logged_status']>0){
	echo MenuItem('http://report11.file.qip.ru','Финансовая информация');
	}
	
	if($_SESSION['logged_status']>1){
	echo MenuItem('shopadmin.php','Настройка склада/магазина');
	echo MenuItem('purchases.php','Управление заказами');
	}
		
	if($_SESSION['logged_status']>2){
	echo MenuItem('cityadmin.php','Настройка города');
	echo MenuItem('products.php','Управление продуктами');
	echo MenuItem('sclads.php','Управление магазинами');
	}

	if($_SESSION['logged_status']>3){
	echo MenuItem('new_purchase.php','Новый заказ');
	echo MenuItem('update_user_pay.php','Внесение оплаченных ЧВ');
	echo MenuItem('insert_contribution.php','Внесение паевых взносов');
	echo MenuItem('calculate_bonuses.php','Начислить бонусы');
	echo MenuItem('generator_of_list_subscribe.php','Генерация листа рассылки');
	echo MenuItem('../subscribe/admin.php','E-Mail рассылка');
	}
echo MenuItem('../xunset.php','Выход из кабинета');
?>
    </td>
    <td width="100%" height="100%" valign="top" style="padding-left:25px">