<?php
$title = 'Персональная статистика по вашему аккаунту';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
?>
<?php
include ('templates/header.tpl');
?>
<?php
require ('../supple/auth_db.php');

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$uin = $_SESSION['logged_user'];
$sql_query = 'SELECT SUM(s.sum) AS contributions FROM ne_shares AS s WHERE s.user='.$uin.' AND s.status=1';
$result = mysql_query($sql_query);
$number = mysql_num_rows($result);
	if($number) $contributions = mysql_result($result,0,"contributions");
	
$sql_query = 'SELECT SUM(c.sum) AS debit FROM ne_creditors AS c WHERE c.user='.$uin.' AND c.status=1 AND c.type=1';
$result = mysql_query($sql_query);
$number = mysql_num_rows($result);
	if($number) $debit = mysql_result($result,0,"debit");
$sql_query = 'SELECT SUM(c.sum) AS credit FROM ne_creditors AS c WHERE c.user='.$uin.' AND c.status=1 AND c.type=2';
$result = mysql_query($sql_query);
$number = mysql_num_rows($result);
	if($number) $credit = mysql_result($result,0,"credit");
$balans = round(($credit - $debit),2);
	
$first_day = date('Y-m').'-01 00:00:00';
$sql_query = 'SELECT SUM(b.sum) AS bonus FROM ne_bonuses AS b WHERE b.user='.$uin.' AND b.status=1 AND b.date_pay>="'.$first_day.'"';
$result = mysql_query($sql_query);
$number = mysql_num_rows($result);
	if($number) $bonus = round(mysql_result($result,0,"bonus"),2);
	
$sql_query = 'SELECT SUM(p.sum) AS purchases FROM ne_purchases AS p WHERE p.user='.$uin.' AND p.status=2';
$result = mysql_query($sql_query);
$number = mysql_num_rows($result);
	if($number) $purchases = mysql_result($result,0,"purchases");
	
$sql_query = 'SELECT SUM(u.sum) AS pays FROM ne_user_pay AS u WHERE u.user='.$uin.' AND u.status=1 AND u.type=1';
$result = mysql_query($sql_query);
$number = mysql_num_rows($result);
	if($number) $pays = mysql_result($result,0,"pays");
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Персональная статистика по вашему аккаунту:</div>
</div>
<div id="data"><table width="100%" border="0" cellpadding="10" cellspacing="0">
<form name="form" method="post" action="change_info.php">
  <tr class="r1">
  <td width="50%">Ваш паевый взнос:</td>
  <td width="50%"><b><?php
	if($contributions) echo $contributions;
	else echo 'В разработке (или нет данных)';
?></b></td>
  </tr>
  <tr class="r2">
    <td width="50%">Ваш баланс:</td>
    <td width="50%"><b><?php
	if($balans) echo $balans;
	else echo 'В разработке (или нет данных)';
?></b></td>
    </tr>
  <tr class="r1">
    <td width="50%">Ваш бонус за прошлый месяц:</td>
    <td width="50%"><b><?php
	if($bonus) echo $bonus;
	else echo 'В разработке (или нет данных)';
?></b></td>
    </tr>
  <tr class="r2">
    <td width="50%">Начисленные дивиденды (кооп. выплаты):</td>
    <td width="50%"><b><?php
	if($divs) echo $divs;
	else echo 'В разработке (или нет данных)';
?></b></td>
    </tr>
  <tr class="r1">
    <td width="50%">Сумма оплаченных покупок (после вступления в кооператив):</td>
    <td width="50%"><b><?php
	if($purchases) echo $purchases;
	else echo 'В разработке (или нет данных)';
?></b></td>
    </tr>
  <tr class="r2">
    <td width="50%">Сумма оплаченных членских взносов:</td>
    <td width="50%"><b><?php
	if($pays) echo $pays;
	else echo 'В разработке (или нет данных)';
?></b></td>
    </tr>
</form>
</table>
</div>
<?php
include ('templates/footer.tpl');
?>