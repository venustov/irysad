<?php
session_start();
$title = '�������� �������� �������';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('../supple/auth_db.php');
require ('../supple/server_root.php');

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

function TableForm($pay_id,$sum,$err,$step,$user){
	if($step=='one'){
	$str = '
 <table width="600" cellspacing="1">
  <thead>
   <tr>
     <td width="50%">ID �������</td>
     <td width="50%">C���� �������</td>
   </tr>
  </thead>
  <tbody>
   <tr align="center" class="r1">
     <td><input name="pay_id" type="text" size="10" maxlength="10"';
		if($pay_id) $str.=' value="'.$pay_id.'"';
	$str.='></td>
	 <td><input name="sum" type="text" size="10" maxlength="10"';
		if($sum) $str.=' value="'.$sum.'"';
	$str.='></td>
   </tr>
   ';
		if($err) $str.='<tr align="center" class="r2">
     <td colspan="2" style="color:#FF0000;font-weight:bold;">'.$err.'</td>
   </tr>';
	$str.='
   <tr class="r3">
     <td>&nbsp;</td>
     <td align="right"><input name="next" type="submit" id="next" value="�����"></td>
   </tr>
  </tbody>
 </table>';
	}
	elseif($step=='two'){
	$str='
 <table width="600" cellspacing="1">
  <thead>
   <tr>
     <td width="50%">ID �������</td>
     <td width="50%">����� ������� </td>
   </tr>
  </thead>
  <tbody>
   <tr align="center" class="r1">
     <td><b>'.$pay_id.'</b></td>
	 <td><b>'.$sum.'</b></td>
   </tr>
   ';
		if($err) $str.='<tr align="center" class="r2">
     <td colspan="2" style="color:#FF0000;font-weight:bold;">'.$err.'</td>
   </tr>';
	$str.='
   <tr class="r3">
     <td><input type="hidden" name="pay_id" value="'.$pay_id.'"><input type="hidden" name="sum" value="'.$sum.'"><input type="hidden" name="user" value="'.$user.'"><input name="back" type="submit" id="back" value="�����"></td>
     <td align="right">';
		if(!eregi('������',$err)) $str.='<input name="ready" type="submit" value="���������">';
		else $str.='&nbsp;';
	$str.='</td>
   </tr>
  </tbody>
 </table>
';
	}
return $str;
}
?>
<?php
include ('templates/header.tpl');
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">�������� ���������� �������� �������:</div>
</div>
<div id="data">
<form name="purchases" method="post" action="">
<?php
$step = 'one';
$sum=abs(str_replace(',','.',$protection->post['sum']));
$user = $protection->post['user'];
	if($protection->post['next']&&$protection->post['pay_id']&&$sum){
	$sql_query = 'SELECT user,sum FROM ne_user_pay WHERE id="'.$protection->post['pay_id'].'" AND status="0" AND type="1"';
	$result = @mysql_query($sql_query);
	$number = @mysql_num_rows($result);
		if(!$number) $err = '������: ������� �� ���������� ��� �� ��� �������!';
		else{
		$user = mysql_result($result,0,"user");
		$sum_db = mysql_result($result,0,"sum");
			if($sum!=$sum_db) $err = '��������������: ���������� ���������� ����� �� ������������� ������������� �����!';
		$step = 'two';
		}
	echo TableForm($protection->post['pay_id'],$sum,$err,$step,$user);
	}
	elseif($protection->post['ready']){
	$sql_insert = 'INSERT INTO ne_user_pay (sum,user,status,type,description,admin) VALUES ('.$sum.','.$user.',1,5,"�������� ����� ���������",'.$_SESSION['logged_user'].')';
	$sql_update = 'UPDATE ne_user_pay SET sum='.$sum.',status="1",admin="'.$_SESSION['logged_user'].'" WHERE id="'.$protection->post['pay_id'].'"';
		if(@mysql_query($sql_insert,$link)){
			if(@mysql_query($sql_update,$link)) $err = '�������� ����� ������� ���Ѩ� � ���� ������';
			else $err = '�� ������� �������� ������ ������� �'.$protection->post['pay_id'].'. ����������, �������� �� ���� ��������������!';
		}
		else $err = '��������� ������ ��� �������� ������� � ���� ������. ����������, ��������� ������ �٨ ���';
	echo TableForm(0,0,$err,$step,$user);
	}
	else echo TableForm($protection->post['pay_id'],$sum,$err,$step,$user);
?>
</form>
</div>
<?php
include ('templates/footer.tpl');
?>