<?php
$title = 'Добавление нового [изменение] продукта';
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

	if($protection->get['id']){
	$id = $protection->get['id'];
	$str_sql_query = "SELECT * FROM items WHERE id='$id'";
	$result = mysql_query($str_sql_query);
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
//		$name = mysql_result($result,$i,"name");
		$name = str_replace('"','&quot;',mysql_result($result,$i,"name"));
		$from = mysql_result($result,$i,"is_from");
		$category = mysql_result($result,$i,"category");
		$month = mysql_result($result,$i,"month");
		$year = mysql_result($result,$i,"urozhay");
		$desc = mysql_result($result,$i,"description");
		$zakup = mysql_result($result,$i,"price_1");
		$rozn = mysql_result($result,$i,"price_2");
		$sale = mysql_result($result,$i,"sale");
		$status = mysql_result($result,$i,"status");
		$ed_izmer = mysql_result($result,$i,"ed_izmer");
		$lot = mysql_result($result,$i,"lot");
		$photo = mysql_result($result,$i,"photo_vk");
		$tags = mysql_result($result,$i,"tags");
		}
		else $id = 0;
	}
$str_sql_query = "SELECT * FROM items AS i WHERE i.is_from > '0' ORDER BY i.is_from ASC";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if($number){
	$i = 0;
	$from_arr[0] = mysql_result($result,$i,"i.is_from");
		while($i < $number){
		$is_from = mysql_result($result,$i,"i.is_from");
		$n = 0;
			foreach($from_arr as $value){
				if($is_from == $value){
				$n++;
				}
			}
			if(!$n) array_push($from_arr, $is_from);
		$i++;
		}
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
  <div class="hdr2" style="background-position:0 0"><?php if($id) echo 'Изменить продукт'; else echo 'Добавить продукт'; ?>:</div>
</div>
<div id="data"><table width="100%" border="0" cellpadding="10" cellspacing="0" id="err">
<form name="form" method="post" action="change_item.php<?php if($id) echo '?id='.$id; ?>">
<tr class="r1">
  <td colspan="2"><b>поля, отмеченные (*), являются обязательными</b></td>
  </tr>
<tr class="r2">
  <td width="25%"><b>(*)</b>Наименование</td>
  <td width="75%"><input name="name" type="text" size="80"<?php if($name) echo ' value="'.$name.'"'; ?>></td>
</tr>
  <tr class="r1">
    <td>Происхождение</td>
    <td><select onchange="javascript:setfield(document.form);" name="from">
<?php
	if(is_array($from_arr)){
		for($i=0; $i<count($from_arr); $i++){
			if($from_arr[$i]!='Не известно'){
			echo '<option value="'.$from_arr[$i].'"';
				if($from_arr[$i]==$from){
				echo ' selected';
				}
			echo '>'.$from_arr[$i].'</option>';
			}
		}
	}
?>
      <option value="Не известно"<?php if((!$from)||($from=='Не известно')) echo ' selected'; ?>>Не известно</option>
      <option value="0">Другое [указать]</option>
    </select>
      другое:
      <input disabled="true" name="other_from" type="text" id="other_from" size="44" maxlength="80"></td>
  </tr>
  <tr class="r2">
    <td>Категория</td>
    <td><select name="category">
      <option value="0"<?php if(!$category) echo ' selected'; ?>>Не выбрана</option>
      <option value="1"<?php if($category==1) echo ' selected'; ?>>Овощи/Фрукты</option>
      <option value="2"<?php if($category==2) echo ' selected'; ?>>Семена/Орехи</option>
      <option value="3"<?php if($category==3) echo ' selected'; ?>>Зелень/Другое</option>
    </select></td>
  </tr>
  <tr class="r1">
    <td>Урожай</td>
    <td>месяц:    
    <select name="month">
      <option value="0"<?php if(!$month) echo ' selected'; ?>>Не известно</option>
      <option value="1"<?php if($month==1) echo ' selected'; ?>>Январь</option>
      <option value="2"<?php if($month==2) echo ' selected'; ?>>Февраль</option>
      <option value="3"<?php if($month==3) echo ' selected'; ?>>Март</option>
      <option value="4"<?php if($month==4) echo ' selected'; ?>>Апрель</option>
      <option value="5"<?php if($month==5) echo ' selected'; ?>>Май</option>
      <option value="6"<?php if($month==6) echo ' selected'; ?>>Июнь</option>
      <option value="7"<?php if($month==7) echo ' selected'; ?>>Июль</option>
      <option value="8"<?php if($month==8) echo ' selected'; ?>>Август</option>
      <option value="9"<?php if($month==9) echo ' selected'; ?>>Сентябрь</option>
      <option value="10"<?php if($month==10) echo ' selected'; ?>>Октябрь</option>
      <option value="11"<?php if($month==11) echo ' selected'; ?>>Ноябрь</option>
      <option value="12"<?php if($month==12) echo ' selected'; ?>>Декабрь</option>
    </select>
    год:
    <select name="year">
      <option value="0"<?php if(!$year) echo ' selected'; ?>>Не известно</option>
<?php
	if((0<$year)&&($year<(date("Y")-5))) echo '<option value="'.$year.'" selected>'.$year.'</option>';
?>
      <option value="<?php echo date("Y")-5; ?>"<?php if($year==(date("Y")-5)) echo ' selected'; ?>><?php echo date("Y")-5; ?></option>
      <option value="<?php echo date("Y")-4; ?>"<?php if($year==(date("Y")-4)) echo ' selected'; ?>><?php echo date("Y")-4; ?></option>
      <option value="<?php echo date("Y")-3; ?>"<?php if($year==(date("Y")-3)) echo ' selected'; ?>><?php echo date("Y")-3; ?></option>
      <option value="<?php echo date("Y")-2; ?>"<?php if($year==(date("Y")-2)) echo ' selected'; ?>><?php echo date("Y")-2; ?></option>
      <option value="<?php echo date("Y")-1; ?>"<?php if($year==(date("Y")-1)) echo ' selected'; ?>><?php echo date("Y")-1; ?></option>
      <option value="<?php echo date("Y"); ?>"<?php if($year==date("Y")) echo ' selected'; ?>><?php echo date("Y"); ?></option>
    </select></td>
  </tr>
  <tr class="r2">
    <td><b>(*)</b>Кооперативная цена </td>
    <td><input name="zakup" type="text" size="8"<?php if($zakup) echo ' value="'.$zakup.'"'; ?>> 
      за 
        <select name="ed_izmer">
          <option value="0"<?php if(!$ed_izmer) echo ' selected'; ?>></option>
          <option value="1"<?php if($ed_izmer=='1') echo ' selected'; ?>>1 кг</option>
          <option value="2"<?php if($ed_izmer=='2') echo ' selected'; ?>>100 гр</option>
          <option value="3"<?php if($ed_izmer=='3') echo ' selected'; ?>>1 упаковка</option>
          <option value="4"<?php if($ed_izmer=='4') echo ' selected'; ?>>1 шт.</option>
                        </select></td>
  </tr>
  <tr class="r1">
    <td><b>(*)</b>Розничная цена</td>
    <td><input name="rozn" type="text" size="8"<?php if($rozn) echo ' value="'.$rozn.'"'; ?>></td>
  </tr>
  <tr class="r2">
    <td>Уценка</td>
    <td><input name="sale" type="checkbox" id="sale" value="1"<?php if($sale) echo ' checked'; ?>></td>
  </tr>
  <tr class="r1">
    <td><b>(*)</b>Количество в одном лоте</td>
    <td><input name="lot" type="text" size="3"<?php if($lot) echo ' value="'.$lot.'"'; ?>></td>
  </tr>
  <tr class="r2">
    <td>Наличие</td>
    <td><select name="status">
      <option value="1"<?php if($status=='1') echo ' selected'; ?>>Есть в наличии</option>
      <option value="2"<?php if($status=='2') echo ' selected'; ?>>Количество ограничено</option>
      <option value="3"<?php if($status=='3') echo ' selected'; ?>>Ожидается скоро</option>
      <option value="4"<?php if($status=='4') echo ' selected'; ?>>Временно отсутствует</option>
            </select></td>
  </tr>
<!--
  <tr class="r2">
    <td>Фото в контакте (только цифры) </td>
    <td><input name="photo" type="text" id="photo" size="40"<?php // if($photo) echo ' value="'.$photo.'"'; ?>>
      например: 13592926_280920208</td>
  </tr>
-->
  <tr class="r1">
    <td width="25%">Описание</td>
    <td width="75%"><textarea name="desc" cols="50" rows="10"><?php if($desc) echo $desc; ?></textarea></td>
  </tr>
  <tr class="r2">
    <td width="25%">Ключевики</td>
    <td width="75%"><textarea name="tags" cols="50" rows="10"><?php if($tags) echo $tags; ?></textarea></td>
  </tr>
  <tr class="r1">
    <td width="25%">&nbsp;</td>
    <td width="75%"><input type="submit" name="<?php if($id) echo 'change_item'; else echo 'insert_item'; ?>" value="<?php if($id) echo 'Сохранить изменения'; else echo 'Добавить'; ?>"></td>
  </tr>
</form>
</table>
</div>
<?php
include ('templates/footer.tpl');
?>