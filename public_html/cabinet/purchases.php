<?php
session_start();
$title = 'Управление заказами';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('../supple/auth_db.php');
require ('../supple/server_root.php');

require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$sum = $protection->post['sum'];
$check_1 = $protection->post['check_1'];
$check_2 = $protection->post['check_2'];
$address = $protection->post['address'];
$time = $protection->post['time'];

	if($protection->post['approve_2']){
	define('FPDF_FONTPATH','font/');
	require('fpdf.php');
	require('purchase.class.php');

	$purchases = new Purchase();
	$purchases->Open();

	$purchases->AddFont('ArialMT','','arial.php');
	$purchases->AddFont('TimesNewRomanPSMT','','times.php');
	$purchases->AliasNbPages();
	$purchases->AddPage();
	$purchases->PrintTitle('Лист закупки товаров','../images/logo.gif','Кооператив "НОВЫЙ ЭДЕМ"');
	
	$header = array("ID","Наименование продукта","Кол-во","Цена","Итого","Прим.");
	$data = $purchases->LoadData($_SESSION['city'],$check_2);
	$purchases->ImprovedTable($header,$data);
// Напечатать лист с адресами и телефонами заказчиков!
	$purchases->AddPage();
	$data = $purchases->LoadAddress($check_2);
	$purchases->ListAddress($data);
// Напечатать лист оплаты членских взносов!
	$purchases->AddPage();
	$header = array("Pay ID","UIN","Сумма","Факт. сумма","Подпись");
	$data = $purchases->LoadPayUser($check_2);
	$purchases->TablePayUser($header,$data);
	
	$purchases->TablesPuschases($_SESSION['city'],$check_2);

		if(($protection->post['change'])&&$purchases->UpdateStatus($check_2,$_SESSION['logged_user'])){
		$purchases->Output('list_zakupki.pdf','D');
//		$purchases->Output();
		}
		else $purchases->Output('list_zakupki.pdf','D');
	}
?>
<?php
include ('templates/header.tpl');
?>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Заказы города 
<?php
	if($_SESSION['logged_status']>3) echo '<a href="change_city.php">'.$_SESSION['city'].'</a>';
	else echo $_SESSION['city'];
?>
  :</div>
