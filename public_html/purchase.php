<?php
session_start();
$basket = $_COOKIE['basket'];
	if(!$basket) $basket = $_SESSION['basket'];
$list = $_COOKIE['list'];
	if(!$list) $list = $_SESSION['list'];

	if((!isset($_SESSION['logged_user']))&&($basket||$list)){
	header("Location: ../reg.php?purchase=true");
	exit();
	}
	elseif((!$basket)&&(!$list)){
	header("Location: ../shop/items/");
	exit();
	}

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

	if($_COOKIE['referer']) $_SESSION['referer'] = $_COOKIE['referer'];
	elseif($protection->get['referer']){
	$_SESSION['referer'] = $protection->get['referer'];
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $protection->get['referer'], $end_cookie, '/');
	}
//echo $referer;

include ('supple/server_root.php');
require ('supple/auth_db.php');
include ('supple/mail.php');

	if(!isset($_SESSION['city'])){
		if ($_COOKIE['city']){
		$str_sql_query = 'SELECT name, icq, phone, admin, country FROM cities WHERE name = "'.$_COOKIE['city'].'"';
		$result = mysql_query($str_sql_query) or die(mysql_error());
		$number = mysql_num_rows($result);
			if(!$number){
			$str_sql_query = "SELECT name, icq, phone, admin, country FROM cities WHERE capital = '1'";
			$result = mysql_query($str_sql_query) or die(mysql_error());
			$number = mysql_num_rows($result);
				if($number){
				$i = 0;
			
				$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
				setcookie('city', mysql_result($result,$i,"name"), $end_cookie, '/');
		
				$_SESSION['city'] = mysql_result($result,$i,"name");
				$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
				$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
					if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
				$_SESSION['country'] = mysql_result($result,$i,"country");
				}
			}
			else{
			$i = 0;
		
			$_SESSION['city'] = $_COOKIE['city'];
			$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
			$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
				if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
			$_SESSION['country'] = mysql_result($result,$i,"country");
			}
		}
		else{
		$str_sql_query = "SELECT name, icq, phone, admin, country FROM cities WHERE capital = '1'";
		$result = mysql_query($str_sql_query) or die(mysql_error());
		$number = mysql_num_rows($result);
			if($number){
			$i = 0;
		
			$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
			setcookie('city', mysql_result($result,$i,"name"), $end_cookie, '/');
		
			$_SESSION['city'] = mysql_result($result,$i,"name");
			$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
			$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
				if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
			$_SESSION['country'] = mysql_result($result,$i,"country");
			}
		}
	}
	
$check = $protection->post['check'];
$check_list = $protection->post['check_list'];
$quantity = $protection->post['quantity'];

$info = $protection->post['info'];
$k_oplate = $protection->post['k_oplate'];
$new_purchase = $protection->post['new_purchase'];

$action = 'step_one';
	if($protection->post['next']){
		if(is_array($info)){
// Здесь надо проверить на наличие ошибок при заполнении:
			if($info['phone']&&$info['address']&&preg_match('/^[0-9-+ ]{7,}$/',$info['phone'])&&(is_array($check)||is_array($check_list))) $action = 'step_two';
			else $err = 1;
		}
	}
	
function MenuItem($url,$ancor){
	if(ereg('/'.$url,$_SERVER['REQUEST_URI'])) $class = 'mn2';
	else $class = 'mn1';
$str = '<div class="'.$class.'"><a href="'.$url.'">'.$ancor.'</a></div>';
return $str;
}

