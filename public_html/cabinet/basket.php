<?php
session_start();
$title = '���������� ��������';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('../supple/auth_db.php');
require ('../supple/server_root.php');

$HTTP_REFERER = $_SERVER['HTTP_REFERER'];

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

//������� ��������� ���������� ��������
//������� �������� ��������
	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
		if($protection->post['change_quantity']&&$protection->post['quantity']&&($protection->post['quantity']!=$protection->post['quantity_old'])){
		$id = $protection->post['id'];
		$quantity = abs($protection->post['quantity']);
		$sql_query = 'UPDATE ne_baskets SET quantity = "'.$quantity.'" WHERE id="'.$id.'"';
		@mysql_query($sql_query);
		}
		elseif(($protection->get['del'])&&($protection->post['approve_del'])){
		$id = $protection->get['id'];
		$str_sql_query = 'DELETE FROM ne_baskets WHERE id = "'.$id.'"';
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
$str_sql_query = 'SELECT * FROM ne_baskets AS b, items AS i, ne_purchases AS p WHERE i.id=b.id_product AND p.user="'.$_SESSION['logged_user'].'" AND b.id_purchase=p.id AND p.status="0" ORDER BY i.name ASC';
$result = @mysql_query($str_sql_query);
$number = @mysql_num_rows($result);
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">��� ������� �����:</div>
</div>
<?php
	if($number){
	echo '<div id="data">
 <table width="100%" cellspacing="1">
  <thead>
   <tr>
    <td width="40%">������������</td>
    <td width="10%">����</td>
    <td width="40%">����������</td>
    <td width="10%">�������</td>
   </tr>
  </thead>
  <tbody>';
	$i=0;
		while ($i<$number){
		
		$id = mysql_result($result,$i,"i.id");
		$basket_id = mysql_result($result,$i,"b.id");
		$name = mysql_result($result,$i,"i.name");
		$zakup = mysql_result($result,$i,"i.price_1");
		$rozn = mysql_result($result,$i,"i.price_2");
		
			if($rozn&&($rozn < $zakup)) $price = $rozn;
			else $price = $zakup;
			
			if(!$_SESSION['logged_status']) $price *= 1.5;
		$quantity = mysql_result($result,$i,"b.quantity");
		$ed_izmer = mysql_result($result,$i,"i.ed_izmer");
		
			if($ed_izmer == 1) $ed_izmer = '��';
			elseif($ed_izmer == 2) $ed_izmer = '100 ��';
			elseif($ed_izmer == 3) $ed_izmer = '��.';
			elseif($ed_izmer == 4) $ed_izmer = '��.';
			else $ed_izmer = 0;
		
		echo '<tr class="';
			if((ceil($i/2)*2) != $i) echo 'r2';
			else echo 'r1';
		echo '">';
		echo '<td align="left" style="padding-left:20px"><a href="'.$SERVER_ROOT.'shop/'.$id.'.html" target="_blank">'.$name.'</a>';
			if(($rozn)&&($rozn<$zakup)) echo ' <b>������</b>';
		echo '</td>';
		echo '<th><b>'.$price.'</b> �/'.$ed_izmer.'</th>';
		echo '<th><form name="quantity" method="post" action=""><input name="quantity" type="text" size="5" maxlength="8" value="'.$quantity.'"><input type="hidden" name="quantity_old" value="'.$quantity.'"><input type="hidden" name="id" value="'.$basket_id.'"><input type="submit" name="change_quantity" value="��������"></form></th>';

		echo '<td align="center"><a href="?id='.$basket_id.'&del=true"><img src="../images/b_drop.png" alt="������� �������" title="������� �������" width=16 height=16 border=0></a></td>';
		echo '</tr>';
		
		$i++;
		}
	echo '</tbody>
 </table>
</div>';
	}
	else{
	echo '<table width="100%" cellspacing="1">
   <tr>
    <td width="100%">';
	echo '� ���� ������ �� ���������� �������.';
	echo '</td>
   </tr>
 </table>';
	}
?>
<?php
include ('templates/footer.tpl');
?>