<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

	if($_COOKIE['referer']){
	$referer = $_COOKIE['referer'];
	$_SESSION['referer'] = $_COOKIE['referer'];
	}
	elseif($protection->get['referer']){
	$referer = $protection->get['referer'];
	$_SESSION['referer'] = $referer;
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $referer, $end_cookie, '/');
	}
	elseif(isset($_SESSION['referer'])) $referer = $_SESSION['referer'];
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
function InsertDB($email,$pass,$referer,$up_uin,$site,$root,$mail_host){
$x=0;
	if(!$referer) $referer = $up_uin;

	if($referer){
	$str_sql_query = "SELECT * FROM customers WHERE uin = '$referer' AND approve = '1'";
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if(!$number){
		$r = mt_rand(1,4);
			switch($r){
//	������� �����
			case 1: $referer = 1088;
			break;
//	������ ����
			case 2: $referer = 2079;
			break;
//	������� ���������
			case 3: $referer = 1056;
			break;
//	����� ����
			case 4: $referer = 2228;
			break;
			}
//		$referer = $_SESSION['referer'];
		}
	}
	else{
	$r = mt_rand(1,4);
		switch($r){
//	������� �����
		case 1: $referer = 1088;
		break;
//	������ ����
		case 2: $referer = 2079;
		break;
//	������� ���������
		case 3: $referer = 1056;
		break;
//	����� ����
		case 4: $referer = 2228;
		break;
		}
//	$referer = $_SESSION['referer'];
	}
	
$city = $_SESSION['city'];
$country = $_SESSION['country'];

$cod_activate = mt_rand(10000,99999);
$sql_query = "INSERT INTO customers (e_mail_1,e_mail_2,password,up_uin,city,cod_activate,country) VALUES ('$email','$email','$pass','$referer','$city','$cod_activate','$country')";
	if(@mysql_query($sql_query)){
	$customer_id = mysql_insert_id();
	$subject = '������ ��� ������������� ����������� �� ����� '.$site;
	$message = '�� ��� ���-�� �� ������ ����� ����������������� �� ����� '.$root.' 
����� ����������� �����������, �������� �� ���� ������:
		
'.$root.'reg.php?id='.$customer_id.'&cod='.$cod_activate.'
		
��� ������������� �������� �� ��� ������, �.�. ��� ������������� �������. 
� ���������, ������� '.$site;
		if(send_mime_mail($site,$mail_host,'������ ������������',$email,'CP1251','KOI8-R',$subject,$message)){
		echo ErrorMessage('���������� �� �����������! �� ��� e-mail ('.$email.') ���������� ������. ��� ��������� �������� �������� �� ������, ��������� � ���������.');
		$x++;
		}
		else echo ErrorMessage('�� ������� ��������� ������ ��� ������������� ����������� <nobr>=(</nobr>. �������� ��������� ��������� ����� ����� <a href="recall.php" class="default">����� ����������� ������</a>.');
	}
	else echo ErrorMessage('�� ������� ���������������� ������ ������������. ��������, ������ ���� ������.');
return $x;
}
function UpdCust($id,$pass,$email,$site,$mail_host,$root){
$new_pass = $pass;
	if(!$pass){
	$length = 8;
	$new_pass = passgen($length);
	}
$md5_pass = md5($new_pass);
$sql_query = "UPDATE customers SET password = '$md5_pass', approve = '1' WHERE uin = '$id'";

	if(@mysql_query($sql_query)){
	
	$subject = '���������� � ����� �������� �� ����� '.$site;
	$message = '���� ������ ��� �����������:
			
	UIN:		'.$id.'
	������:		'.$new_pass.'

URL ������ ��� ����������� ����� ������������� �� ������ �����:

'.$root.'u'.$id.'

�� ������ �������� ������ � ���������� � ������ ����������.
��� ������������� �������� �� ��� ������, �.�. ��� ������������� �������.

� ���������, ������� '.$site;
		if($pass){
		send_mime_mail($site,$mail_host,'������ ������������',$email,'CP1251','KOI8-R',$subject,$message);
		echo ErrorMessage('���������� �� ����������� � �������. ��� ���������� � ���������� ���������� ����� ��������� ���������.<br>
���� ������ ��� ����������� (�������� � ���������):<br><br>
UIN: '.$id.'<br>
������: '.$new_pass.'
<p>URL ������ ��� ����������� ����� ������������� �� ������ �����:</p>
<p>'.$root.'u'.$id.'</p>
<p>�� ������ �������� ������ � ���������� � ������ ����������.</p>
<p>� ���������, ������� '.$site.'</p>');
		}
		elseif(send_mime_mail($site,$mail_host,'������ ������������',$email,'CP1251','KOI8-R',$subject,$message)) echo ErrorMessage('���������� �� ����������� � �������. ��� ���������� � ���������� ���������� ����� ��������� ���������.<br>
���� ������ ��� ����������� (�������� � ���������):<br><br>
UIN: '.$id.'<br>
������: '.$new_pass.'
<p>URL ������ ��� ����������� ����� ������������� �� ������ �����:</p>
<p>'.$root.'u'.$id.'</p>
<p>�� ������ �������� ������ � ���������� � ������ ����������.</p>
<p>� ���������, ������� '.$site.'</p>');
		else echo ErrorMessage('�� ������� ������� ������ � ������ ������� ��� ����������� �� ��� e-mail. ����������, �������������� <a href="recall.php" class="default">������ ����������� ������</a>.<br>
���������� �� ����������� � �������. ��� ���������� � ���������� ���������� ����� ��������� ���������.<br>
���� ������ ��� ����������� (�������� � ���������):<br><br>
UIN: '.$id.'<br>
������: '.$new_pass.'
<p>URL ������ ��� ����������� ����� ������������� �� ������ �����:</p>
<p>'.$root.'u'.$id.'</p>
<p>�� ������ �������� ������ � ���������� � ������ ����������.</p>
<p>� ���������, ������� '.$site.'</p>');
	}
	else echo ErrorMessage('�� ������� ��������� ����������� ������ ������������. ����������, ���������� �������. �������.');
return true;
}
function Form($email,$referer,$pass_1,$pass_2,$uin) {
$str = '<tr>
            <td width="100%" align="right" class="text">����������� e-mail (������������ ����):</td>
            <td align="right"><input name="email" type="text"';
	if($email) $str .= ' value="'.$email.'"';
$str .= ' size="30"></td>
          </tr>';
	if(!$referer){
$str .= '<tr>
            <td width="100%" align="right" class="text">UIN �������� (���� ������):</td>
            <td align="right"><input name="uin" type="text"';
	if($uin) $str .= ' value="'.$uin.'"';
$str .= ' size="30"></td>
          </tr>';
	}
$str .= '<tr>
            <td align="right" class="text">������:</td>
            <td align="right"><input name="pass_1" type="password"';
	if($pass_1) $str .= ' value="'.$pass_1.'"';
$str .= ' size="30"></td>
          </tr>
          <tr>
            <td align="right" class="text">��������� ������:</td>
            <td align="right"><input name="pass_2" type="password"';
	if($pass_2) $str .= ' value="'.$pass_2.'"';
$str .= ' size="30"></td>
          </tr>
          <tr>
		    <td width="100%" align="right" class="text">������� ������ &quot;������������������&quot;, �� ������������ � <a href="rule.php">��������� �������</a> � ������� </td>
            <td align="right"><input name="registre" type="submit" id="registre" value="������������������"></td>
          </tr>';
return $str;
}
function ErrorMessage($message) {
return '<tr bgcolor="#B7C9E1">
            <td colspan="2" align="right" class="error">'.$message.'</td>
          </tr>';
}
?>
<?php
include ('templates/header.tpl');
?>

<title>���� ���: ����� ���� �� �������� �������. ��������������� �����������.</title>
</head>

<body>
<table width="1000" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_left"><h1>��������������� ���������� &quot;���� ���&quot;</h1>
	<h2>������������ �������� �� ��������</h2>
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
		<form action="" method="post" name="regnew">
<?php
$id = $protection->get['id'];
$cod = $protection->get['cod'];
	if($protection->post['registre']){
	
	$email = trim($protection->post['email']);
	$pass_1 = trim($protection->post['pass_1']);
	$pass_2 = trim($protection->post['pass_2']);
	$up_uin = trim($protection->post['uin']);
	
		if($email&&preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.[a-zA-Z]{2,4}$/',$email)){
		$str_sql_query = "SELECT uin FROM customers WHERE e_mail_1 = '$email'";
		$result = @mysql_query($str_sql_query, $link);
		$number = @mysql_num_rows($result);
			if(!$number){
				if($pass_1 != $pass_2) echo ErrorMessage('������ � ��������������� ����� ����� ���� ���������! ����� ����� �������� ���� �������. � ���� ������ ������ ����� ������������ ������������� � ������ ��� �� e-mail.');
				elseif($pass_1&&(strlen($pass_1) < 8)) echo ErrorMessage('� ������ ������� ��������� ���������� ��������!');
				elseif($pass_1&&(!preg_match('/^[a-zA-Z0-9]+$/',$pass_1))) echo ErrorMessage('� ������ ������������ ������������ �������!');
				else $x = InsertDB($email,$pass_1,$referer,$up_uin,$site,$SERVER_ROOT,$mail_host);
			}
			else echo ErrorMessage('������������ � ����� e-mail ��� ���� � ���� ������!');
		}
		else echo ErrorMessage('�� ��������� ������������ ���� ���� ��� ��������� �����������!');
		
		if(!$x) echo Form($email,$referer,$pass_1,$pass_2,$up_uin);
	}
	elseif($id&&$cod){
	$str_sql_query = "SELECT approve, cod_activate, password, e_mail_1 FROM customers WHERE uin = '$id'";
	$result = @mysql_query($str_sql_query,$link);
	$number = @mysql_num_rows($result);
		if($number){
		$i = 0;
		$approve = mysql_result($result,$i,"approve");
			if(!$approve){
			$cod_activate = mysql_result($result,$i,"cod_activate");
				if($cod == $cod_activate){
				$pass = mysql_result($result,$i,"password");
				$email = mysql_result($result,$i,"e_mail_1");
				UpdCust($id,$pass,$email,$site,$mail_host,$SERVER_ROOT);
				}
				else echo ErrorMessage('��� ��������� �� ������!');
			}
			else echo ErrorMessage('��� e-mail ��� �����������. �������!');
		}
		else echo ErrorMessage('������������ � ����� UIN � ������� �� ���������������!');
	}
	elseif($protection->get['purchase']){
	echo ErrorMessage('��� ����, ����� ������� ����� ��������� ���������, ���������� ���� ������������������ ������������� � ����� � �������. ��� �����������, ��������� ����� ����. ��� ����� � ������� ������������� ����� ������ ������ ������ ��������.');
	echo Form(0,$referer,0,0,0);
	}
	else echo Form(0,$referer,0,0,0);
?>
		</form>
        </table></td>
        <td width="44%" align="right" valign="top" class="text" style="border-left:1px solid #FFFFFF;"><p>����� ������� �� ������ &quot;������������������&quot; �� ��� e-mail ����� ������� ������ �� ������� ��� ������������� �����������, ������� ���������� ���� &quot;e-mail&quot; �����������. ���� �������� ������������.</p>
          <p>��������� ���� �� �������� ������������� ��� ����������, ������ ���� �� ��������� ���� &quot;������&quot;, �� ���� &quot;��������� ������&quot; ���� ������ ���� ���������.</p>
          <p> � ���� &quot;������&quot; ����������� ������ ��������� ������� �� a �� z � ����� � ����� ������������������. ����� ���������� �������� ������ ���� ������ 8. </p></td>
        </tr>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>