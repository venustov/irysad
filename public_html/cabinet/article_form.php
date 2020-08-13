<?php
$title = 'Добавление [изменение] статьи';
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

$err = $protection->get['err'];
	if($err == 1) $msg = 'ПОЛЯ "URL" И "АНКОР" ДОЛЖНЫ БЫТЬ ЗАПОЛНЕНЫ ОБА ИЛИ ОБА БЫТЬ ПУСТЫМИ';
	if($err == 2) $msg = 'В ПОЛЯХ "ЗАГОЛОВОК СТАТЬИ" ИЛИ "АНКОР"<br> ПРИСУТСТВУЮТ НЕДОПУСТИМЫЕ СИМВОЛЫ';
	if($err == 3) $msg = 'НЕ ЗАПОЛНЕНЫ ОБЯЗАТЕЛЬНЫЕ ПОЛЯ';
	
require ('../supple/auth_db.php');

$str_sql_query = "SELECT * FROM articles AS a WHERE a.category IS NOT NULL ORDER BY a.category ASC";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if($number){
	$i = 0;
	$cat_arr[0] = mysql_result($result,$i,"a.category");
		while($i < $number){
		$cat = mysql_result($result,$i,"a.category");
		$n = 0;
			foreach($cat_arr as $value){
				if($cat == $value){
				$n++;
				}
			}
			if(!$n) array_push($cat_arr, $cat);
		$i++;
		}
	}

	if($protection->get['id']){
	$id = $protection->get['id'];
	$str_sql_query = "SELECT * FROM articles WHERE id = '$id'";
	$result = mysql_query($str_sql_query);
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
		$article = mysql_result($result,$i,"title");
		$epigraf = mysql_result($result,$i,"epigraf");
		$desc = mysql_result($result,$i,"description");
		$author = mysql_result($result,$i,"author");
		$maker = mysql_result($result,$i,"maker");
		$approve = mysql_result($result,$i,"approve");
		$link_on = mysql_result($result,$i,"link");
		$ankor = mysql_result($result,$i,"ankor");
		$cat = mysql_result($result,$i,"category");
		$tags = mysql_result($result,$i,"tags");
		}
		else $id = 0;
	}
