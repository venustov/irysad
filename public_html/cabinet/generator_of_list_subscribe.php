<?php
$title = 'Создание базы данных адресов рассылки';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
?>
<?php
include ('templates/header.tpl');
?>
<?php
require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

require ('../supple/auth_db.php');
require ('../supple/normal_date.php');
//require ('../supple/translit.php');

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
	
	if($protection->post['submit_choice']){
	
	$approve = $protection->post['approve'];
	$status = $protection->post['status'];
	$name = $protection->post['name'];
	$city = $protection->post['city'];
	$zahod = $protection->post['zahod'];
		if(!$zahod) $zahod = 1;
		
	$str_sql_query = 'SELECT * FROM customers WHERE e_mail_1 != "info@irysad.com"';
	
		if($approve<2) $str_sql_query.=' AND approve = '.$approve;
		
		if($status<5) $str_sql_query.=' AND status = "'.$status.'"';
		
		if(!$name) $str_sql_query.=' AND name_2 IS NOT NULL';
		elseif($name==1) $str_sql_query.=' AND name_2 IS NULL';
		
		if($city) $str_sql_query.=' AND city = "'.$city.'"';
	
	$str_sql_query.=' ORDER BY uin DESC';
		
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
	
	$quant = 500;
	$begin = (($zahod - 1)*$quant);
		if(!$number) echo $str_sql_query.'В Базе Данных больше нет данных, удовлетворяющих вашему запросу!';
		elseif($number>($zahod*$quant)){
		$end = $zahod*$quant;
		echo 'Выведено '.($end-$begin).' из '.$number.' записей';
		}
		else{
		$end = $number;
		echo 'Выведено '.$number.' из '.$number.' записей';
		}
	}
	elseif($protection->post['generate_new']||$protection->post['generate_add']){
	
		if($protection->post['generate_new']) $fp = fopen('../subscribe/emails.txt','w');
		else $fp = fopen('../subscribe/emails.txt','a');
		
	$customers = $protection->post['customer'];
	$q=0;
		if(is_array($customers)){
			foreach($customers as $key => $value){
			$test = fwrite($fp,'
');
			$test = fwrite($fp,$value);
			$q++;
			}
		}
		elseif($protection->post['name']&&$protection->post['e_mail']){
		$name = $protection->post['name'];
		$e_mail = $protection->post['e_mail'];
		$value = '
0000,'.date("d.m.Y").','.$name.','.$e_mail.',,,2,00000';
		$test = fwrite($fp,$value);
		$q = 1;
		}
	fclose($fp);
	echo 'В файл записано '.$q.' записей';
	}
?>
<script language="JavaScript"> 
<!--//
function setfield(frmname){
var mt=frmname.other_from;
var ct=frmname.from;
if (ct.value==0){
mt.disabled=false;
}
else{
mt.disabled=true; 
}
}
//-->
</script>

<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Генерация листа рассылки:</div>
</div>
<div id="data"><table width="100%" border="0" cellpadding="10" cellspacing="0" id="choice">
<form name="choice" method="post" action="">
<tr class="r2">
  <td width="25%"><span style="font-weight: bold">Статус подтверждения</span></td>
  <td width="75%"><select name="approve">
    <option value="0">Только неподтверждённые</option>
    <option value="1">Только подтверждённые</option>
    <option value="2" selected>Все без исключения</option>
  </select></td>
</tr>
  <tr class="r2">
    <td><span style="font-weight: bold">Статус управления</span></td>
    <td><select name="status">
      <option value="0">Только регистрация</option>
      <option value="1">Держатели клубной карты и пайщики</option>
      <option value="2">Админы</option>
      <option value="3">Управляющие</option>
      <option value="4">Директор</option>
      <option value="5" selected>Все без исключения</option>
    </select></td>
  </tr>
  <tr class="r2">
    <td><span style="font-weight: bold">Наличие Имени </span></td>
    <td><select name="name">
      <option value="0">Есть имя</option>
      <option value="1">Нет имени</option>
      <option value="2" selected>Все без исключения</option>
    </select></td>
  </tr>
  <tr class="r2">
    <td><span style="font-weight: bold">Город</span></td>
	<td>
<?php
	if(is_array($city_arr)){
	echo '<select name="city" id="city">';
		for($i=0; $i<count($city_arr); $i++){
		echo '<option value="'.$city_arr[$i].'"';
			if($city==$city_arr[$i]) echo ' selected';
		echo '>'.$city_arr[$i].'</option>';
		}
	echo '
        <option value="0"';
		if(!$city) echo ' selected';
	echo '>Не важно</option></select>';
	}
	else echo '&nbsp;';
?>
    </td>
  </tr>
  <tr class="r2">
    <td><span style="font-weight: bold">Заход</span></td>
    <td><input name="zahod" type="text" id="zahod" size="3" maxlength="2"></td>
  </tr>
  <tr class="r2">
    <td width="25%">&nbsp;</td>
    <td width="75%"><input type="submit" name="submit_choice" value="Произвести выборку из БД"></td>
  </tr>
</form>
</table>

<table width="100%" border="0" cellpadding="10" cellspacing="0" id="list">
<form name="generate" method="post" action="">
  <thead>
  <tr>
    <td>&nbsp;</td>
    <td><span style="font-weight: bold">UIN</span></td>
    <td width="35%"><span style="font-weight: bold">Имя </span></td>
    <td width="30%"><span style="font-weight: bold">e-mail</span></td>
  </tr>
  </thead>
<?php
	if($protection->post['submit_choice']){
		if($number>=($quant*($zahod-1))){
/* 		echo $str_sql_query;
		echo $number;*/

		$i = $begin;
			while($i < $end){
			$uin = mysql_result($result,$i,"uin");
			$name_1 = mysql_result($result,$i,"name_1");
			$name_2 = mysql_result($result,$i,"name_2");
			$e_mail = mysql_result($result,$i,"e_mail_2");
			$cod = mysql_result($result,$i,"cod_activate");
			$data = NormalDate(mysql_result($result,$i,"date_reg"));
			$str .= '
  <tr class="r1">
    <td><div align="center">
      <input name="customer[]" type="checkbox" value="'.$uin.','.$data.','.$name_2.','.$e_mail.',,,2,'.$cod.'" checked>
    </div></td>
    <td>'.$uin.'</td>
    <td>'.$name_1.' '.$name_2.'</td>
    <td>'.$e_mail.'</td>
  </tr>
';
			$i++;
			}
		}
	}
	else $str .= '
  <tr class="r1">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="name" type="text" id="name"></td>
    <td><input name="e_mail" type="text" id="e_mail"></td>
  </tr>
';
echo $str;
?>
  <tr class="r2">
    <td>&nbsp;</td>
    <td><span style="font-weight: bold">Записи будут добавлены к существующим! </span></td>
    <td colspan="2"><input type="submit" name="generate_add" id="generate_add" value="Добавить в лист рассылки"></td>
    </tr>
  <tr class="r2">
    <td width="5%">&nbsp;</td>
    <td width="30%"><b>Существующий файл будет очищен!</b></td>
    <td colspan="2"><input type="submit" name="generate_new" id="generate_new" value="Записать в лист рассылки"></td>
    </tr>
</form>
</table>

</div>
<?php
include ('templates/footer.tpl');
?>