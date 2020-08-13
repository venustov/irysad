<html>
<head>
	<title>TSB Subscription - Admin</title>
	<meta http-equiv="content-type" content="text/html; charset=Windows-1251">
</head>
<link href="style.css" rel="stylesheet" type="text/css">
<body>
<center>
<table border=1 width="510" cellspacing="1" cellpadding="0">
<tr>
<td align="center">
<a href="action.php?act=1" class="menu">cтатистика</a>
</td>
<td align="center">
<a href="action.php?act=2" class="menu">управление базой</a>
</td>
<td align="center">
<a href="action.php?act=3" class="menu">экспорт</a>
</td>
<td align="center">
<a href="action.php?act=7" class="menu">рассылка</a>
</td>
<td align="center">
<a href="action.php?act=6" class="menu">настройка</a>
</td>
<td align="center">
<a href="action.php?act=4" class="menu">about</a>
</td>
<td align="center">
<a href="action.php?act=5" class="menu">выход</a>
</td>
</tr>
</table>
<table>
<?php
function show_ver($arg) {
	if ($arg=="ver") echo '1.38.4beta';
	if ($arg=="date") echo '14.10.06';
}
?>
<tr><td width="510" align="right"><?php show_ver('ver'); ?></td></tr>
</table>