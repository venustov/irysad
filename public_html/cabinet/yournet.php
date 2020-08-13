<?php
$title = 'Ваша сеть';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
?>
<?php
include ('templates/header.tpl');
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Участники, пришедшие по вашей рекомендации:</div>
</div>
<?php
require ('../supple/auth_db.php');
$user = $_SESSION['logged_user'];
$first_day = date('Y-m').'-01 00:00:00';

$str_sql_query = "SELECT * FROM customers WHERE up_uin='$user' AND uin!='$user' AND approve='1'";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if(!$number){
	echo '<table width="100%" cellspacing="1">
   <tr>
    <td width="100%">Участников, пришедших по вашей рекомендации, в Базе Данных не найдено...</td>
   </tr>
 </table>';
	}
	else{
	$j = 1;
	$vsego_sum = 0;
	echo '<div id="data">
 <table width="100%" cellspacing="1">
  <thead>
   <tr>
    <td width="7%">UIN</td>
    <td width="18%">Фамилия Имя</td>
    <td width="10%">UP</td>
    <td width="14%">Гражданство</td>
    <td width="17%">Город</td>
    <td width="14%">Сумма покупок (без наценки)</td>
    <td width="10%">Ваш %</td>
    <td width="10%">Ожидаемый бонус</td>
    </tr>
  </thead>
  <tbody>';
		while($number){
		$str_sql_query = 'SELECT * FROM customers WHERE approve=1 ';
		echo '<tr class="r3">
     <td colspan="8" align="left" style="padding-left:40px"><b>'.$j.'-й уровень:</b></td>
     </tr>';
			switch($j){
			case 1: $percent = 2.5;
			break;
			case 2: $percent = 2;
			break;
			case 3: $percent = 1.5;
			break;
			case 4:
			case 5:
			case 6:
			case 7: $percent = 1;
			break;
			}
		$i = 0;
		$itogo_vzn = 0;
		$itogo_sum = 0;
			while($i < $number){
			$uin = mysql_result($result,$i,"uin");
			$name_1 = mysql_result($result,$i,"name_1");
			$name_2 = mysql_result($result,$i,"name_2");
			$up_uin = mysql_result($result,$i,"up_uin");
			$country = mysql_result($result,$i,"country");
			$city = mysql_result($result,$i,"city");
			$balance = mysql_result($result,$i,"balance");
			$status = mysql_result($result,$i,"status");
//			$query = "SELECT SUM(sum) FROM ne_user_pay WHERE status!='0' AND type='1' AND date_pay>=DATE_SUB('$first_day',INTERVAL 1 MONTH) AND date_pay<'$first_day' AND user = '$uin'";
			$query = 'SELECT SUM(sum) AS sum_purchases FROM ne_purchases WHERE status=2 AND date>="'.$first_day.'" AND user='.$uin;
			$res = mysql_query($query);
			$num = mysql_num_rows($res);
				if($num){
				$sum = mysql_result($res,0,"sum_purchases");
					if(!$status) $sum = round((($sum/3)*2),2);
				$rez = round(($sum*$percent/100),2);
				$itogo_vzn += $sum;
				$itogo_sum += $rez;
				}
			echo '
   <tr class="';
			if((ceil($i/2)*2) != $i) echo 'r2';
			else echo 'r1';
   		echo '"><td align="center">'.$uin.'</td>
    <td align="left" style="padding-left:20px">'.$name_1.' '.$name_2.'</td>
	<td align="center">'.$up_uin.'</td>
	<td align="center">'.$country.'</td>
	<td align="center">'.$city.'</td>
	<td align="center">'.$sum.'</td>
	<td align="center">'.$percent.'</td>
	<td align="center">'.$rez.'</td></tr>
   ';
				if($i) $str_sql_query .= ' OR up_uin='.$uin;
				else $str_sql_query .= 'AND (up_uin='.$uin;
			$i++;
			}
		$str_sql_query .= ')';
		$vsego_sum = $vsego_sum + $itogo_sum;
		echo '<tr class="r4">
     <td colspan="5" align="left" style="padding-left:40px"><b>Итого:</b></td>
     <td align="center">'.$itogo_vzn.'</td>
     <td align="center">'.$percent.'</td>
     <td align="center"><b>'.$itogo_sum.'</b></td>
   </tr>';
		$result = mysql_query($str_sql_query);
		$number = mysql_num_rows($result);
		$j++;
		}
	echo '<tr class="r3">
     <td colspan="7" align="left" style="padding-left:40px"><b>Всего:</b></td>
     <td align="center"><b>'.$vsego_sum.'</b></td>
   </tr></tbody>
 </table>
</div>';
	}
?>
<?php
include ('templates/footer.tpl');
?>