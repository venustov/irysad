<?php
session_start();
$title = 'Управление продуктами';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('../supple/auth_db.php');
require ('../supple/server_root.php');
require ('../supple/delete_dir.php');

$HTTP_REFERER = $_SERVER['HTTP_REFERER'];

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

//Сделать удаление продукта
	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
		if(($protection->get['del'])&&($protection->post['approve_del'])){
		$id = $protection->get['id'];
			if(file_exists('../'.$img_fold.'/photoes/'.$id)) removeDirRec('../'.$img_fold.'/photoes/'.$id);
		$str_sql_query = "DELETE FROM items WHERE id = '$id'";
		@mysql_query($str_sql_query, $link);
		}
		elseif(($protection->get['del'])&&(!$protection->post['no_del'])){
		include ('templates/del_approve.tpl');
		exit();
		}
	}
?>
<?php
include ('templates/header.tpl');
?>
<?php
	if($_SESSION['logged_status']>3) $city = $_SESSION['city'];
	else $city = $_SESSION['logged_city'];

$str_sql_query = "SELECT * FROM items AS i WHERE i.city = '$city' ORDER BY i.status ASC, i.name ASC";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Продукты города 
<?php
	if($_SESSION['logged_status']>3) echo '<a href="other_city.php">'.$city.'</a>';
	else echo $city;
?>
  :</div>
</div>
<?php
echo '<table width="100%" cellspacing="1">
   <tr>
    <td><form name="new_item" method="post" action="item_form.php"><input name="add_item" type="submit" id="add_item" value="Добавить продукт"></form></td>
    <td width="100%">';
	if($number){
	$s = $protection->get['s'];	//номер страницы
	$k = 300;	//количество наименований на одной странице
		if(!$s) $s = 1;
	$n = ceil($number/$k);		//количество страниц
	$i = 1;
		if(($n > 1)&&($s != 1)){
		$prev = $s - 1;
		echo '<a href="?s='.$prev.'&sort='.$sort.'">Предыдущая <<</a> ';
		}
		while ($i <= $n){
			if($i == $s) echo '[ '.$i.' ]';
			else echo '[<a href="?s='.$i.'&sort='.$sort.'"> '.$i.' </a>]';
		$i++;
		}
		if(($n > 1)&&($n != $s)){
		$next = $s + 1;
		echo ' <a href="?s='.$next.'&sort='.$sort.'">&gt;&gt; Следующая</a>';
		}
	}
	else echo 'В Базе Данных не обнаружено позиций.';
	echo '</td>
   </tr>
 </table>';

	if($number){
	echo '<div id="data">
 <table width="100%" cellspacing="1">
  <thead>
   <tr>
    <td width="30%">Наименование</td>
    <td width="8%">Изменить описание</td>
    <td width="8%">Изменить картинку</td>
    <td width="17%">Закупочная цена (руб.)</td>
    <td width="27%">Наличие</td>
    <td width="10%">Удалить</td>
   </tr>
  </thead>
  <tbody>';
	$i = ($s-1)*$k;
		while ($i < $number && $i < ($s*$k)){
		
		$id = mysql_result($result,$i,"i.id");
		$name = mysql_result($result,$i,"i.name");
		$from = mysql_result($result,$i,"i.is_from");
		$category = mysql_result($result,$i,"i.category");
		$urozhay = mysql_result($result,$i,"i.urozhay");
		$price_1 = mysql_result($result,$i,"i.price_1");
		$price_2 = mysql_result($result,$i,"i.price_2");
		$status = mysql_result($result,$i,"i.status");
		
		echo '<tr class="';
			if((ceil($i/2)*2) != $i) echo 'r2';
			else echo 'r1';
		echo '">';
		echo '<td align="left" style="padding-left:20px"><a href="'.$SERVER_ROOT.'shop/'.$id.'.html" target="_blank">'.$name.'</a>';
			if(($price_2)&&($price_2<$price_1)) echo ' <b>УЦЕНКА</b>';
		echo ' (происхождение: ';
			if(!$from) echo 'не указано';
			else echo $from;
		echo ', категория: ';
			if(!$category) echo 'не определена';
			elseif($category == '1') echo 'фрукты/овощи';
			elseif($category == '2') echo 'семена/орехи';
			elseif($category == '3') echo 'зелень/другое';
		echo ', урожай: ';
			if(!$urozhay) echo 'не известно';
			else echo $urozhay.' год';
		echo ')</td>';
		echo '<td align="center"><a href="item_form.php?id='.$id.'"><img src="../images/change.png" width="16" height="16" border="0"></a></td>';
		echo '<td align="center"><a href="photo.php?id='.$id.'"><img src="../images/images_all.png" width="16" height="16" border="0"></a></td>';
		echo '<th><form name="price_zak" method="post" action="change_status.php"><input name="price_zak" type="text" size="5" maxlength="8" value="'.$price_1.'"><input type="submit" name="zak_change" value="Изменить"><input type="hidden" name="id" value="'.$id.'"></form></th>';
  		echo '<td align="center"><form name="status" method="post" action="change_status.php"><select name="status">
      <option value="1"';
			if($status==1) echo ' selected';
		echo '>Есть в наличии</option>
      <option value="2"';
	  		if($status=='2') echo ' selected';
		echo '>Количество ограничено</option>
      <option value="3"';
	  		if($status=='3') echo ' selected';
		echo '>Ожидается скоро</option>
      <option value="4"';
	  		if($status=='4') echo ' selected';
		echo '>Временно отсутствует</option>
            </select><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="status_old" value="'.$status.'"><input type="submit" name="status_change" value="Изменить"></form></td>';
		echo '<td align="center"><a href="?id='.$id.'&del=true"><img src="../images/b_drop.png" alt="Удалить продукт" title="Удалить продукт" width=16 height=16 border=0></a></td>';
		echo '</tr>';
		
		$i++;
		}
	echo '</tbody>
 </table>
</div>';
	}
?>
<?php
include ('templates/footer.tpl');
?>