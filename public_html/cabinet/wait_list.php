<?php
session_start();
$title = '���� ��������. ����������';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('../supple/auth_db.php');
require ('../supple/server_root.php');

$HTTP_REFERER = $_SERVER['HTTP_REFERER'];

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

//������� �������� ��������
	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
		if(($protection->get['del'])&&($protection->post['approve_del'])){
		$id = $protection->get['id'];
		$str_sql_query = 'DELETE FROM ne_wait_lists WHERE id_product = "'.$id.'" AND id_user="'.$_SESSION['logged_user'].'"';
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
$str_sql_query = 'SELECT * FROM ne_wait_lists AS w, items AS i WHERE i.id=w.id_product AND w.id_user="'.$_SESSION['logged_user'].'" GROUP BY i.name ORDER BY i.name ASC';
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">��� ���� ��������:</div>
</div>
<?php
echo '<table width="100%" cellspacing="1">
   <tr>
    <td width="100%">';
	if($number){
	$s = $protection->get['s'];	//����� ��������
	$k = 50;	//���������� ������������ �� ����� ��������
		if(!$s) $s = 1;
	$n = ceil($number/$k);		//���������� �������
	$i = 1;
		if(($n > 1)&&($s != 1)){
		$prev = $s - 1;
		echo '<a href="?s='.$prev.'&sort='.$sort.'">���������� <<</a> ';
		}
		while ($i <= $n){
			if($i == $s) echo '[ '.$i.' ]';
			else echo '[<a href="?s='.$i.'&sort='.$sort.'"> '.$i.' </a>]';
		$i++;
		}
		if(($n > 1)&&($n != $s)){
		$next = $s + 1;
		echo ' <a href="?s='.$next.'&sort='.$sort.'">&gt;&gt; ���������</a>';
		}
	}
	else echo '� ����� ����� �������� �� ���������� ���������.';
	echo '</td>
   </tr>
 </table>';

	if($number){
	echo '
<div id="data">
 <table width="100%" cellspacing="1">
  <thead>
   <tr>
    <td width="40%">������������</td>
    <td width="20%">�������������</td>
    <td width="10%">������</td>
    <td width="20%">����</td>
    <td width="10%">�������</td>
   </tr>
  </thead>
  <tbody>
   ';
	$i = ($s-1)*$k;
		while ($i < $number && $i < ($s*$k)){
		
		$id = mysql_result($result,$i,"i.id");
		$name = mysql_result($result,$i,"i.name");
		$from = mysql_result($result,$i,"i.is_from");
		$urozhay = mysql_result($result,$i,"i.urozhay");
		$price_1 = mysql_result($result,$i,"i.price_1");
		$price_2 = mysql_result($result,$i,"i.price_2");
		$ed_izmer = mysql_result($result,$i,"i.ed_izmer");
		
			if($ed_izmer == 1) $ed_izmer = '��';
			elseif($ed_izmer == 2) $ed_izmer = '100 ��';
			elseif($ed_izmer == 3) $ed_izmer = '��.';
			elseif($ed_izmer == 4) $ed_izmer = '��.';
			else $ed_izmer = 0;
		
		echo '<tr class="';
			if((ceil($i/2)*2) != $i) echo 'r2';
			else echo 'r1';
		echo '">
    <td align="left" style="padding-left:20px"><a href="'.$SERVER_ROOT.'shop/'.$id.'.html" target="_blank">'.$name.'</a></td>
    <td align="left" style="padding-left:20px">';
			if(!$from) echo '�� �������';
			else echo $from;
		echo '</td>
    <td align="left" style="padding-left:20px">';
			if(!$urozhay) echo '�� ��������';
			else echo $urozhay.' ���';
		echo '</td>
    <td align="center" style="padding-left:20px">';
			if(($price_2)&&($price_2<$price_1)) $price = $price_2;
			else $price = $price_1;
			
			if(!$_SESSION['logged_status']) $price *= 1.5;
		echo $price.' �';
			if($ed_izmer) echo '/'.$ed_izmer;
			if(($price_2)&&($price_2<$price_1)) echo ' (<b>������</b>)';
		echo '</td>
    <td align="center"><a href="?id='.$id.'&del=true"><img src="../images/b_drop.png" alt="������� �������" title="������� ������� �� ����� ��������" width=16 height=16 border=0></a></td>
   </tr>
  ';
		$i++;
		}
	echo '</tbody>
 </table>
</div>
';
	}
?>
<?php
include ('templates/footer.tpl');
?>