function TrTable($str_in){
$str_out = '
      <tr>
        <td bgcolor="#FFFFFF" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;" class="text">'.$str_in.'</td>
      </tr>
      ';
return $str_out;
}
function TdDostavka($str_in){
$str_out = '<p style="background-color:#99FFCC;">Информация по доставке и оплате в вашем городе:</p>
           <p align="justify">'.$str_in.'</p>';
return $str_out;
}
function TdError($str_in){
$str_out = '<p align="justify" style="color:#FF0000;">'.$str_in.'</p>';
return $str_out;
}
function TdReady($str_in){
$str_out = '<p align="justify">'.$str_in.'</p>';
return $str_out;
}
function TdUserPay($sum_pay,$sum_purchases,$sum,$k_oplate,$action,$sum_next){
	if($action=='step_one'){
		if(!$sum_pay) $sum_pay = 0;
		if(!$sum_purchases) $sum_purchases = 0;
	$str_out = '<p style="background-color:#99FFCC;">Членский взнос:</p>
            <div id="list">
              <table width="100%"  border="0" cellspacing="0" cellpadding="10">
                <thead>
                  <tr>
                    <td width="26%">Сумма заказов (рублей) </td>
                    <td width="27%">Сумма членских взносов (рублей) </td>
                    <td width="27%">Лимит покупок (рублей)</td>
                    <td width="20%">Оплатить ЧВ (рублей)</td>
                  </tr>
                </thead>
                <tr class="r1">
                  <td>'.$sum_purchases.'</td>
                  <td>'.$sum_pay.'</td>
                  <td>'.$sum_next.'</td>
                  <td><select name="k_oplate"><option value="0">&nbsp;</option>';
		for($i=5;$i<100;$i++){
		$k = $i*100;
		$str_out .= '<option value="'.$k.'"';
			if(($sum_next<=0)&&($i==5)) $str_out .= ' selected';
		$str_out .= '>'.$k.'</option>';
		}
	$str_out .= '</select></td>
                </tr>
              </table>
            </div>';
	}
	elseif($action=='step_two'){
	$str_out = '<p style="background-color:#99FFCC;">Членский взнос:</p>
            <div id="list">
              <table width="100%"  border="0" cellspacing="0" cellpadding="10">
                <thead>
                  <tr>
                    <td width="26%">Сумма заказов (рублей) </td>
                    <td width="27%">Сумма членских взносов (рублей) </td>
                    <td width="27%">Лимит покупок (рублей)</td>
                    <td width="20%">Оплатить ЧВ (рублей)</td>
                  </tr>
                </thead>
                <tr class="r1">
                  <td>'.$sum_purchases.'</td>
                  <td>'.$sum_pay.'</td>
                  <td>'.$sum_next.'</td>
                  <td><span style="color:#FF0000;">'.$k_oplate.'</span><input type="hidden" name="k_oplate" value="'.$k_oplate.'"><input type="hidden" name="sum_purchase" value="'.$sum.'"></td>
                </tr>
              </table>
            </div>';
	}
return $str_out;
}
function TdBasket($basket,$check,$quantity,$action){
	if($action=='step_one'){
	$str_out = '<p style="background-color:#99FFCC;">Корзина:</p>
            <div id="list">
              <table width="100%"  border="0" cellspacing="0" cellpadding="10">
			<thead>
                <tr>
                  <td>Оставить</td>
                  <td>Количество лотов</td>
                  <td>Цена</td>
                  <td>Наименование продукта</td>
                  </tr>
			</thead>
                ';
		foreach($basket as $key => $value){
		$str_out .= '<tr class="';
			if((ceil($key/2)*2)!=$key) $str_out .= 'r2';
			else $str_out .= 'r1';
		$str_out .= '">
                  <td width="10%"><input name="check[]" type="checkbox" id="check[]" value="'.$value['id'].'"';
			if($check){
				foreach($check as $subk => $subv){
					if($value['id']==$subv){
					$str_out .= ' checked';
					break;
					}
				}
			}
			else $str_out .= ' checked';
		
		$str_out .= '></td>
                  <td width="25%"><input name="quantity['.$value['id'].']" type="text" id="quantity" size="3" maxlength="5"';
// Вывод количества
			foreach($quantity as $subk => $subv){
				if($value['id']==$subk){
				$quant = $subv;
				break;
				}
			}
			if($quant) $str_out .= ' value="'.$quant.'"';
			else $str_out .= ' value="1"';
		
		$str_out .= '>';
/*Возможный вариант вывода количества. Почему-то не работает
			if(is_array($quantity)){
				foreach($quantity as $subk => $subv){
					if($value['id']==$subk){
					$quant = $subv;
					break;
					}
				}
			}
			else $quant = 1;
		$str_out .= '></td>
                  <td width="15%"><select name="quantity['.$value['id'].']" multiple size="1">
  ';
			for($i=1;$i<=100;$i++){
			$str_out .= '<option value="'.$i.'"';
				if($i==$quant) $str_out .= ' selected';
			$str_out .= '>'.$i.'</option>
  ';
			}
		$str_out .= '</select>';
*/
		$str_out .= '(1 лот = '.$value['lot'].' '.$value['ed_izmer'].')';
//			if($value['ed_izmer']) $str_out .= ' '.$value['ed_izmer'];
			
/*			if(!$_SESSION['logged_status']) $price = 1.5*$value['price'];
			else $price = $value['price'];
*/
		$price = $value['price'];
		
		$str_out .= '</td>
                  <td width="15%">'.$price.' р';
			if($value['ed_izmer']) $str_out .= '/'.$value['ed_izmer'];
		$str_out .= '</td>
                  <td width="50%">'.$value['name'].'</td>
                </tr>';
		}
	$str_out .= '
              </table>
          </div>
';
	}
	elseif($action=='step_two'){
		if(is_array($check)){
		$str_out = '<p style="background-color:#99FFCC;">Корзина:</p>
            <div id="list">
              <table width="100%"  border="0" cellspacing="0" cellpadding="10">
                <thead>
                  <tr>
                    <td width="50%">Наименование продукта</td>
                    <td width="20%">Количество лотов</td>
                    <td width="10%">Цена</td>
                    <td width="20%">Сумма (рублей)</td>
                  </tr>
                </thead>';
		$sum_full = 0;
			foreach($check as $key => $value){
				if($quantity[$value]&&is_numeric($quantity[$value])) $quant = round(abs($quantity[$value]));
				else $quant = 1;
			$str_out .= '<tr class="';
				if((ceil($key/2)*2)!=$key) $str_out .= 'r2';
				else $str_out .= 'r1';
			$str_out .= '">
                  <td width="50%">';
				foreach($basket as $subk => $subv){
					if($subv['id']==$value){
					$name = $subv['name'];
					$ed_izmer = $subv['ed_izmer'];
					$lot = $subv['lot'];
					$price = $subv['price'];
						if(!$_SESSION['logged_status']) $price *= 1.5;
					$sum = $quant*$price;
					break;
					}
				}
			$str_out .= $name.'</td>
                  <td width="20%">'.$quant.' (1 лот = '.$lot.' '.$ed_izmer.')</td>
                  <td width="10%">'.$price.' р';
				if($ed_izmer) $str_out .= '/'.$ed_izmer;
			$sum = $price*$quant*$lot;
			$str_out .= '<input type="hidden" name="check[]" value="'.$value.'">';
			$str_out .= '</td>
                  <td width="20%">'.$sum.'</td>
                </tr>
			';
			$sum_full += $sum;
			}
		$str_out .= '<tfoot>
                <tr>
                  <th width="60%">Итого:</th>
                  <td width="10%">&nbsp;</td>
                  <td width="10%">&nbsp;</td>
                  <td width="20%"><span style="color:#FF0000;">'.$sum_full.'</span>';
			foreach($quantity as $key => $value){
			$str_out .= '<input type="hidden" name="quantity['.$key.']" value="';
				if(is_numeric($value)) $str_out .= abs($value);
				else $str_out .= '1';
			$str_out .= '">';
			}
		$str_out .= '<input type="hidden" name="sum_full" value="'.$sum_full.'"></td>
                </tr>
			</tfoot>
              </table>
          </div>
';
		}
		else $str_out = '<p style="background-color:#99FFCC;">Корзина:</p>Вы не оставили ни одного продукта из своей корзины.';
	}
return $str_out;
}
function TdList($list,$check,$action){
	if($action=='step_one'){
	$str_out = '<p style="background-color:#99FFCC;">Лист ожидания:</p>
            <div id="list"><table width="100%"  border="0" cellspacing="0" cellpadding="10">
			  <thead>
                <tr>
                  <td width="10%">Оставить</td>
                  <td width="10%">Цена</td>
                  <td width="80%">Наименование продукта</td>
                  </tr>
			  </thead>';
		foreach($list as $key => $value){
		$str_out .= '<tr class="';
			if((ceil($key/2)*2)!=$key) $str_out .= 'r2';
			else $str_out .= 'r1';
		$str_out .= '">
                  <td width="10%"><input name="check_list[]" type="checkbox" value="'.$value['id'].'"';
			if($check){
				foreach($check as $subk => $subv){
					if($value['id']==$subv) $str_out .= ' checked';
				}
			}
			else $str_out .= ' checked';
		$str_out .= '></td>
                  <td width="10%">'.$value['price'].' р';
			if($value['ed_izmer']) $str_out .= '/'.$value['ed_izmer'];
		$str_out .= '</td>
                  <td width="80%">'.$value['name'].'</td>
                </tr>';
		}
$str_out .= '</table>
          </div>';
	}
	elseif($action=='step_two'){
		if(is_array($check)){
		$str_out = '<p style="background-color:#99FFCC;">Лист ожидания:</p>
            <div id="list">
              <table width="100%"  border="0" cellspacing="0" cellpadding="10">
                <thead>
                  <tr>
                    <td width="90%">Наименование продукта</td>
                    <td width="10%">Цена</td>
                  </tr>
                </thead>
                ';
			foreach($check as $key => $value){
			$str_out .= '<tr class="';
				if((ceil($key/2)*2)!=$key) $str_out .= 'r2';
				else $str_out .= 'r1';
			$str_out .= '">
                  <td width="90%">';
				foreach($list as $subk => $subv){
					if($subv['id']==$value){
					$name = $subv['name'];
					$ed_izmer = $subv['ed_izmer'];
					$price = $subv['price'];
					break;
					}
				}
			$str_out .= $name.'</td>
                  <td width="10%">'.$price.' р';
				if($ed_izmer) $str_out .= '/'.$ed_izmer;
			$str_out .= '<input type="hidden" name="check_list[]" value="'.$value.'">';
			$str_out .= '</td>
                </tr>';
			}
		$str_out .= '
		</table>
          </div>';
		}
		else $str_out = '<p style="background-color:#99FFCC;">Лист ожидания:</p>Вы не оставили ни одного продукта в листе ожидания';
	}
return $str_out;
}
function TdInfo($info,$action,$new_purchase){
	if($action=='step_one'){
	$str_out = '<p style="background-color:#99FFCC;">Дополнительная информация:</p>
          <div id="list"><table width="100%"  border="0" cellspacing="0" cellpadding="10">
                <tr class="r2">
                  <td width="25%" class="r3">&nbsp;</td>
                  <td width="75%" class="r3"><input name="new_purchase" type="checkbox" value="checkbox">Оформить как новый заказ (на другой адрес)</td>
                </tr>
                <tr class="r1">
                  <td width="25%"><span style="color:#FF0000;">(*)</span> Контактный телефон:</td>
                  <td width="75%"><input name="info[phone]" type="text" id="phone" size="18" maxlength="26"';
		if($info['phone']) $str_out .= ' value="'.$info['phone'].'"';
		elseif($_COOKIE['info_phone']) $str_out .= ' value="'.$_COOKIE['info_phone'].'"';
		elseif($_SESSION['info_phone']) $str_out .= ' value="'.$_SESSION['info_phone'].'"';
	$str_out .= '></td>
                </tr>
                <tr class="r2">
                  <td width="25%"><span style="color:#FF0000;">(*)</span> Имя:</td>
                  <td width="75%"><input name="info[name_1]" type="text" id="name_1" size="36" maxlength="45"';
		if($info['name_1']) $str_out .= ' value="'.$info['name_1'].'"';
		elseif($_COOKIE['info_name_1']) $str_out .= ' value="'.$_COOKIE['info_name_1'].'"';
		elseif($_SESSION['info_name_1']) $str_out .= ' value="'.$_SESSION['info_name_1'].'"';
	$str_out .= '></td>
                </tr>
                <tr class="r1">
                  <td width="25%">Отчество:</td>
                  <td width="75%"><input name="info[name_2]" type="text" id="name_2" size="36" maxlength="45"';
		if($info['name_2']) $str_out .= ' value="'.$info['name_2'].'"';
		elseif($_COOKIE['info_name_2']) $str_out .= ' value="'.$_COOKIE['info_name_2'].'"';
		elseif($_SESSION['info_name_2']) $str_out .= ' value="'.$_SESSION['info_name_2'].'"';
	$str_out .= '></td>
                </tr>
                <tr class="r2">
                  <td width="25%">Фамилия:</td>
                  <td width="75%"><input name="info[name_3]" type="text" id="name_3" size="36" maxlength="45"';
		if($info['name_3']) $str_out .= ' value="'.$info['name_3'].'"';
		elseif($_COOKIE['info_name_3']) $str_out .= ' value="'.$_COOKIE['info_name_3'].'"';
		elseif($_SESSION['info_name_3']) $str_out .= ' value="'.$_SESSION['info_name_3'].'"';
	$str_out .= '></td>
                </tr>
                <tr class="r1">
                  <td width="25%"><span style="color:#FF0000;">(*)</span> Адрес доставки: </td>
                  <td width="75%"><input name="info[address]" type="text" id="address" size="63" maxlength="150"';
		if($info['address']) $str_out .= ' value="'.$info['address'].'"';
		elseif($_COOKIE['info_address']) $str_out .= ' value="'.$_COOKIE['info_address'].'"';
		elseif($_SESSION['info_address']) $str_out .= ' value="'.$_SESSION['info_address'].'"';
	$str_out .= '></td>
                </tr>
                <tr class="r2">
                  <td colspan="2">Поля, отмеченные <span style="color:#FF0000;">(*)</span>, являются обязательными </td>
                </tr>
                <tr class="r1">
                  <td width="25%" valign="top">Примечания:</td>
                  <td width="75%"><textarea name="info[desc]" cols="54" rows="5">';
		if($info['desc']) $str_out .= $info['desc'];
		elseif($_COOKIE['info_desc']) $str_out .= $_COOKIE['info_desc'];
		elseif($_SESSION['info_desc']) $str_out .= $_SESSION['info_desc'];
	$str_out .= '</textarea></td>
                </tr>
                <tr class="r2">
                  <td>Предпочтительное время доставки: </td>
                  <td><select name="info[time]">
                    <option value="1"';
		if((!$info['time'])||($info['time']==1)) $str_out .= ' selected';
	$str_out .= '>Не важно</option>
                    <option value="2"';
		if($info['time']==2) $str_out .= ' selected';
	$str_out .= '>Утром</option>
                    <option value="3"';
		if($info['time']==3) $str_out .= ' selected';
	$str_out .= '>Днем</option>
                    <option value="4"';
		if($info['time']==4) $str_out .= ' selected';
	$str_out .= '>Вечером</option>
                    <option value="5"';
		if($info['time']==5) $str_out .= ' selected';
	$str_out .= '>Ночью</option>
                  </select></td>
                </tr>
                <tr class="r1">
                  <td>&nbsp;</td>
                  <td><input name="info[remember]" type="checkbox" id="remember" value="checkbox"';
		if($info['remember']) $str_out .= ' checked';
	$str_out .= '>Запомнить введённую информацию</td>
                </tr>
              </table>
          </div>';
	}
	elseif($action=='step_two'){
	$n = 1;
	$str_out = '<p style="background-color:#99FFCC;">Дополнительная информация:</p>
            <div id="list">
              <table width="100%"  border="0" cellspacing="0" cellpadding="10">
                <tr class="r1">
                  <td width="50%" class="r3">Контактный телефон:</td>
                  <td width="50%" class="r3">'.$info['phone'].'</td>
                </tr>';
		if($info['name_1']){
		$str_out .= '
                <tr class="r2">
                  <th width="50%">Имя:</th>
                  <td width="50%">'.$info['name_1'].'</td>
                </tr>';
		$n++;
		}
		if($info['name_2']){
		$str_out .= '
                <tr class="';
			if(ceil($n/2)==($n/2)) $str_out .= 'r1';
			else $str_out .= 'r2';
		$str_out .= '">
                  <th width="50%">Отчество:</th>
                  <td width="50%">'.$info['name_2'].'</td>
                </tr>';
		$n++;
		}
		if($info['name_3']){
		$str_out .= '
                <tr class="';
			if(ceil($n/2)==($n/2)) $str_out .= 'r1';
			else $str_out .= 'r2';
		$str_out .= '">
                  <th width="50%">Фамилия:</th>
                  <td width="50%">'.$info['name_3'].'</td>
                </tr>';
		$n++;
		}
		if($info['address']){
		$str_out .= '
                <tr class="';
			if(ceil($n/2)==($n/2)) $str_out .= 'r1';
			else $str_out .= 'r2';
		$str_out .= '">
                  <th width="50%">Адрес доставки:</th>
                  <td width="50%">'.$info['address'].'</td>
                </tr>';
		$n++;
		}
/*		if($info['save_in_db']){
		$str_out .= '
                <tr class="';
			if(ceil($n/2)==($n/2)) $str_out .= 'r1';
			else $str_out .= 'r2';
		$str_out .= '">
                  <th colspan="2">Информация будет записана в БД (в личные данные аккаунта)</th>
                </tr>';
		$n++;
		}*/
		if($info['desc']){
		$str_out .= '
                <tr class="';
			if(ceil($n/2)==($n/2)) $str_out .= 'r1';
			else $str_out .= 'r2';
		$str_out .= '">
                  <th width="50%">Примечания:</th>
                  <td width="50%">'.$info['desc'].'</td>
                </tr>';
		$n++;
		}
		if($info['time']>1){
		$str_out .= '<tr class="';
			if(ceil($n/2)==($n/2)) $str_out .= 'r1';
			else $str_out .= 'r2';
		$str_out .= '">
                  <th width="50%">Предпочтительное время доставки:</th>
                  <td width="50%">';
			if($info['time']==2) $str_out .= 'Утром';
			elseif($info['time']==3) $str_out .= 'Днем';
			elseif($info['time']==4) $str_out .= 'Вечером';
			elseif($info['time']==5) $str_out .= 'Ночью';
			
		$str_out .= '</td>
                </tr>
              ';
		$n++;
		}
		if($new_purchase){
		$str_out .= '
				<tr class="';
			if(ceil($n/2)==($n/2)) $str_out .= 'r1';
			else $str_out .= 'r2';
		$str_out .= '">
                  <td width="50%">&nbsp;</td>
                  <td width="50%">Заказ будет доставлен на адрес, отличный от адреса вашего предыдущего заказа (если он есть)</td>
                </tr>';
		}
		foreach($info as $key => $value) $str_out .= '<input type="hidden" name="info['.$key.']" value="'.$value.'">';
	$str_out .= '<input type="hidden" name="new_purchase" value="'.$new_purchase.'"></table>
          </div>';
	}
return $str_out;
}
function TrApprove($action,$itog_sum){
	if($action=='step_one'){
	$str_out = '		  
      <tr>
        <td align="center" bgcolor="#FFFFFF" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;"><input name="next" type="submit" id="next" value="Далее"></td>
      </tr>
      ';
	}
	elseif($action=='step_two'){
	$str_out = '		  
      <tr>
        <td align="left" bgcolor="#FFFFFF" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="50%" class="text">Общая сумма к оплате с учетом ЧВ: <span style="color:#FF0000; font:bold 18px Arial;">'.$itog_sum.'</span> рублей</td>
            <td width="50%"><input name="approve" type="submit" id="approve" value="Подтвердить заказ"><input name="back" type="submit" id="back" value="Вернуться назад"></td>
          </tr>
        </table></td>
      </tr>
      ';
	}
return $str_out;
}
function CheckAutoPay($k_oplate){
$x = 0;
$sql_query = 'SELECT autopay FROM customers WHERE uin="'.$_SESSION['logged_user'].'"';
$result = mysql_query($sql_query) or die(mysql_error());
	if(mysql_result($result,0,"autopay")==1){
	$sql_query = 'SELECT * FROM ne_user_pay WHERE user="'.$_SESSION['logged_user'].'" AND status="1"';
	$result = mysql_query($sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
	$balans = 0;
		if($number){
		$i = 0;
			while($i < $number){
				if(mysql_result($result,$i,"type")>=5) $balans += mysql_result($result,$i,"sum");
				else $balans -= mysql_result($result,$i,"sum");
			$i++;
			}
		}
		if(($balans)&&($balans>$k_oplate)) $x=1;
	}
return $x;
}
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
<?php
include ('templates/header.tpl');
?>
<title>Ирий Сад :: магазин фрукты овощи. Подтверждение заказа.</title>
</head>

<body>
<table width="1000" height="100%" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_left"><h1>Потребительский кооператив &quot;Ирий Сад&quot;</h1>
	<h2>качественные продукты для сыроедов</h2>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo 'Тел.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">выбрать другой город</a></p></td>
    <td width="200"><img src="images/head2_02.jpg" width="200" height="150"></td>
    <td width="400" align="right" valign="bottom" class="head_right">
<?php
	if(!isset($_SESSION['logged_user'])){
	include ('templates/enter_panel.tpl');
	}
	else include ('templates/exit_panel.tpl');
?>
	</td>
  </tr>
<?php
include ('templates/menu.tpl');
?>
  <tr>
    <td colspan="3" class="menu"><table width="100%"  border="0" cellspacing="20" cellpadding="5">
<?php
if($basket) $basket=unserialize($basket);
if($list) $list=unserialize($list);
	if(!$protection->post['approve']){
	echo '<form action="" method="post" name="pay">';
		if($err){
		$str = 'ОШИБКА: не заполнены (или неверно заполнены) обязательные поля в разделе &quot;Дополнительная информация&quot;!';
		echo TrTable(TdError($str));
		}

		if(!$_SESSION['dostavka']){
		$sql_query = 'SELECT dostavka FROM cities WHERE name = "'.$_COOKIE['city'].'"';
		$result = mysql_query($sql_query,$link) or die(mysql_error());
		$number = mysql_num_rows($result);
			if($number) $_SESSION['dostavka'] = mysql_result($result,0,"dostavka");
			else $_SESSION['dostavka'] = 'Нет данных =(';
		}
	echo TrTable(TdDostavka($_SESSION['dostavka']));
	$sum_pay = $protection->post['sum_pay'];
		if(!$sum_pay&&$_SESSION['logged_status']){
		$sql_query = 'SELECT SUM(u.sum) AS sum_u FROM ne_user_pay AS u WHERE u.status="1" AND u.type="1" AND u.user="'.$_SESSION['logged_user'].'"';
		$result = @mysql_query($sql_query,$link);
//		$number = @mysql_num_rows($result);
		$users_pays = mysql_result($result,0,"sum_u");
		
		$sql_query = 'SELECT SUM(p.sum) AS sum_p,COUNT(id) AS quantity FROM ne_purchases AS p WHERE p.user="'.$_SESSION['logged_user'].'"';
		$result = @mysql_query($sql_query,$link);
		
		$users_purchases = mysql_result($result,0,"sum_p");
		$quantity_purchases = mysql_result($result,0,"quantity");
			if($users_purchases&&$quantity_purchases) $average = $users_purchases/$quantity_purchases;
			else $average = 0;
			
			if($average<400) $k=3;
			elseif($average>=400&&$average<800) $k=4;
			elseif($average>=800) $k=5;
		$sum_next = ($k*$users_pays) - $users_purchases;
		}
		
		$sum_purchase = 0;
		if($protection->post['next']){
			if(is_array($check)){
			
				foreach($check as $key => $value){
				$quantity[$value] = str_replace(',','.',$quantity[$value]);
					if($quantity[$value]&&is_numeric($quantity[$value])) $quant = abs($quantity[$value]);
					else $quant = 1;

					foreach($basket as $subk => $subv){
						if($subv['id']==$value){
						$price = $subv['price'];
						$lot = $subv['lot'];
							if(!$_SESSION['logged_status']) $price *= 1.5;
						break;
						}
					}
				$sum = $price*$quant*$lot;
				$sum_purchase += $sum;
				}
			}
		}
		if($basket){
		echo TrTable(TdBasket($basket,$check,$quantity,$action));
			if($_SESSION['logged_status']){
			$sum_next = ($k*$users_pays) - $users_purchases;
				if($sum_purchase>$sum_next){
				$next_pay=(100*ceil((($users_purchases+$sum_purchase)/$k)/100))-$users_pays;
//				$next_pay = 100*ceil((($sum_purchase+abs($sum_next))/3)/100);
					if($next_pay<500) $next_pay = 500;
					if($k_oplate<$next_pay) $k_oplate = $next_pay;
				}
/* Так было, когда были членские взносы...
			echo TrTable(TdUserPay($users_pays,$users_purchases,$sum_purchase,$k_oplate,$action,$sum_next));
*/
			}
		}
		if($list) echo TrTable(TdList($list,$check_list,$action));
		echo TrTable(TdInfo($info,$action,$new_purchase));
	
	$all_sum = $sum_purchase + $k_oplate;
	echo TrApprove($action,$all_sum);
	echo '</form>';
	}
	else{
		if(is_array($check)){
		$sum = $protection->post['sum_purchase'];
//		$sum = $protection->post['sum_full'];

			if($protection->post['new_purchase']){
			$sql_query = 'INSERT INTO ne_purchases (sum,user,phone,name_1,name_2,name_3,address,description,time_of) VALUES ("'.$sum.'","'.$_SESSION['logged_user'].'","'.$info['phone'].'","'.$info['name_1'].'","'.$info['name_2'].'","'.$info['name_3'].'","'.$info['address'].'","'.$info['desc'].'","'.$info['time'].'")';
			$result = @mysql_query($sql_query);
			$id_purchase = @mysql_insert_id();
			}
			else{
//			Нужно проверить, есть ли необработанные заказы от этого юзера. Если есть, то UPDATE. Если нет, то INSERT.
			$sql_query = 'SELECT id,sum FROM ne_purchases WHERE user='.$_SESSION['logged_user'].' AND status="0"';
			$result = @mysql_query($sql_query);
			$number = @mysql_num_rows($result);
				if($number){
				$id_purchase = mysql_result($result,0,"id");
				$sum += mysql_result($result,0,"sum");
				$sql_query = 'UPDATE ne_purchases SET sum="'.$sum.'",phone="'.$info['phone'].'",name_1="'.$info['name_1'].'",name_2="'.$info['name_2'].'",name_3="'.$info['name_3'].'",address="'.$info['address'].'",description="'.$info['desc'].'",time_of="'.$info['time'].'" WHERE id="'.$id_purchase.'"';
				$result = @mysql_query($sql_query);
				}
				else{
				$sql_query = 'INSERT INTO ne_purchases (sum,user,phone,name_1,name_2,name_3,address,description,time_of) VALUES ("'.$sum.'","'.$_SESSION['logged_user'].'","'.$info['phone'].'","'.$info['name_1'].'","'.$info['name_2'].'","'.$info['name_3'].'","'.$info['address'].'","'.$info['desc'].'","'.$info['time'].'")';
				$result = @mysql_query($sql_query);
				$id_purchase = @mysql_insert_id();
				}
			}
			
		$sql_query = 'INSERT INTO ne_baskets VALUES ';
			foreach($check as $key => $value){
				if($quantity[$value]) $quant = round(abs($quantity[$value]));
				else $quant = 1;

				foreach($basket as $subk => $subv){
					if($subv['id']==$value){
					$sql_query .= '(0,'.$id_purchase.',"'.$value.'","'.$quant.'"),';
					break;
					}
				}
			}
		$sql_query = preg_replace('/,$/','',$sql_query);
		$result = @mysql_query($sql_query);
		
			if($k_oplate){
			$sql_query_1 = 'DELETE FROM ne_user_pay WHERE user="'.$_SESSION['logged_user'].'" AND type="1" AND status="0"';
				if(mysql_query($sql_query_1,$link)){ 
				$desc = 'Членский взнос';
					if(CheckAutoPay($k_oplate)) $sql_query_2 = 'INSERT INTO ne_user_pay (sum,user,status,month,year_of,type,description) VALUES ("'.$k_oplate.'","'.$_SESSION['logged_user'].'","1","0","0","1","'.$desc.'")';
					else $sql_query_2 = 'INSERT INTO ne_user_pay (sum,user,status,month,year_of,type,description) VALUES ("'.$k_oplate.'","'.$_SESSION['logged_user'].'","0","0","0","1","'.$desc.'")';
				}
			}
			if($id_purchase&&((!$k_oplate)||(mysql_query($sql_query_2,$link)))){
			$str = 'Ваш заказ успешно занесён в Базу Данных и принят в обработку. В ближайшее время (как правило, накануне доставки) с Вами свяжется менеджер кооператива, чтобы более конкретно обсудить время.';
			
			$subject = 'Оформлен новый заказ на сайте '.$site;
			$message = 'На сайте '.$site.' какой-то нехороший человек сделал новый заказ продуктов на дом.
Пожалуйста, примите необходимые меры для разрешения данной ситуации.
		
Вам не обязательно отвечать на это письмо, т.к. оно сгенерировано роботом. 
Удачи. Всегда Ваш, Робот сайта '.$site;
			send_mime_mail('Робот сайта '.$site,$robot_mail,'Администратору',$adm_mail,'CP1251','KOI8-R',$subject,$message);
			
			unset($_SESSION['basket']);
			setcookie('basket','',time()-3600);
			}
			else $str = 'Произошли ошибки при записи Вашего заказа в Базу Данных. Пожалуйста, повторите процедуру заказа заново. Или свяжитесь с менеджером кооператива по телефону для устного заказа. Приносим свои извинения за доставленные неудобства.';
		echo TrTable(TdReady($str));
		}
		if(is_array($check_list)){
		$n = 0;
		$sql_query = 'INSERT INTO ne_wait_lists VALUES ';
			foreach($check_list as $key => $value){
				foreach($list as $subk => $subv){
					if($subv['id']==$value){
					$sql_query .= '(0,"'.$_SESSION['logged_user'].'","'.$value.'"),';
					$n++;
					break;
					}
				}
			}
		$sql_query = preg_replace('/,$/','',$sql_query);
		$result = @mysql_query($sql_query);
			
			if($n==count($check_list)){
			$str = 'Ваш лист ожидания успешно обновлён. При поступлении выбранных Вами товаров, мы вышлем на Ваш e-mail сообщение.';
			unset($_SESSION['list']);
			setcookie('list','',time()-3600);
			}
			else $str = 'Произошли ошибки при обновлении Вашего листа ожидания. Пожалуйста, повторите попытку позднее. Приносим свои извинения за доставленные неудобства.';
		echo TrTable(TdReady($str));
		}
		if(is_array($info)){
			if($info['remember']){
			
			$_SESSION['info_phone'] = $info['phone'];
			$_SESSION['info_name_1'] = $info['name_1'];
			$_SESSION['info_name_2'] = $info['name_2'];
			$_SESSION['info_name_3'] = $info['name_3'];
			$_SESSION['info_address'] = $info['address'];
			$_SESSION['info_desc'] = $info['desc'];
			
			$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
			setcookie('info_phone', $info['phone'], $end_cookie, '/');
			setcookie('info_name_1', $info['name_1'], $end_cookie, '/');
			setcookie('info_name_2', $info['name_2'], $end_cookie, '/');
			setcookie('info_name_3', $info['name_3'], $end_cookie, '/');
			setcookie('info_address', $info['address'], $end_cookie, '/');
			setcookie('info_desc', $info['desc'], $end_cookie, '/');
			}
/*			if($info['save_in_db']) $sql_query = 'UPDATE customers SET name_2="'.$info['name_1'].'", name_3="'.$info['name_2'].'", name_1="'.$info['name_3'].'", phone_1="'.$info['phone'].'", address="'.$info['address'].'" WHERE uin = "'.$_SESSION['logged_user'].'"';

			if(@mysql_query($sql_query)) $str = 'Ваша контактная информация успешно обновлена.';
			else $str = 'Произошли ошибки при обновлении Вашей контактной информации. Вы можете сделать это самостоятельно в "личном кабинете". Приносим свои извинения за доставленные неудобства.';
		echo TrTable(TdReady($str));*/
		}
	}
?>
    </table></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>