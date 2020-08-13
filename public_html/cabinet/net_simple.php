<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Ваша сеть</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>

<body>
<?php
require ('../supple/auth_db.php');
$user = $_SESSION['logged_user'];
$first_day = date('Y-m').'-01 00:00:00';

$str_sql_query = "SELECT * FROM customers WHERE up_uin='$user' AND uin!='$user'";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if(!$number){
	echo 'Ничего не найдено';
	}
$j = 1;
$vsego_sum = 0;
	while($number){
	$str_sql_query = "SELECT * FROM customers WHERE ";
	echo $j.'-й уровень: <br>';
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
		$name_1 = mysql_result($result,$i,"name_1");
		$name_2 = mysql_result($result,$i,"name_2");
		$up_uin = mysql_result($result,$i,"up_uin");
		$country = mysql_result($result,$i,"country");
		$city = mysql_result($result,$i,"city");
		$balance = mysql_result($result,$i,"balance");
		$query = "SELECT SUM(sum) FROM ne_user_pay WHERE status!='0' AND date_pay>=DATE_SUB('$first_day',INTERVAL 1 MONTH) AND date_pay<'$first_day' AND user = '$uin'";
		$res = mysql_query($query);
		$num = mysql_num_rows($res);
			if($num){
			$sum = mysql_result($res,"0");
			$rez = $sum*($percent/100);
			$itogo_vzn = $itogo_vzn + $sum;
			$itogo_sum = $itogo_sum + $rez;
			}
		echo $uin.'&nbsp;'.$name_1.'&nbsp;'.$up_uin.'&nbsp;'.$city.'&nbsp;'.$balance.'&nbsp;'.$sum.'&nbsp;'.$percent.'&nbsp;'.$rez.'<br>';
			if($i) $str_sql_query .= " OR up_uin = '$uin'";
			else $str_sql_query .= "up_uin = '$uin'";
		$i++;
		}
	$vsego_sum = $vsego_sum + $itogo_sum;
	echo '<br>Итого: '.$itogo_vzn.'&nbsp;'.$percent.'&nbsp;'.$itogo_sum.'<br><br>';
	$result = mysql_query($str_sql_query);
	$number = mysql_num_rows($result);
	$j++;
	}
echo 'Всего: '.$vsego_sum;
?>
</body>
</html>
