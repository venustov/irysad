<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

	if($_COOKIE['referer']) $_SESSION['referer'] = $_COOKIE['referer'];
	elseif($protection->get['referer']){
	$_SESSION['referer'] = $protection->get['referer'];
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $protection->get['referer'], $end_cookie, '/');
	}
//echo $referer;

include ('supple/server_root.php');
require ('supple/auth_db.php');
include ('supple/mail.php');
include ('supple/passgen.php');

if(!isset($_SESSION['city'])){
	if ($_COOKIE['city']){
	$str_sql_query = 'SELECT name, icq, phone, admin, country FROM cities WHERE name = "'.$_COOKIE['city'].'"';
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if(!$number){
		$str_sql_query = "SELECT name, icq, phone, admin, country FROM cities WHERE capital = '1'";
		$result = mysql_query($str_sql_query) or die(mysql_error());
		$number = mysql_num_rows($result);
			if($number){
			$i = 0;
			
			$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
			setcookie('city', mysql_result($result,$i,"name"), $end_cookie, '/');
		
			$_SESSION['city'] = mysql_result($result,$i,"name");
			$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
			$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
				if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
			$_SESSION['country'] = mysql_result($result,$i,"country");
			}
		}
		else{
		$i = 0;
		
		$_SESSION['city'] = $_COOKIE['city'];
		$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
		$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
			if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
		$_SESSION['country'] = mysql_result($result,$i,"country");
		}
	}
	else {
	$str_sql_query = "SELECT name, icq, phone, admin, country FROM cities WHERE capital = '1'";
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
		
		$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
		setcookie('city', mysql_result($result,$i,"name"), $end_cookie, '/');
		
		$_SESSION['city'] = mysql_result($result,$i,"name");
		$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
		$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
			if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
		$_SESSION['country'] = mysql_result($result,$i,"country");
		}
	}
}
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
function Form($message){
	if($message) $str .= '
			  <tr>
			    <td align="center" class="error"><b>'.$message.'</b></td>
			  </tr>';
$str .= '
              <tr>
                <td align="center">������� ��� UIN ���� e-mail, ��������� ��� �����������:</td>
              </tr>
              <tr>
                <td align="center"><input name="login" type="text" id="login" size="26" maxlength="80"></td>
              </tr>
              <tr>
                <td align="center"><input type="submit" name="submit" value="������� ����� ������"></td>
              </tr>
              <tr>
                <td align="center"><a href="index.php" class="default">��������� �� ������� ��������</td>
              </tr>';
return $str;
}
?>
<?php
include ('templates/header.tpl');
?>

<title>���� ���: ����� ���� ��� �������� �������. ��������������� �����������.</title>
</head>

<body>
<table width="1000" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_left"><h1>��������������� ���������� &quot;���� ���&quot;</h1>
	<h2>������������ �������� ��� ��������</h2>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo '���.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">������� ������ �����</a></p></td>
    <td width="200"><img src="images/head2_02.jpg" width="200" height="150"></td>
    <td width="400" align="right" valign="bottom" class="head_right">
<?php
	if(!isset($_SESSION['logged_user'])){
	include ('templates/enter_panel.tpl');
	}
	else include ('templates/exit_panel.tpl');
?>
	</td>
  </tr>
<?php
include ('templates/menu.tpl');
?>
  <tr>
    <td colspan="3" class="menu"><table width="100%" height="650"  border="0" cellpadding="10" cellspacing="0">
      <tr>
        <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu">����������� ������ ����� �����</td>
        <td width="44%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu"><table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#C9D6E7">
		<form name="recall" method="post" action="">
<?php
	if($protection->post['submit']){
	$login = $protection->post['login'];
	$str_sql_query = "SELECT uin, e_mail_1, e_mail_2, approve, cod_activate FROM customers WHERE uin='$login' OR e_mail_1='$login' OR e_mail_2='$login'";
	$result = @mysql_query($str_sql_query,$link);
	$number = @mysql_num_rows($result);
		if($number){
		$approve = mysql_result($result,$i,"approve");
		$id = mysql_result($result,$i,"uin");
		$email_1 = mysql_result($result,$i,"e_mail_1");
		$email_2 = mysql_result($result,$i,"e_mail_2");
			if($approve){
			
			$length = 8;
			$new_pass = passgen($length);
			$md5_pass = md5($new_pass);
			
			$str_sql = "UPDATE customers SET password = '$md5_pass' WHERE uin = '$id'";
			
				if(@mysql_query($str_sql)){
			
				$subject = '���������� � ����� �������� �� ����� '.$site;
				$message = '���� ������ ��� �����������:
			
	�����:	'.$id.'
	������:	'.$new_pass.'

�� ������ �������� ������ � ���������� � ������ ����������.
��� ������������� �������� �� ��� ������, �.�. ��� ������������� �������.

� ���������, ������� '.$site;
					if(send_mime_mail($site,$mail_host,'������������',$email_1,'CP1251','KOI8-R',$subject,$message)||send_mime_mail($site,$mail_host,'������������',$email_2,'CP1251','KOI8-R',$subject,$message)){
					echo Form('��������� �����.');
					}
					else echo Form('������! �� ������� ��������� ������ � ����� ������� �� ��� e-mail. ����������, ��������� ������.');
				}
				else echo Form('������! �� ������� �������� ������ � ������� �� ��� e-mail. ����������, ��������� ������.');
			}
			else{
			$cod = mysql_result($result,$i,"cod_activate");
			$subject = '������ ��� ������������� ����������� �� ����� '.$site.' ��������.';
			$message = '�� ��� ���-�� �� ������ ����� ����������������� �� ����� '.$root.' 
����� ����������� �����������, �������� �� ���� ������:
		
'.$SERVER_ROOT.'reg.php?id='.$id.'&cod='.$cod.'
		
��� ������������� �������� �� ��� ������, �.�. ��� ������������� �������. 
� ���������, ������� '.$site;
				if(send_mime_mail($site,$mail_host,'������������',$email_1,'CP1251','KOI8-R',$subject,$message)||send_mime_mail($site,$mail_host,'������������',$email_2,'CP1251','KOI8-R',$subject,$message)){
				echo Form('�� ��� e-mail �������� ������� ������ ��� ��������� ��������. ��������� �����.');
				}
				else echo Form('������! �� ������� ��������� ������ ��� ��������� �������� �� ��� e-mail. ����������, ��������� ������.');
			}
		}
		else echo Form('������! ������������ � ����� UIN ��� e-mail �� ����������!');
	}
	else echo Form(0);
?>
        </form>
		</table></td>
        <td width="44%" align="right" valign="top" class="text" style="border-left:1px solid #FFFFFF;"><p>����� ������� �� ������ &quot;������� ����� ������&quot; ����� ����������� ������� �������� �� ��� e-mail ��������� � ����� �������.</p>
          </td>
        </tr>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>