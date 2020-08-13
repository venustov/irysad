<?php
session_start();
$title = 'Внесение нового заказа';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('../supple/auth_db.php');
require ('../supple/server_root.php');

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

function TableForm($uin,$sum,$err,$step){
	if($step=='one'){
	$str='
 <table width="600" cellspacing="1">
  <thead>
   <tr>
     <td width="50%">UIN</td>
     <td width="50%">Сумма заказа (оплачено)</td>
   </tr>
  </thead>
  <tbody>
   <tr align="center" class="r1">
     <td><input name="uin" type="text" id="uin" size="10" maxlength="10"';
		if($uin) $str.=' value="'.$uin.'"';
	$str.='></td>
	 <td><input name="sum" type="text" id="sum" size="10" maxlength="10"';
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
     <td><input name="next" type="submit" id="next" value="Далее"></td>
   </tr>
  </tbody>
 </table>
';
	}
	elseif($step=='two'){
	$str='
 <table width="600" cellspacing="1">
  <thead>
   <tr>
     <td width="50%">UIN</td>
     <td width="50%">Сумма заказа (оплачено)</td>
   </tr>
  </thead>
  <tbody>
   <tr align="center" class="r1">
     <td><b>'.$uin.'</b></td>
	 <td><b>'.$sum.'</b></td>
   </tr>
   <tr class="r3">
     <td><input type="hidden" name="uin" value="'.$uin.'"><input type="hidden" name="sum" value="'.$sum.'"><input name="back" type="submit" id="back" value="Назад"></td>
     <td>';
		if(!$err) $str.='<input name="ready" type="submit" id="ready" value="Сохранить">';
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
  <div class="hdr2" style="background-position:0 0">Внесение нового заказа:</div>
</div>
<div id="data">
<form name="purchases" method="post" action="">
<?php
$step = 'one';
$sum=abs(str_replace(',','.',$protection->post['sum']));
	if($protection->post['next']&&$protection->post['uin']&&$sum){
	$sql_query = 'SELECT * FROM customers WHERE uin="'.$protection->post['uin'].'" AND approve="1"';
	$result = @mysql_query($sql_query);
	$number = @mysql_num_rows($result);
		if(!$number) $err = 'ОШИБКА: УЧАСТНИКА С ТАКИМ UIN-ом НЕ СУЩЕСТВУЕТ!';
		else $step = 'two';
	echo TableForm($protection->post['uin'],$sum,$err,$step);
	}
	elseif($protection->post['ready']){
	$sql_query = 'INSERT INTO ne_purchases (sum,user,status,admin) VALUES ("'.$sum.'","'.$protection->post['uin'].'","2","'.$_SESSION['logged_user'].'")';
		if(@mysql_query($sql_query,$link)) $err = 'ЗАКАЗ УСПЕШНО ВНЕСЁН В БАЗУ ДАННЫХ';
		else $err = 'ПРОИЗОШЛИ ОШИБКИ ПРИ ВНЕСЕНИИ ЗАКАЗА В БАЗУ ДАННЫХ. ПОЖАЛУЙСТА, ПОВТОРИТЕ ЗАПРОС ЕЩЁ РАЗ';
	echo TableForm(0,0,$err,$step);
	}
	else echo TableForm($protection->post['uin'],$sum,$err,$step);
?>
</form>
</div>
<?php
include ('templates/footer.tpl');
?>