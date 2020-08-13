<?php
session_start();
$title = 'Статьи';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('../supple/auth_db.php');
require ('../supple/server_root.php');
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

//Удаление статьи
function delArticle($id,$img_fold){
$str_sql_query = "DELETE FROM articles WHERE id = '$id'";
@mysql_query($str_sql_query);
$img = '../'.$img_fold.'/articles/'.$id.'.jpg';
	if(file_exists($img)) unlink($img);
return true;
}
	if(($protection->get['del'])&&($protection->post['approve_del'])){
	$id = $protection->get['id'];
		if($_SESSION['logged_status'] != 4){
		$user = $_SESSION['logged_user'];
		$str_sql_query = "SELECT * FROM articles WHERE id = '$id' AND maker = '$user'";
		$result = mysql_query($str_sql_query);
		$number = mysql_num_rows($result);
			if($number) delArticle($id,$img_fold);
		}
		else delArticle($id,$img_fold);
	}
	elseif(($protection->get['del'])&&(!$protection->post['no_del'])){
	include ('templates/del_approve.tpl');
	exit();
	}
?>
<?php
include ('templates/header.tpl');
?>
<?php
$str_sql_query = 'SELECT a.id, a.title, a.maker, a.approve FROM articles AS a';
	if($_SESSION['logged_status'] == 4) $str_sql_query .= ' ORDER BY a.approve ASC, a.id ASC';
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Статьи СЕ-, ЗОЖ- и другой тематики</div>
</div>
<?php
echo '<table width="100%" cellspacing="1">
   <tr>
    <td><form name="new_item" method="post" action="article_form.php"><input name="add_article" type="submit" id="add_article" value="Разместить новую статью"></form></td>
    <td width="100%">';
	if($number){
	$s = $protection->get['s'];	//номер страницы
	$k = 50;	//количество наименований на одной странице
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
    <td width="5%" align="center">ID</td>
    <td width="32%">Заголовок</td>
    <td width="35%">Персональная ссылка (URL)</td>
    <td width="8%">Редактировать</td>
    <td width="12%">Статус</td>
    <td width="8%">Удалить</td>
   </tr>
  </thead>
  <tbody>';
	$i = ($s-1)*$k;
		while ($i < $number && $i < ($s*$k)){
		
		$id = mysql_result($result,$i,"a.id");
		$title_article = mysql_result($result,$i,"a.title");
		$maker = mysql_result($result,$i,"a.maker");
		$approve = mysql_result($result,$i,"a.approve");
		
		echo '<tr class="';
			if((ceil($i/2)*2) != $i) echo 'r2';
			else echo 'r1';
		echo '">';
		echo '<td align="left">'.$id.'</td>';
		echo '<td align="left" style="padding-left:20px"><a href="'.$SERVER_ROOT.'articles/'.$id;
			if($maker != $_SESSION['logged_user']) echo '/u'.$_SESSION['logged_user'];
			else echo '.html';
		echo '" target="_blank">'.$title_article.'</a></td>';
		echo '<th>
        <input name="url_article" type="text" size="45" maxlength="50" value="'.$SERVER_ROOT.'articles/'.$id;
			if($maker != $_SESSION['logged_user']) echo '/u'.$_SESSION['logged_user'];
			else echo '.html';
		echo '">
    </th>';
		echo '<td align="center">';
			if(($maker == $_SESSION['logged_user'])||($_SESSION['logged_status'] == 4)) echo '<a href="article_form.php?id='.$id.'"><img src="../images/change.png" alt="Редактировать" title="Редактировать" width="16" height="16" border="0"></a>';
			else echo '&nbsp;';
		echo '</td>';
  		echo '<td align="center">';
			if(!$approve) echo 'не проверено';
			else echo 'проверено';
		echo '</td>';
		echo '<td align="center">';
			if(($maker == $_SESSION['logged_user'])||($_SESSION['logged_status'] == 4)) echo '<a href="?id='.$id.'&del=true"><img src="../images/b_drop.png" alt="Удалить статью" title="Удалить статью" width=16 height=16 border=0></a>';
			else echo '&nbsp;';
		echo '</td>';
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