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
//	Климова Ольга
			case 1: $referer = 1088;
			break;
//	Коркин Илья
			case 2: $referer = 2079;
			break;
//	Дмитрий Спакойнай
			case 3: $referer = 1056;
			break;
//	Асеев Илья
			case 4: $referer = 2228;
			break;
			}
//		$referer = $_SESSION['referer'];
		}
	}
	else{
	$r = mt_rand(1,4);
		switch($r){
//	Климова Ольга
		case 1: $referer = 1088;
		break;
//	Коркин Илья
		case 2: $referer = 2079;
		break;
//	Дмитрий Спакойнай
		case 3: $referer = 1056;
		break;
//	Асеев Илья
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
	$subject = 'Ссылка для подтверждения регистрации на сайте '.$site;
	$message = 'Вы или кто-то от вашего имени зарегистрировался на сайте '.$root.' 
Чтобы подтвердить регистрацию, пройдите по этой ссылке:
		
'.$root.'reg.php?id='.$customer_id.'&cod='.$cod_activate.'
		
Вам необязательно отвечать на это письмо, т.к. оно сгенерировано роботом. 
С уважением, команда '.$site;
		if(send_mime_mail($site,$mail_host,'Новому пользователю',$email,'CP1251','KOI8-R',$subject,$message)){
		echo ErrorMessage('Благодарим за регистрацию! На ваш e-mail ('.$email.') отправлено письмо. Для активации аккаунта пройдите по ссылке, указанной в сообщении.');
		$x++;
		}
		else echo ErrorMessage('Не удалось отправить письмо для подтверждения регистрации <nobr>=(</nobr>. Повторно запросить сообщение можно через <a href="recall.php" class="default">форму напоминания пароля</a>.');
	}
	else echo ErrorMessage('Не удалось зарегистрировать нового пользователя. Возможно, ошибка Базы Данных.');
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
	
	$subject = 'Информация о вашем аккаунте на сайте '.$site;
	$message = 'Ваши данные для авторизации:
			
	UIN:		'.$id.'
	Пароль:		'.$new_pass.'

URL ссылки для привлечения новых пользователей от Вашего имени:

'.$root.'u'.$id.'

Вы можете изменить пароль в настройках в панели управления.
Вам необязательно отвечать на это письмо, т.к. оно сгенерировано роботом.

С уважением, команда '.$site;
		if($pass){
		send_mime_mail($site,$mail_host,'Новому пользователю',$email,'CP1251','KOI8-R',$subject,$message);
		echo ErrorMessage('Благодарим за регистрацию в системе. Для вступления в кооператив необходимо также подписать заявление.<br>
Ваши данные для авторизации (запишите и сохраните):<br><br>
UIN: '.$id.'<br>
Пароль: '.$new_pass.'
<p>URL ссылки для привлечения новых пользователей от Вашего имени:</p>
<p>'.$root.'u'.$id.'</p>
<p>Вы можете изменить пароль в настройках в панели управления.</p>
<p>С уважением, команда '.$site.'</p>');
		}
		elseif(send_mime_mail($site,$mail_host,'Новому пользователю',$email,'CP1251','KOI8-R',$subject,$message)) echo ErrorMessage('Благодарим за регистрацию в системе. Для вступления в кооператив необходимо также подписать заявление.<br>
Ваши данные для авторизации (запишите и сохраните):<br><br>
UIN: '.$id.'<br>
Пароль: '.$new_pass.'
<p>URL ссылки для привлечения новых пользователей от Вашего имени:</p>
<p>'.$root.'u'.$id.'</p>
<p>Вы можете изменить пароль в настройках в панели управления.</p>
<p>С уважением, команда '.$site.'</p>');
		else echo ErrorMessage('Не удалось выслать письмо с вашими данными для авторизации на ваш e-mail. Пожалуйста, воспользуйтесь <a href="recall.php" class="default">формой напоминания пароля</a>.<br>
Благодарим за регистрацию в системе. Для вступления в кооператив необходимо также подписать заявление.<br>
Ваши данные для авторизации (запишите и сохраните):<br><br>
UIN: '.$id.'<br>
Пароль: '.$new_pass.'
<p>URL ссылки для привлечения новых пользователей от Вашего имени:</p>
<p>'.$root.'u'.$id.'</p>
<p>Вы можете изменить пароль в настройках в панели управления.</p>
<p>С уважением, команда '.$site.'</p>');
	}
	else echo ErrorMessage('Не удалось закончить регистрацию нового пользователя. Пожалуйста, попробуйте позднее. Спасибо.');
return true;
}
function Form($email,$referer,$pass_1,$pass_2,$uin) {
$str = '<tr>
            <td width="100%" align="right" class="text">Действующий e-mail (обязательное поле):</td>
            <td align="right"><input name="email" type="text"';
	if($email) $str .= ' value="'.$email.'"';
$str .= ' size="30"></td>
          </tr>';
	if(!$referer){
$str .= '<tr>
            <td width="100%" align="right" class="text">UIN реферера (если знаете):</td>
            <td align="right"><input name="uin" type="text"';
	if($uin) $str .= ' value="'.$uin.'"';
$str .= ' size="30"></td>
          </tr>';
	}
$str .= '<tr>
            <td align="right" class="text">Пароль:</td>
            <td align="right"><input name="pass_1" type="password"';
	if($pass_1) $str .= ' value="'.$pass_1.'"';
$str .= ' size="30"></td>
          </tr>
          <tr>
            <td align="right" class="text">Повторите пароль:</td>
            <td align="right"><input name="pass_2" type="password"';
	if($pass_2) $str .= ' value="'.$pass_2.'"';
$str .= ' size="30"></td>
          </tr>
          <tr>
		    <td width="100%" align="right" class="text">Нажимая кнопку &quot;Зарегистрироваться&quot;, вы соглашаетесь с <a href="rule.php">условиями участия</a> в системе </td>
            <td align="right"><input name="registre" type="submit" id="registre" value="Зарегистрироваться"></td>
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

<title>Ирий Сад: живая пища от сыроедов городов. Предварительная регистрация.</title>
</head>

<body>
<table width="1000" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_left"><h1>Потребительский кооператив &quot;Ирий Сад&quot;</h1>
	<h2>качественные продукты от сыроедов</h2>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo 'Тел.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">выбрать другой город</a></p></td>
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
        <td width="12%" align="right" valign="top" bgcolor="#B7C9E1" class="submenu">Регистрация нового члена клуба</td>
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
				if($pass_1 != $pass_2) echo ErrorMessage('Пароли в соответствующих полях дожны быть одинаковы! Также можно оставить поля пустыми. В этом случае пароль будет сгенерирован автоматически и выслан вам на e-mail.');
				elseif($pass_1&&(strlen($pass_1) < 8)) echo ErrorMessage('В пароле слишком маленькое количество символов!');
				elseif($pass_1&&(!preg_match('/^[a-zA-Z0-9]+$/',$pass_1))) echo ErrorMessage('В пароле присутствуют недопустимые символы!');
				else $x = InsertDB($email,$pass_1,$referer,$up_uin,$site,$SERVER_ROOT,$mail_host);
			}
			else echo ErrorMessage('Пользователь с таким e-mail уже есть в Базе Данных!');
		}
		else echo ErrorMessage('Не заполнены обязательные поля либо они заполнены некорректно!');
		
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
				else echo ErrorMessage('Код активации не верный!');
			}
			else echo ErrorMessage('Ваш e-mail уже подтвержден. Спасибо!');
		}
		else echo ErrorMessage('Пользователь с таким UIN в системе не зарегистрирован!');
	}
	elseif($protection->get['purchase']){
	echo ErrorMessage('Для того, чтобы сделать заказ выбранных продуктов, необходимо быть зарегистрированным пользователем и войти в систему. Для регистрации, заполните форму ниже. Для входа в аккаунт предназначена форма вверху справа данной страницы.');
	echo Form(0,$referer,0,0,0);
	}
	else echo Form(0,$referer,0,0,0);
?>
		</form>
        </table></td>
        <td width="44%" align="right" valign="top" class="text" style="border-left:1px solid #FFFFFF;"><p>После нажатия на кнопку &quot;Зарегистрироваться&quot; на ваш e-mail будет выслано письмо со ссылкой для подтверждения регистрации, поэтому заполняйте поле &quot;e-mail&quot; внимательно. Поле является обязательным.</p>
          <p>Остальные поля не являются обязательными для заполнения, однако если вы заполнили поле &quot;Пароль&quot;, то поле &quot;Повторите пароль&quot; тоже должно быть заполнено.</p>
          <p> В поле &quot;Пароль&quot; допускаются только латинские символы от a до z и цифры в любой последовательности. Общее количество символов должно быть больше 8. </p></td>
        </tr>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>