</div>
<?php
	if($protection->post['next_1']){
		if(is_array($check_1)&&is_array($sum)){
		echo '<div id="data">
<form name="purchases" method="post" action="">
 <table width="300" cellspacing="1">
  <thead>
   <tr>
    <td width="50%">ID Заказа</td>
    <td width="50%">Оплаченная сумма (руб.)</td>
    </tr>
  </thead>
  <tbody>
   ';
		$i=1;
		$itogo=0;
			foreach($check_1 as $key => $value){
			$x=$sum[$value];
				if(!$x) $x=0;
			echo '<tr class="';
				if((ceil($i/2)*2)!=$i) echo 'r2';
				else echo 'r1';
			echo '">
	<td align="right"><span style="font-weight: bold">'.$value.'</span></td>
	<td><span style="font-weight: bold">'.$x.'</span><input type="hidden" name="check_1[]" value="'.$value.'"><input type="hidden" name="sum['.$value.']" value="'.$x.'"></td>
   </tr>
   ';
			$itogo+=$sum[$value];
			$i++;
			}
		echo '<tr class="r4">
     <td align="right"><span style="font-weight: bold">Итого:</span></td>
     <td><span style="font-weight: bold">'.$itogo.'</span><input type="hidden" name="itogo" value="'.$itogo.'"></td>
   </tr>
';
		}
		else{
		echo '<div id="data">
<form name="purchases" method="post" action="">
 <table width="300" cellspacing="1">
  <thead>
   <tr>
    <td colspan="2"><b>Не было обработано ни одного заказа?</b></td>
   </tr>
  </thead>
  <tbody>
   ';
		}
	echo '<tr class="r3">
    <td><input name="back_1" type="submit" id="back_1" value="Назад"></td>
	<td><input name="approve_1" type="submit" id="approve_1" value="Далее"></td>
   </tr>
  </tbody>
 </table>
</form>
</div>
';
	}
	elseif($protection->post['next_2']){
		if(is_array($check_2)&&is_array($address)){
		echo '<div id="data">
<form name="purchases" method="post" action="">
 <table width="600" cellspacing="1">
  <thead>
   <tr>
    <td width="20%">ID Заказа</td>
    <td width="70%">Адрес (и время доставки)</td>
   </tr>
  </thead>
  <tbody>
   ';
			foreach($check_2 as $key => $value){
			echo '<tr class="';
				if((ceil($key/2)*2)!=$key) echo 'r2';
				else echo 'r1';
			echo '">
	<td align="right"><span style="font-weight: bold">'.$value.'</span><input type="hidden" name="check_2[]" value="'.$value.'"></td>
	<td><span style="font-weight: bold">'.$address[$value];
				if($time[$value]&&($time[$value]!=1)) echo ' ('.$time[$value].')';
			echo '</span><input type="hidden" name="address['.$value.']" value="'.$address[$value].'"></td>
   </tr>';
			}
		echo '
   <tr class="r3">
    <td><input name="back_2" type="submit" id="back_2" value="Назад"></td>
	<td><input name="approve_2" type="submit" id="approve_2" value="Скачать лист закупок"><input type="checkbox" name="change" value="checkbox"> Изменить статусы заказов (на "в процессе")</td>
   </tr>
  </tbody>
 </table>
</form>
</div>
';
		}
	}
	elseif($protection->post['approve_1']||$protection->post['back_2']){
	require ('../supple/normal_date.php');

		if(is_array($sum)){
		$itogo=0;
			foreach($sum as $key => $value){
				if(!$value) $value=0;
			$sql_query = 'UPDATE ne_purchases SET status="2", sum="'.$value.'" WHERE id="'.$key.'"';
				if(@mysql_query($sql_query)) $itogo+=$value;
			}
			if($itogo!=$protection->post['itogo']){
			echo '
 <table width="100%" cellspacing="0">
   <tr>
     <td><p align="justify" style="color:#FF0000;">Произошли ошибки при обновлении таблицы Базы Данных. Пожалуйста, проделайте еще раз предыдущую операцию позже.</p></td>
   </tr>
 </table>
';
			}
		}
	$sql_query = 'SELECT * FROM ne_purchases AS p, ne_baskets AS b, items AS i WHERE b.id_purchase=p.id AND b.id_product=i.id AND i.city="'.$_SESSION['city'].'" AND p.status<"2" GROUP BY p.id';
	$result = mysql_query($sql_query);
	$number = mysql_num_rows($result);
		if($number){
		echo '<div id="data">
<form name="purchases" method="post" action="">
 <table width="100%" cellspacing="1">
  <thead>
   <tr>
    <td width="5%" align="center">Выбрать</td>
    <td width="5%" align="center">ID</td>
    <td width="5%">Дата</td>
    <td width="35%">Адрес, время доставки. Телефон, Имя клиента</td>
    <td width="50%">Примечание клиента</td>
   </tr>
  </thead>
  <tbody>
   ';
		$i=0;
			while($i<$number){
			$id = mysql_result($result,$i,"p.id");
			$date = NormalDate(mysql_result($result,$i,"p.date"));
				if(is_array($address)) $addr = $address[$id];
				if(!$addr) $addr = mysql_result($result,$i,"p.address");
			$time = mysql_result($result,$i,"p.time_of");
				if($time==2) $time = 'Утром';
				elseif($time==3) $time = 'Днем';
				elseif($time==4) $time = 'Вечером';
				elseif($time==5) $time = 'Ночью';
				
			$phone = mysql_result($result,$i,"p.phone");
			$name = mysql_result($result,$i,"p.name_1");
			$prim = mysql_result($result,$i,"p.description");
			
			echo '<tr class="';
				if((ceil($i/2)*2)!=$i) echo 'r2';
				else echo 'r1';
			echo '">
    <td align="right"><input type="checkbox" name="check_2[]" value="'.$id.'"';
				if(!is_array($check_2)) echo ' checked';
				else{
					foreach($check_2 as $key => $value){
						if($id==$value){
						echo ' checked';
						break;
						}
					}
				}
			echo '></td>
    <td align="center">'.$id.'</td><td align="center">'.$date.'</td>
	<td><input name="address['.$id.']" type="text" size="50" maxlength="50" value="'.$addr.'"><input type="hidden" name="time['.$id.']" value="'.$time.'">';
				if($time!=1) echo '<br>'.$time;
			echo '<br>'.$phone;
				if($name) echo ', '.$name;
			echo '</td>
    <td>'.$prim.'</td>
   </tr>
 ';
			$addr='';
			$i++;
			}
		echo '<tr class="r3">
     <td colspan="5" align="left"><input name="next_2" type="submit" value="Далее"></td>
     </tr>
  </tbody>
 </table>
</form>
</div>';
		}
		else echo '
 <table width="100%" cellspacing="0">
   <tr>
     <td><p align="justify" style="color:#FF0000;">Новых (необработанных) заказов в Базе Данных не обнаружено.</p></td>
   </tr>
 </table>
';
	}
	else{
	$city = $_SESSION['city'];

	$str_sql_query = 'SELECT * FROM ne_purchases AS p, items AS i, ne_baskets AS b WHERE b.id_product=i.id AND b.id_purchase=p.id AND i.city = "'.$city.'"';
		if($_SESSION['logged_status']<3) $str_sql_query .= ' AND p.admin="'.$_SESSION['logged_user'].'"';
	$str_sql_query .= ' AND p.status="1" GROUP BY p.id ORDER BY p.id ASC';

	$result = mysql_query($str_sql_query);
	$number = mysql_num_rows($result);
		if($number){
		echo '<div id="data">
<form name="purchases" method="post" action="">
 <table width="300" cellspacing="1">
  <thead>
   <tr>
    <td width="34%">Обработано</td>
    <td width="33%">ID Заказа</td>
    <td width="33%">Оплаченная сумма (руб.)</td>
    </tr>
  </thead>
  <tbody>
   ';
		$i=0;
			while($i<$number){
			$id = mysql_result($result,$i,"p.id");
			echo '<tr class="';
				if((ceil($i/2)*2)!=$i) echo 'r2';
				else echo 'r1';
			echo '">
	<td align="right"><input type="checkbox" name="check_1[]" value="'.$id.'"';
				if(is_array($check_1)){
					foreach($check_1 as $key => $value){
						if($id==$value){
						echo ' checked';
						break;
						}
					}
				}
			echo '></td>
   	<td align="right"><span style="font-weight: bold">'.$id.'</span></td>
	<td><input name="sum['.$id.']" type="text"';
				if(is_array($sum)) echo ' value="'.$sum[$id].'"';
			echo ' size="10" maxlength="10"></td>
   </tr>
   ';
			$i++;
			}
		echo '   <tr class="r3">
    <td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><input name="next_1" type="submit" value="Далее"></td>
   </tr>
  </tbody>
 </table>
</form>
</div>';
		}
		else echo '<div id="data">
<form name="purchases" method="post" action="">
 <table width="300" cellspacing="0">
   <tr>
     <td><p align="justify" style="color:#FF0000;">Заказов в обработке в Базе Данных не обнаружено.</p></td>
   </tr>
   <tr class="r3">
	<td><input name="approve_1" type="submit" value="Далее"></td>
   </tr>
 </table>
</form>
</div>
';
	}
?>
<?php
include ('templates/footer.tpl');
?>