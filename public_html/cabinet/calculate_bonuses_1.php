<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
$title = 'Расчет и начисление бонусов';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

include ('../supple/server_root.php');
require ('../supple/auth_db.php');
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
?>
<?php
include ('templates/header.tpl');
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Расчет и начисление бонусов:</div>
</div>
<?php
	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
	$first_day = date('Y-m').'-01 00:00:00';
	$time = date('Y-m-d H:i:s');
	$sql_query='SELECT SUM(sum) AS bonuses FROM ne_bonuses WHERE sum>0 AND status=1 AND date_pay>="'.$first_day.'"';
	$result = @mysql_query($sql_query);
	$number = @mysql_num_rows($result);
		if($number) $bonuses_sum=mysql_result($result,0,"bonuses");
		elseif($protection->post['next']){
		$sql_query = 'SELECT uin FROM customers WHERE approve=1';
		$result_1 = @mysql_query($sql_query);
		$number_1 = @mysql_num_rows($result_1);
			if($number_1){
			$y=0;
			$bonuses_sum=0;
				while($y<$number_1){
				$user = mysql_result($result_1,$y,"uin");
				
				$str_sql_query = 'SELECT * FROM customers WHERE up_uin='.$user.' AND uin!='.$user.' AND approve=1';
				$result = mysql_query($str_sql_query);
				$number = mysql_num_rows($result);
					if($number){
					$j = 1;
					$vsego_sum = 0;
						while($number){
						$str_sql_query = 'SELECT * FROM customers WHERE approve=1 ';
							switch($j){
							case 1: $percent = 8;
							break;
							case 2: $percent = 7;
							break;
							case 3: $percent = 6;
							break;
							case 4:
							case 5:
							case 6:
							case 7:
							case 8:
							case 9:
							case 10:
							case 11:
							case 12: $percent = 1;
							break;
							}
						$i = 0;
						$itogo_vzn = 0;
						$itogo_sum = 0;
							while($i < $number){
							$uin = mysql_result($result,$i,"uin");
							$up_uin = mysql_result($result,$i,"up_uin");
							$query = 'SELECT SUM(sum) AS pays FROM ne_user_pay WHERE status=1 AND type=1 AND date_pay>=DATE_SUB("'.$first_day.'",INTERVAL 1 MONTH) AND date_pay<"'.$first_day.'" AND user="'.$uin.'"';
							$res = mysql_query($query);
							$num = mysql_num_rows($res);
								if($num){
								$sum = mysql_result($res,0,"pays");
								$rez = $sum*($percent/100);
								$itogo_vzn += $sum;
								$itogo_sum += $rez;
//								echo $itogo_sum.'</br>';
								}

								if($i) $str_sql_query .= ' OR up_uin="'.$uin.'"';
								else $str_sql_query .= 'AND (up_uin="'.$uin.'"';
							$i++;
							}
						$str_sql_query .= ')';
						$vsego_sum += $itogo_sum;
//						echo $vsego_sum.'</br>';
						$result = mysql_query($str_sql_query);
						$number = mysql_num_rows($result);
						$j++;
						}
						if($vsego_sum){
						$new_bonus = array('uin'=>$user,'sum'=>$vsego_sum);
						$bonuses[]=$new_bonus;
						}
					$bonuses_sum += $vsego_sum;
					}
				$y++;
				}
			}
		$sql_bonuses='INSERT INTO ne_bonuses VALUES ';
		$sql_creditors='INSERT INTO ne_creditors VALUES ';
			foreach($bonuses as $key=>$value){
			$sql_bonuses.='(0,'.$value['sum'].',"'.$time.'",'.$value['uin'].',1,'.$_SESSION['logged_user'].'),';
			$sql_creditors.='(0,'.$value['sum'].',"'.$time.'",'.$value['uin'].',1,'.$_SESSION['logged_user'].',2),';
			}
		$sql_bonuses=preg_replace('/,$/','',$sql_bonuses);
		$sql_creditors=preg_replace('/,$/','',$sql_creditors);
			if(@mysql_query($sql_bonuses)&&@mysql_query($sql_creditors)) echo 'Бонусы начислены успешно';
			else echo 'Произошли ошибки';
		}
	}
?>
<div id="data">
<form name="bonuses" method="post" action="">
 <table width="400" cellspacing="1">
  <thead>
   <tr>
     <td width="100%">Сумма выплаченных бонусов за прошлый месяц (руб.):</td>
   </tr>
  </thead>
  <tbody>
   <tr align="center" class="r1">
     <td><b><?php echo $bonuses_sum; ?></b></td>
   </tr>
   <tr class="r3">
     <td align="center"><input name="next" type="submit" value="Расчитать и начислить бонусы за прошлый месяц"></td>
   </tr>
  </tbody>
 </table>
</form>
</div>
<?php
include ('templates/footer.tpl');
?>