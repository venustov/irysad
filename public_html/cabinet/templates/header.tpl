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

<title>���� ���: ������� ������������. <?php echo $title; ?></title>
</head>

<body>

<div class="holder" style="padding:0 25px 0 25px">
<table width="100%" cellspacing="0" id="nav">
 <tr>
  <th width="20%" align="center"><div><a href="<?php echo $SERVER_ROOT; ?>"><img src="../images/logo.gif" width="76" height="41" border="0" align="left"></a>����������<br />
    �����<br />����</div></th>
  <td width="42%" height="67">&nbsp;</td>
  <td width="12%"><a href="<?php echo $SERVER_ROOT; ?>talking/" target="_blank"><img src="../images/ico03.gif" width="35" height="32"><br />
    <b>�����</b></a></td>
  <td width="12%"><a href="../unset.php"><img src="../images/ico01.gif" width="34" height="32"><br />
    <b>����� �� ��������</b></a></td>
 </tr>
</table>
</div>
<div style="background-color:#fff;padding:20px 25px 20px 25px">
<div class="holder">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" align="center" valign="middle"><table cellspacing="0" width="100%" id="dgvr3">
      <tr>
        <td width="25%">UIN: <b><?php echo $_SESSION['logged_user']; ?></b><br><nobr>������: <b><?php echo $_SESSION['logged_balance']; ?></b> ���. [<b>+0</b> ���.]</nobr></td>
        <th width="40%">������ ��� ����������� ����� ������������� �� ������ �����:<br /><b><?php echo $SERVER_ROOT; ?>u<?php echo $_SESSION['logged_user']; ?></b></th>
        <td width="35%">��� ��������� ������� ����� ����������: <b>0</b>&nbsp;������. <nobr>��� �����: <b>0</b>&nbsp;������. ���� ������: <b>10</b> �����.</nobr></td>
      </tr>
	</table></td>
  </tr>
  <tr>
    <td width="200" height="100%" valign="top">
        <div class="hdr1"><div class="hdr2" style="background-position:0 0">������ ����������</div></div>
<?php
echo MenuItem('index.php','�������');
echo MenuItem('persona.php','������������ ����������');
echo MenuItem('yournet.php','���� ����');
echo MenuItem('basket.php','��� ������� �����');
echo MenuItem('wait_list.php','���� ��������');
echo MenuItem('articles.php','������ �� ���');
echo MenuItem('stats.php','���� ����������');
echo MenuItem('create_price.php','�����-���� ��� ����� ���������');

	if($_SESSION['logged_status']>0){
	echo MenuItem('http://report11.file.qip.ru','���������� ����������');
	}
	
	if($_SESSION['logged_status']>1){
	echo MenuItem('shopadmin.php','��������� ������/��������');
	echo MenuItem('purchases.php','���������� ��������');
	}
		
	if($_SESSION['logged_status']>2){
	echo MenuItem('cityadmin.php','��������� ������');
	echo MenuItem('products.php','���������� ����������');
	echo MenuItem('sclads.php','���������� ����������');
	}

	if($_SESSION['logged_status']>3){
	echo MenuItem('new_purchase.php','����� �����');
	echo MenuItem('update_user_pay.php','�������� ���������� ��');
	echo MenuItem('insert_contribution.php','�������� ������ �������');
	echo MenuItem('calculate_bonuses.php','��������� ������');
	echo MenuItem('generator_of_list_subscribe.php','��������� ����� ��������');
	echo MenuItem('../subscribe/admin.php','E-Mail ��������');
	}
echo MenuItem('../xunset.php','����� �� ��������');
?>
    </td>
    <td width="100%" height="100%" valign="top" style="padding-left:25px">