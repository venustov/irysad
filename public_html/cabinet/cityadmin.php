<?php
$title = 'Настройка города';
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

$city = $protection->post['city'];
	if(!$city) $city = $_SESSION['city'];
	
	if($protection->post['change_city']&&$protection->post['other_city']){
	$other_city = $protection->post['other_city'];
	$str_sql_query = 'INSERT INTO cities (name) VALUES (\''.$other_city.'\')';
	@mysql_query($str_sql_query);
	$id = mysql_insert_id();
	$city = $other_city;
	}
	
	if($_SESSION['logged_status'] > 1){
	$str_sql_query = 'SELECT * FROM cities WHERE name = "'.$city.'"';
	$result = mysql_query($str_sql_query);
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
	
		$id = mysql_result($result,$i,"id");
		$about = mysql_result($result,$i,"dostavka");
//		$about = str_replace('"','\"',mysql_result($result,$i,"dostavka"));
		$icq = mysql_result($result,$i,"icq");
		$phone = mysql_result($result,$i,"phone");
		$skype = mysql_result($result,$i,"skype");
		$country = mysql_result($result,$i,"country");
		$adm_uin = mysql_result($result,$i,"admin");
		$contacts = mysql_result($result,$i,"contacts");
		
		$contacts = str_replace('<img1>','<a href="../images/',$contacts);
		$contacts = str_replace('<img2>','" target="_blank"><img src="../images/',$contacts);
		$contacts = str_replace('<img3>','" alt="',$contacts);
		$contacts = str_replace('<img4>','" title="',$contacts);
		$contacts = str_replace('<img5>','" width=300 border="0"></a>',$contacts);
		
		$contacts = str_replace('[[','<a href="',$contacts);
		$contacts = str_replace('|','">',$contacts);
		$contacts = str_replace(']]','</a>',$contacts);

		}
	}

	if((!is_array($city_arr))&&($_SESSION['logged_status'] >= 4)){
	$str_sql_query = 'SELECT * FROM cities ORDER BY name ASC';
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
			while($i < $number){
			$name = mysql_result($result,$i,"name");
			$city_arr[] = $name;
			$i++;
			}
		}
	}
?>
<script language="JavaScript"> 
<!--//
function setfield(frmname){
var mt=frmname.other_city;
var ct=frmname.city;
if (ct.value==1){
mt.disabled=false;
}
else{
mt.disabled=true; 
}
}
//-->
</script>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Информация по вашему городу (изменение):</div>
</div>
<div id="data"><table width="100%" border="0" cellpadding="10" cellspacing="0">
<?php
	if($protection->get['err']){
	echo '<tr class="r2">
    <td colspan="3" align="center"><b>';
		if($protection->get['err'] == 1) echo 'Ошибка #'.$protection->get['err'].': Ошибка при записи в Базу Данных';
/*		elseif($protection->get['err'] == 2){
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
		}*/
	echo '</b></td>
    </tr>';
	}
	if($_SESSION['logged_status'] >= 4) echo '<form name="change_city" method="post" action="">';
?>
  <tr class="r1">
    <td width="20%">Город:</td>
    <td width="20%"><b><?php echo $city; ?></b></td>
	<td width="60%">
<?php
	if(is_array($city_arr)){
	echo '<select onchange="javascript:setfield(document.change_city);" name="city" id="city">';
		for($i=0; $i<count($city_arr); $i++){
		echo '<option value="'.$city_arr[$i].'"';
			if($city==$city_arr[$i]) echo ' selected';
		echo '>'.$city_arr[$i].'</option>';
		}
	echo '
        <option value="1">Другой [указать]</option></select>
    другой:
    <input disabled="true" name="other_city" id="other_city" type="text" size="27" maxlength="36">
    <input name="change_city" type="submit" value="Применить">';
	}
	else echo '&nbsp;';
?>
    </td>
  </tr>
<?php
	if($_SESSION['logged_status'] >= 4) echo '</form>';
?>
<form name="info_city" method="post" action="change_city.php">
<?php	
	if($_SESSION['logged_status'] >= 4){
	echo '
  <tr class="r2">
    <td>UIN админа:</td>
    <td><b>'.$adm_uin.'</td>
    <td><input name="adm_uin" type="text" id="adm_uin" size="6" maxlength="25"></td>
  </tr>';
	}
?>
  <tr class="r2">
    <td>Страна:</td>
    <td><b><?php echo $country; ?></b></td>
    <td>
<?php
	if($_SESSION['logged_status'] >= 4) echo '<input name="country" type="text" id="country" size="20" maxlength="50">';
	else echo '&nbsp;';
?></td>
  </tr>
  <tr class="r1">
    <td>#ICQ:</td>
    <td><b><?php echo $icq; ?></b></td>
    <td><input name="icq" type="text" id="icq" size="10" maxlength="20"></td>
  </tr>
  <tr class="r2">
    <td>Телефон:</td>
    <td><b><?php echo $phone; ?></b></td>
    <td><input name="phone" type="text" id="phone" size="15" maxlength="15"></td>
  </tr>
  <tr class="r1">
    <td>Skype:</td>
    <td><b><?php echo $skype; ?></b></td>
    <td><input name="skype" type="text" id="skype" size="30" maxlength="50"></td>
  </tr>
  <tr class="r2">
    <td valign="top">Доставка и оплата: </td>
    <td valign="top"><b><?php echo $about; ?></b></td>
    <td valign="top"><textarea name="about" cols="45" rows="36"><?php echo $about; ?></textarea></td>
  </tr>
  <tr class="r1">
    <td valign="top">Контактная информация: </td>
    <td valign="top"><b><?php echo $contacts; ?></b></td>
    <td valign="top"><textarea name="contacts" cols="45" rows="36"><?php echo $contacts; ?></textarea></td>
  </tr>
  <tr class="r2">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
	 <input name="id" type="hidden" value="<?php echo $id; ?>">
	 <input name="city" type="hidden" value="<?php echo $city; ?>">
	 <input name="admin_old" type="hidden" value="<?php echo $adm_uin; ?>">
	 <input name="country_old" type="hidden" value="<?php echo $country; ?>">
	 <input name="icq_old" type="hidden" value="<?php echo $icq; ?>">
	 <input name="phone_old" type="hidden" value="<?php echo $phone; ?>">
	 <input name="skype_old" type="hidden" value="<?php echo $skype; ?>">
	 <input name="about_old" type="hidden" value="<?php echo $about; ?>">
	 <input name="contacts_old" type="hidden" value='<?php echo $contacts; ?>'>
	 <input type="submit" name="change_city" value="Сохранить изменения">
	</td>
  </tr>
  <tr class="r2">
    <td>вставить изображение: </td>
    <td>&nbsp;</td>
    <td><b>&lt;img1&gt;имя файла&lt;img2&gt;имя файла&lt;img3&gt;alt&lt;img4&gt;title&lt;img5&gt;</b></td>
  </tr>
  <tr class="r2">
    <td>вставить внутреннюю ссылку:</td>
    <td>&nbsp;</td>
    <td><b>[[link|title]]</b></td>
  </tr>
</form>
</table>
</div>
<?php
include ('templates/footer.tpl');
?>