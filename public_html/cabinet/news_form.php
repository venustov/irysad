<?php
$title = 'Добавление [изменение] новости';
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
	$str_sql_query = "SELECT * FROM news WHERE id = '$id'";
	$result = mysql_query($str_sql_query);
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
		$date = mysql_result($result,$i,"date");
		$name = mysql_result($result,$i,"title");
		$desc = mysql_result($result,$i,"description");
		$approve = mysql_result($result,$i,"approve");
		$access = mysql_result($result,$i,"access");
		}
		else $id = 0;
	}

?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0"><?php if($id) echo 'Изменить новость'; else echo 'Добавить новость'; ?>:</div>
</div>
<div id="data"><table width="100%" border="0" cellpadding="10" cellspacing="0">
<form name="form" method="post" action="change_news.php<?php if($id) echo '?id='.$id; ?>">
<?php
	if($_SESSION['logged_status'] == 4){
	echo '<tr class="r2">
    <td width="25%">';
		if($id){
		echo '<a href="../news/'.$id.'" target="_blank">Текст новости</a>';
		}
	echo '</td>
    <td width="75%"><input name="approve" type="radio" value="1"';
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
  <td width="25%">Заголовок</td>
  <td width="75%"><input name="title" type="text" size="80"<?php if($name) echo ' value="'.$name.'"'; ?>></td>
</tr>
  <tr class="r2">
    <td width="25%">Подробно</td>
    <td width="75%"><textarea name="desc" cols="50" rows="10"><?php if($desc) echo $desc; ?></textarea></td>
  </tr>
  <tr class="r1">
    <td width="25%">Доступ</td>
    <td width="75%"><input name="access" type="radio" value="1"<?php if(($access)||(!$id)) echo ' checked'; ?>>
      только для членов кооператива
        <input name="access" type="radio" value="0"<?php if(($id)&&(!$access)) echo ' checked'; ?>>
        для всех</td>
  </tr>
  <tr class="r2">
    <td width="25%">&nbsp;</td>
    <td width="75%"><input type="submit" name="<?php if($id) echo 'change_news'; else echo 'insert_news'; ?>" value="<?php if($id) echo 'Сохранить изменения'; else echo 'Добавить'; ?>"></td>
  </tr>
</form>
</table>
</div>
<?php
include ('templates/footer.tpl');
?>