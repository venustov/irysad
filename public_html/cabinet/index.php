<?php
$title = 'Новости проекта';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
?>
<?php
include ('templates/header.tpl');
?>
<?php
require ('../supple/auth_db.php');
require ('../supple/normal_date.php');

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$str_sql_query = "SELECT n.id, n.date, n.title, n.description, n.approve, n.newsmaker FROM news AS n ORDER BY n.date DESC";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
?>
<div class="hdr1"><div class="hdr2" style="background-position:-1px -108px">Новости</div></div>
<table width="100%" cellspacing="1">
  <tr>
	<td colspan="2">
<?php
	if($number){
	$s = $protection->get['s'];	//номер страницы
	$k = 5;	//количество наименований на одной странице
		if(!$s) $s = 1;
	$i = ($s-1)*$k;
//вывод новостей
		while ($i < $number && $i < ($s*$k)){
		$id = mysql_result($result,$i,"n.id");
		$date = NormalDate(mysql_result($result,$i,"n.date"));
		$title = mysql_result($result,$i,"n.title");
		
		$desc = substr(mysql_result($result,$i,"n.description"),0,360);
		
		$desc = preg_replace('/<a href=[\"\']{0,1}http[s]{0,1}:\/\/[\S]*[\'\"]{0,1}>([\S\s]*)<\/a>/','\1',$desc);
		
		$approve = mysql_result($result,$i,"n.approve");
		$newsmaker = mysql_result($result,$i,"n.newsmaker");
		echo '<ul class="news">';
			if((!$approve)||($newsmaker == $_SESSION['logged_user'])||($_SESSION['logged_status'] == 4)){
			echo '<div class="work">';
				if(!$approve){
				echo '<b>ожидает проверки</b>&nbsp;';
				}
				if(($newsmaker == $_SESSION['logged_user'])||($_SESSION['logged_status'] == 4)){
				echo '<a href="news_form.php?id='.$id.'" style="text-decoration: none; background-color: #0000FF; color: #FFFF00;">редактировать</a>&nbsp;<a href="change_news.php?id='.$id.'&del_news=true" title="Удалить" style="text-decoration: none; background-color: #993300; color: #FFFF00;">удалить</a>';
				}
			echo '</div>';
			}
//вывод самой новости
		echo '<li><a href="'.$SERVER_ROOT.'news/'.$id.'" target="_blank"><strong>'.$date.'&nbsp;<b>'.$title.'</b></strong><br /><br style="line-height:5px">'.$desc.'...</a></li>';
		$i++;
		}
	echo '</ul>';
	}
echo '</td>
  </tr>
  <tr>
    <td><form name="new_news" method="post" action="news_form.php"><input name="add_news" type="submit" value="Добавить новость"></form></td>
    <td width="100%">';
	if($number){
		if(!$s) $s = 1;
	$n = ceil($number/$k);		//количество страниц
	$i = 1;
		if(($n > 1)&&($s != 1)){
		$prev = $s - 1;
		echo '<a href="?s='.$prev.'">Предыдущая <<</a> ';
		}
		while ($i <= $n){
			if($i == $s) echo '[ '.$i.' ]';
			else echo '[<a href="?s='.$i.'"> '.$i.' </a>]';
		$i++;
		}
		if(($n > 1)&&($n != $s)){
		$next = $s + 1;
		echo ' <a href="?s='.$next.'">&gt;&gt; Следующая</a>';
		}
	}
	else echo 'В Базе Данных не обнаружено позиций.';
echo '</td>
   </tr>';
?>
</table>
<?php
include ('templates/footer.tpl');
?>