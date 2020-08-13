<?php
$title = 'Персональная информация участника проекта';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
?>
<?php
include ('templates/header.tpl');
?>
<?php
require ('../supple/auth_db.php');

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$uin = $_SESSION['logged_user'];
$str_sql_query = "SELECT * FROM customers WHERE uin = '$uin'";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if($number){
	$i = 0;
	
	$name_1 = mysql_result($result,$i,"name_1");
	$name_2 = mysql_result($result,$i,"name_2");
	$name_3 = mysql_result($result,$i,"name_3");
	$icq = mysql_result($result,$i,"icq");
	
	$vkontakte = mysql_result($result,$i,"vkontakte");
	$phone_1 = mysql_result($result,$i,"phone_1");
	$phone_2 = mysql_result($result,$i,"phone_2");
	$skype = mysql_result($result,$i,"skype");
	
	$country = mysql_result($result,$i,"country");
	$city = mysql_result($result,$i,"city");
	$address = mysql_result($result,$i,"address");
	$email = mysql_result($result,$i,"e_mail_2");
	
	$about = mysql_result($result,$i,"about");
	$autopay = mysql_result($result,$i,"autopay");
	}
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Персональная информация (изменение):</div>
</div>
<div id="data"><table width="100%" border="0" cellpadding="10" cellspacing="0">
<form name="form" method="post" action="change_info.php">
<?php
	if($protection->get['err']){
	echo '<tr class="r2">
    <td colspan="4" align="center"><b>';
		if($protection->get['err'] == 1){
		echo 'Ошибка #'.$protection->get['err'].': Ошибка при записи в Базу Данных';
		}
		elseif($protection->get['err'] == 2){
		echo 'Ошибка #'.$protection->get['err'].': Пароли в соответствующих полях дожны быть одинаковы';
		}
		elseif($protection->get['err'] == 3){
		echo 'Ошибка #'.$protection->get['err'].': В пароле слишком маленькое количество символов!';
		}
		elseif($protection->get['err'] == 4){
		echo 'Ошибка #'.$protection->get['err'].': В пароле присутствуют недопустимые символы!';
		}
		elseif($protection->get['err'] == 5){
		echo 'Ошибка #'.$protection->get['err'].': Ошибка при записи в Базу Данных!';
		}
		elseif($protection->get['err'] == 6){
		echo 'Ваш пароль успешно изменен!';
		}
	echo '</b></td>
    </tr>';
	}
?>
  <tr class="r1">
  <td width="20%">UIN:</td>
  <td width="30%"><b><?php echo $uin; ?></b></td>
  <td width="30%">&nbsp;</td>
  <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r2">
    <td width="20%">Фамилия:</td>
    <td width="30%"><b><?php echo $name_1; ?></b></td>
    <td width="30%"><input name="name_1" type="text" id="name_1" size="30" maxlength="50"<?php if($name_1) echo ' value="'.$name_1.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r1">
    <td width="20%">Имя:</td>
    <td width="30%"><b><?php echo $name_2; ?></b></td>
    <td width="30%"><input name="name_2" type="text" id="name_2" size="30" maxlength="50"<?php if($name_2) echo ' value="'.$name_2.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r2">
    <td width="20%">Отчество:</td>
    <td width="30%"><b><?php echo $name_3; ?></b></td>
    <td width="30%"><input name="name_3" type="text" id="name_3" size="30" maxlength="50"<?php if($name_3) echo ' value="'.$name_3.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r1">
    <td width="20%">#ICQ:</td>
    <td width="30%"><b><?php echo $icq; ?></b></td>
    <td width="30%"><input name="icq" type="text" id="icq" size="10" maxlength="20"<?php if($icq) echo ' value="'.$icq.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r2">
    <td width="20%">ID вконтакте.ру:</td>
    <td width="30%"><?php if($vkontakte) echo '<a href="http://vkontakte.ru/id'.$vkontakte.'" target="_blank"><b>'.$vkontakte.'</b></a>'; ?></td>
    <td width="30%"><input name="vkontakte" type="text" id="vkontakte" size="12" maxlength="20"<?php if($vkontakte) echo ' value="'.$vkontakte.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r1">
    <td width="20%">Телефон 1: </td>
    <td width="30%"><b><?php echo $phone_1; ?></b></td>
    <td width="30%"><input name="phone_1" type="text" id="phone_1" size="15" maxlength="15"<?php if($phone_1) echo ' value="'.$phone_1.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r2">
    <td width="20%">Телефон 2: </td>
    <td width="30%"><b><?php echo $phone_2; ?></b></td>
    <td width="30%"><input name="phone_2" type="text" id="phone_2" size="15" maxlength="15"<?php if($phone_2) echo ' value="'.$phone_2.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r1">
    <td width="20%">Skype:</td>
    <td width="30%"><b><?php echo $skype; ?></b></td>
    <td width="30%"><input name="skype" type="text" id="skype" size="30" maxlength="50"<?php if($skype) echo ' value="'.$skype.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r2">
    <td width="20%">Гражданство:</td>
    <td width="30%"><b><?php echo $country; ?></b></td>
    <td width="30%"><input name="country" type="text" id="country" size="20" maxlength="50"<?php if($country) echo ' value="'.$country.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r1">
    <td width="20%">Город:</td>
    <td width="30%"><b><?php echo $city; ?></b></td>
    <td width="30%"><input name="city" type="text" id="city" size="20" maxlength="50"<?php if($city) echo ' value="'.$city.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r2">
    <td width="20%">Адрес:</td>
    <td width="30%"><b><?php echo $address; ?></b></td>
    <td width="30%"><input name="address" type="text" id="address" size="50" maxlength="256"<?php if($address) echo ' value="'.$address.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r1">
    <td width="20%">E-mail:</td>
    <td width="30%"><b><?php echo $email; ?></b></td>
    <td width="30%"><input name="email" type="text" id="email" size="30" maxlength="50"<?php if($email) echo ' value="'.$email.'"'; ?>></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r2">
    <td valign="top">Ваши навыки, которые могли бы быть полезны проекту: </td>
    <td valign="top"><b><?php echo $about; ?></b></td>
    <td valign="top"><textarea name="about" cols="45" rows="6"><?php if($about) echo $about; ?></textarea></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="r1">
    <td valign="top">Членский взнос списывать с баланса автоматически </td>
    <td valign="top"><input name="autopay" type="radio" value="1"<?php if($autopay) echo ' checked'; ?>>
      Да</td>
    <td valign="top"><input name="autopay" type="radio" value="0"<?php if(!$autopay) echo ' checked'; ?>>
      Нет</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="r2">
    <td width="20%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
    <td width="30%"><input type="submit" name="change_info" value="Сохранить изменения"></td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="r1">
    <td>Новый пароль:</td>
    <td>введите:
        <input name="pass1" type="password" id="pass1"></td>
    <td>еще раз:
        <input name="pass2" type="password" id="pass2"></td>
    <td><input name="change_pass" type="submit" id="change_pass" value="Изменить пароль"></td>
  </tr>
</form>
</table>
</div>
<?php
include ('templates/footer.tpl');
?>