?>
<script language="JavaScript"> 
<!--//
function setfield(frmname){
var mt=frmname.other_cat;
var ct=frmname.cat;
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
  <div class="hdr2" style="background-position:0 0"><?php if($id) echo 'Изменить статью'; else echo 'Добавить статью'; ?>:</div>
</div>
<div id="data"><table width="100%" border="0" cellpadding="0" cellspacing="0" id="err">
<form name="form" enctype="multipart/form-data" method="post" action="change_article.php<?php if($id) echo '?id='.$id; ?>">
<?php
	if($err) echo '<tr class="r2">
    <td width="25%">Ошибка:</td>
    <td colspan="2"><b>'.$msg.'</b></td>
  </tr>';
	if($_SESSION['logged_status'] == 4){
	echo '<tr class="r2">
    <td width="25%">';
		if($id){
		echo '<a href="../articles/'.$id.'" target="_blank">Текст статьи</a>';
		}
	echo '</td>
    <td colspan="2"><input name="approve" type="radio" value="1"';
		if(($approve)||(!$id)) echo ' checked';
	echo '>
      проверено
        <input name="approve" type="radio" value="0"';
		if(($id)&&(!$approve)) echo ' checked';
	echo '>
        не проверено </td>
  </tr>';
	}
?>
  <tr class="r1">
    <td colspan="3"><b>поля, отмеченные (*), являются обязательными</b></td>
  </tr>
   <tr class="r1">
    <td width="25%" valign="top">Картинка (будет справа вверху)</td>
<?php
$picwithoutjpg = $protection->get['pic'];
$pic = $protection->get['pic'].'.jpg';
	if(!$picwithoutjpg) $pic = $id.'.jpg';
	
	if($picwithoutjpg||$id){
	$img = '../'.$img_fold.'/articles/'.$pic;
		if (file_exists($img)){
		echo '<td align="right"><img src="'.$img.'"><br><a href="change_article.php?id='.$id.'&pic='.$pic.'&del_pic=true" class="del" title="Удалить"><b>удалить картинку</b></a><br></td>
    <td valign="top">';
		}
		else{
		echo '<td colspan="2">';
		}
	}
	else{
	echo '<td colspan="2">';
	}
echo '<input name="file" type="file" id="file" value="Обзор..." size="26">
      <input type="submit" name="upload" value="Загрузить"><br>Если изменение картинки не очевидно, попробуйте обновить страницу (F5)</td>';
?>
  </tr>
  <tr class="r2">
    <td width="25%"><b>(*)</b>Заголовок статьи (макс. 250)</td>
    <td colspan="2"><input name="article" type="text" size="80"<?php if($article) echo ' value="'.$article.'"'; ?>></td>
  </tr>
  <tr class="r1">
    <td width="25%"><b>(*)</b>Категория:</td>
    <td colspan="2"><select onchange="javascript:setfield(document.form);" name="cat">
<?php
	if(is_array($cat_arr)){
		for($i=0; $i<count($cat_arr); $i++){
			if($cat_arr[$i]!='Разное'){
			echo '<option value="'.$cat_arr[$i].'"';
				if($cat_arr[$i]==$cat){
				echo ' selected';
				}
			echo '>'.$cat_arr[$i].'</option>';
			}
		}
	}
?>
      <option value="Разное"<?php if((!$cat)||($cat=='Разное')) echo ' selected'; ?>>Разное</option>
      <option value="0">Другое [указать]</option>
    </select>
      другое:
      <input disabled="true" name="other_cat" type="text" id="other_cat" size="44" maxlength="80">
    </td>
  </tr>
  <tr class="r1">
    <td width="25%">Эпиграф (не обязательно)</td>
    <td colspan="2"><textarea name="epigraf" cols="40" rows="5"><?php if($epigraf) echo $epigraf; ?></textarea></td>
  </tr>
  <tr class="r2">
    <td width="25%"><b>(*)</b>Текст статьи</td>
    <td colspan="2"><textarea name="desc" cols="80" rows="50"><?php if($desc) echo $desc; ?></textarea></td>
  </tr>
  <tr class="r1">
    <td>Теги (через точку с запятой без пробелов):</td>
    <td colspan="2"><textarea name="tags" cols="80" rows="10"><?php if($tags) echo $tags; ?></textarea></td>
  </tr>
  <tr class="r2">
    <td width="25%"><b>(*)</b>Автор</td>
    <td colspan="2"><textarea name="author" cols="40" rows="5"><?php if($author) echo $author; ?></textarea></td>
  </tr>
  <tr class="r1">
    <td width="25%" rowspan="2" valign="top">Первоисточник (если необходимо)</td>
    <td><input name="link_on" type="text" size="50"<?php if($link_on) echo ' value="'.$link_on.'"'; ?>></td>
    <td><input name="ankor" type="text" id="ankor" size="30"<?php if($ankor) echo ' value="'.$ankor.'"'; ?>></td>
  </tr>
  <tr class="r2">
    <td>URL (например: http://www.site.ru/article.html)</td>
    <td>Анкор (например: ссылка на первоисточник) </td>
  </tr>
  <tr class="r2">
    <td width="25%">&nbsp;</td>
    <td colspan="2"><input type="submit" name="<?php if($id) echo 'change_article'; else echo 'insert_article'; ?>" value="<?php if($id) echo 'Сохранить изменения'; else echo 'Добавить'; ?>"></td>
    </tr>
<input name="pic" type="hidden" value="<?php echo $picwithoutjpg; ?>">
</form>
</table>
</div>
<?php
include ('templates/footer.tpl');
?>