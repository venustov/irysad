<?php
//	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('../supple/auth_db.php');
class Purchase extends FPDF{
	function PrintTitle($title,$image,$company){
	$this->Image($image,6,6,40,20);
	$this->SetFont('ArialMT','',20);
	$this->Cell(210,4,$company,0,0,'C');
	$this->Ln();
	$this->Ln();
	$this->Cell(37);
	$this->SetFillColor(209,204,244);
	$this->SetFont('ArialMT','',10);
	$this->Cell(150,8,$title,0,0,'C',1);
	$this->Ln();
	$this->Ln();
	}
	function LoadData($city,$check){
		if(is_array($check)){
		$query_insert = ' AND (p.id="'.$check[0].'"';
			foreach($check as $key=>$value) if($key!=0) $query_insert .= ' OR p.id="'.$value.'"';
		$query_insert .= ')';
		}
	$sql_query = 'SELECT b.id_purchase, b.id_product, b.quantity, i.id, i.name, i.city, i.price_1, i.price_2, i.ed_izmer, i.lot, p.id, p.status FROM ne_baskets AS b, items AS i, ne_purchases AS p WHERE b.id_purchase=p.id AND b.id_product=i.id AND i.city="'.$city.'" AND p.status<"2"'.$query_insert.' ORDER BY i.name ASC, i.id ASC';
	$result = @mysql_query($sql_query);
	$number = @mysql_num_rows($result);
	$i = 0;
	$id_pr = mysql_result($result,$i,"b.id_product");
	$quantity = 0;
	$q_zak = 0;
		while($i<=$number){
		$id = mysql_result($result,$i,"b.id_product");

			if($id_pr==$id){
			$quantity += mysql_result($result,$i,"b.quantity");
//			$q_zak++;
			$i++;
			}
			else{
			$j = $i - 1;
			$id_pr = mysql_result($result,$j,"b.id_product");
			$name = str_replace('&quot;','"',mysql_result($result,$j,"i.name"));
			$ed_izmer = mysql_result($result,$j,"i.ed_izmer");
			$lot = mysql_result($result,$j,"i.lot");
			$zakup = mysql_result($result,$j,"i.price_1");
			$rozn = mysql_result($result,$j,"i.price_2");
		
				if($rozn&&($rozn<$zakup)){
				$price = $rozn;
				$prim = 'УЦЕНКА';
				}
				else{
				$price = $zakup;
				$prim = '-';
				}
		
				if($ed_izmer == 1) $ed_izmer = 'кг';
				elseif($ed_izmer == 2) $ed_izmer = '100 гр';
				elseif($ed_izmer == 3) $ed_izmer = 'уп.';
				elseif($ed_izmer == 4) $ed_izmer = 'шт.';
				else $ed_izmer = 0;
				
			$new_item = array('id'=>$id_pr,'name'=>$name,'quantity'=>$quantity,'ed_izmer'=>$ed_izmer,'lot'=>$lot,'prim'=>$prim,'price'=>$price);
			$list_zakup[] = $new_item;
//			echo 'ID: '.$id_pr.' NAME: '.$name.' Заказ: '.$id_purchase.' Количество: '.$quantity.' ('.$ed_izmer.') Статус: '.$status.'<br>';
			$id_pr = $id;
			$quantity = 0;
//			$q_zak = 0;
			}
		}
	return $list_zakup;
	}
	function LoadAddress($check){
		if(is_array($check)){
		$query_insert = ' id="'.$check[0].'"';
			foreach($check as $key=>$value) if($key!=0) $query_insert .= ' OR id="'.$value.'"';
		}
	$sql_query = 'SELECT id,phone,name_1,name_2,name_3,address,description FROM ne_purchases WHERE'.$query_insert.' ORDER BY id DESC';
	$result = @mysql_query($sql_query);
	$number = @mysql_num_rows($result);
		if($number){
		$i=0;
			while($i<$number){
			$id = mysql_result($result,$i,"id");
			$phone = mysql_result($result,$i,"phone");
			$name_1 = mysql_result($result,$i,"name_1");
			$name_2 = mysql_result($result,$i,"name_2");
			$name_3 = mysql_result($result,$i,"name_3");
			$address = mysql_result($result,$i,"address");
			$desc = mysql_result($result,$i,"description");
			
			$new_address = array('id'=>$id,'phone'=>$phone,'name_1'=>$name_1,'name_2'=>$name_2,'name_3'=>$name_3,'address'=>$address,'desc'=>$desc);
			$users_address[] = $new_address;
			$i++;
			}
		}
	return $users_address;
	}
	function LoadPayUser($check){
		if(is_array($check)){
		$query_insert = ' AND (p.id="'.$check[0].'"';
			foreach($check as $key=>$value) if($key!=0) $query_insert .= ' OR p.id="'.$value.'"';
		$query_insert .= ')';
		}
	$sql_query = 'SELECT u.id, u.sum, u.user FROM ne_user_pay AS u, ne_purchases AS p WHERE u.status="0" AND u.user=p.user AND u.type="1"'.$query_insert.' GROUP BY u.user ORDER BY u.id DESC';
	$result = @mysql_query($sql_query);
	$number = @mysql_num_rows($result);
		if($number){
		$i=0;
			while($i<$number){
			$id = mysql_result($result,$i,"u.id");
			$user = mysql_result($result,$i,"u.user");
			$sum = mysql_result($result,$i,"u.sum");
			$new_pay = array('id'=>$id,'user'=>$user,'sum'=>$sum);
			$users_pay[] = $new_pay;
			$i++;
			}
		}
	return $users_pay;
	}
	function TablePayUser($header,$users_pay){
	$this->SetFont('ArialMT','',8);
    $w=array(37,37,39,37,37);
    for($i=0;$i<count($header);$i++) $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    $this->SetFont('TimesNewRomanPSMT','',8);
    	foreach($users_pay as $row){
	
		$this->Cell($w[0],6,number_format($row['id']),'LRBT',0,'R');
		$this->Cell($w[1],6,$row['user'],'LRBT',0,'C');
		$this->Cell($w[2],6,$row['sum'],'LRBT',0,'C');
		$this->Cell($w[3],6,'','LRBT');
		$this->Cell($w[4],6,'','LRBT');
        
		$this->Ln();
    	}
    $this->Cell(array_sum($w),0,'','T');
	}
	function ImprovedTable($header,$list_zakup){
    //Указываем ширину столбцов
    $w=array(8,72,30,30,27,20);
    
    //Выводим заголовки столбцов
    for($i=0;$i<count($header);$i++) $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    
    //Выводим данные
    //Сначала установим шрифт для данных
    $this->SetFont('TimesNewRomanPSMT','',8);
	$sum = 0;
    	foreach($list_zakup as $row){
	
		/*Первый параметр Cell() - ширина столбца, указанная ранее в массиве $w, второй параметр - высота столбца, третий параметр - строка для вывода, LRBT - означает прорисовку границ со всех сторон ячейки (Left, Right, Bottom, Top). Можно также указать выравнивание в ячейке по правому краю ('R') */
		$this->Cell($w[0],6,number_format($row['id']),'LRBT',0,'R');
		$this->Cell($w[1],6,$row['name'],'LRBT');
		$this->Cell($w[2],6,$row['quantity'].' x '.$row['lot'].' '.$row['ed_izmer'],'LRBT',0,'C');
		$this->Cell($w[3],6,$row['price'].' / ___','LRBT',0,'C');
		$this->Cell($w[4],6,'','LRBT');
		$this->Cell($w[5],6,$row['prim'],'LRBT');
		$sum += $row['price']*$row['quantity']*$row['lot'];
        
		//Переходим на следующую строку
		$this->Ln();
    	}
    //Closure line
    $this->Cell(array_sum($w),0,'','T');
	$this->Ln();
	$this->Cell(110,8,'Приблизительная сумма закупки: ',0,0,'L');
	$this->Cell(87,8,$sum,0,0,'L');
	}
	function TablePurchase($header,$purchase){
    $w=array(7,95,15,15,15,20,20);
	$this->SetFont('ArialMT','',6);
	
    	for($i=0;$i<count($header);$i++) $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();

    $this->SetFont('TimesNewRomanPSMT','',8);
    	foreach($purchase as $row){
	
		$this->Cell($w[0],6,number_format($row['id']),'LRBT',0,'R');
		$this->Cell($w[1],6,$row['name'],'LRBT');
		$this->Cell($w[2],6,$row['quantity'],'LRBT',0,'C');
		$this->Cell($w[3],6,$row['real_price'],'LRBT');
		$this->Cell($w[4],6,$row['real_quantity'],'LRBT');
		$this->Cell($w[5],6,$row['sum'],'LRBT');
		$this->Cell($w[6],6,$row['prim'],'LRBT');
        
		$this->Ln();
    	}
    $this->Cell(array_sum($w),0,'','T');
	}
	function TableHead($id,$uin,$id_pay){
    $w=array(20,40,20,40,20,47);
	$this->SetDrawColor(129,129,129);
	$this->SetFont('ArialMT','',6);
//	$this->SetFillColor(209,204,244);
	$this->Cell($w[0],12,'Заказ №','LRBT');
	$this->SetFont('ArialMT','',18);
	$this->Cell($w[1],12,number_format($id),'LRBT',0,'L');
	$this->SetFont('ArialMT','',6);
	$this->Cell($w[2],12,'UIN:','LRBT');
	$this->SetFont('ArialMT','',18);
	$this->Cell($w[3],12,$uin,'LRBT',0,'L');
	$this->SetFont('ArialMT','',6);
	$this->Cell($w[4],12,'Pay ID:','LRBT');
	$this->SetFont('ArialMT','',18);
	$this->Cell($w[5],12,$id_pay,'LRBT',0,'L');
	
    $this->Ln();
    $this->Cell(array_sum($w),0,'','T');
	}
	function InAddition($phone,$name_1,$name_2,$name_3,$address,$desc,$time){
	$this->SetFont('ArialMT','',8);
	$this->Cell(40,8,'Тел: ',0,0,'L');
	$this->Cell(160,8,$phone,0,0,'L');
	$this->Ln();
	$this->Cell(40,8,'Имя: ',0,0,'L');
	$this->Cell(25,8,$name_1,0,0,'L');
		if($name_2) $this->Cell(25,8,$name_2,0,0,'L');
		if($name_3) $this->Cell(25,8,$name_3,0,0,'L');
		$this->Ln();
		$this->Cell(40,8,'Адрес: ',0,0,'L');
		$this->Cell(167,8,$address,0,0,'L');
		if($desc){
		$this->Ln();
//		$this->Cell(20,8,'Дополнительно: ',0,0,'L');
		$this->MultiCell(187,8,$desc);
		}
		if($time!=1){
		$this->Ln();
		$this->Cell(40,8,'Доставить: ',0,0,'L');
		$this->Cell(147,8,$time,0,0,'L');
		}
	}
	function Footer(){
	//Позиционирование в  1.5 см от нижней границы
	$this->SetY(-15);
	//TimesNewRomanPSMT italic 8
	$this->SetFont('TimesNewRomanPSMT','',8);
	//Номер страницы
	$this->Cell(0,10,'Страница '.$this->PageNo().'/{nb}',0,0,'C');
	}
	function TablesPuschases($city,$check){
		if(is_array($check)){
		$query_insert = ' AND (p.id="'.$check[0].'"';
			foreach($check as $key=>$value) if($key!=0) $query_insert .= ' OR p.id="'.$value.'"';
		$query_insert .= ')';
		}
//	$sql_query = 'SELECT b.id_purchase, b.id_product, b.quantity, i.id, i.name, i.city, i.price_1, i.price_2, i.ed_izmer, p.id, p.user, p.phone, p.name_1, p.name_2, p.name_3, p.address, p.description, p.time_of, p.status FROM ne_baskets AS b, items AS i, ne_purchases AS p WHERE b.id_purchase=p.id AND b.id_product=i.id AND i.city="'.$city.'" AND p.status="0" ORDER BY p.id ASC, i.name ASC';
//	$sql_query = 'SELECT b.id_purchase, b.id_product, b.quantity, i.id, i.name, i.city, i.price_1, i.price_2, i.ed_izmer, p.id, p.user, p.phone, p.name_1, p.name_2, p.name_3, p.address, p.description, p.time_of, p.status, u.id, u.sum, u.status FROM ne_baskets AS b, items AS i, ne_purchases AS p, ne_user_pay AS u WHERE b.id_purchase=p.id AND b.id_product=i.id AND i.city="'.$city.'" AND p.status<"2" AND p.user=u.user AND u.type="1" AND MONTH(u.date_pay)=MONTH(p.date) AND YEAR(u.date_pay)=YEAR(p.date)'.$query_insert.' ORDER BY p.id ASC, i.name ASC';
//	$sql_query = 'SELECT b.id_purchase, b.id_product, b.quantity, i.id, i.name, i.city, i.price_1, i.price_2, i.ed_izmer, p.id, p.user, p.phone, p.name_1, p.name_2, p.name_3, p.address, p.description, p.time_of, p.status, u.id, u.sum, u.status FROM ne_baskets AS b, items AS i, ne_purchases AS p, ne_user_pay AS u WHERE b.id_purchase=p.id AND b.id_product=i.id AND i.city="'.$city.'" AND p.status<"2" AND p.user=u.user AND u.type="1"'.$query_insert.' AND u.status="0" ORDER BY p.id ASC, i.name ASC';
	$sql_query = 'SELECT b.id_purchase, b.id_product, b.quantity, i.id, i.name, i.city, i.price_1, i.price_2, i.ed_izmer, p.id, p.user, p.phone, p.name_1, p.name_2, p.name_3, p.address, p.description, p.time_of, p.status, c.status FROM ne_baskets AS b, items AS i, ne_purchases AS p, customers AS c WHERE b.id_purchase=p.id AND b.id_product=i.id AND i.city="'.$city.'" AND p.status<"2" AND p.user=c.uin'.$query_insert.' ORDER BY p.id ASC, i.name ASC';
	$result = @mysql_query($sql_query);
	$number = @mysql_num_rows($result);
		if($number){
		$i = 0;
		$id_pur = mysql_result($result,$i,"b.id_purchase");
			while($i<=$number){
			$id = mysql_result($result,$i,"b.id_purchase");

				if($id_pur==$id){
				$id_pr = mysql_result($result,$i,"b.id_product");
				$name = str_replace('&quot;','"',mysql_result($result,$i,"i.name"));
				$ed_izmer = mysql_result($result,$i,"i.ed_izmer");
				$zakup = mysql_result($result,$i,"i.price_1");
				$rozn = mysql_result($result,$i,"i.price_2");
				$quantity = mysql_result($result,$i,"b.quantity");
				
				$id_user = mysql_result($result,$i,"p.user");
				$phone = mysql_result($result,$i,"p.phone");
				$name_1 = mysql_result($result,$i,"p.name_1");
				$name_2 = mysql_result($result,$i,"p.name_2");
				$name_3 = mysql_result($result,$i,"p.name_3");
				$address = mysql_result($result,$i,"p.address");
				$desc = mysql_result($result,$i,"p.description");
				$time = mysql_result($result,$i,"p.time_of");
				
				$user_status = mysql_result($result,$i,"c.status");
					if($user_status){
					$sql_query = 'SELECT u.id,u.status,u.sum FROM ne_user_pay AS u,ne_purchases AS p WHERE u.user='.$id_user.' AND u.type="1" AND u.status="0"';
					$result_1 = @mysql_query($sql_query);
					
					$id_pay = mysql_result($result_1,0,"u.id");
					$status = mysql_result($result_1,0,"u.status");
					$sum_pay = mysql_result($result_1,0,"u.sum");
					}
					else $id_pay = 'КЛИЕНТ';
				
					if($ed_izmer == 1) $ed_izmer = 'кг';
					elseif($ed_izmer == 2) $ed_izmer = '100 гр';
					elseif($ed_izmer == 3) $ed_izmer = 'уп.';
					elseif($ed_izmer == 4) $ed_izmer = 'шт.';
					else $ed_izmer = 0;
					
					if($time==2) $time = 'Утром';
					elseif($time==3) $time = 'Днем';
					elseif($time==4) $time = 'Вечером';
					elseif($time==5) $time = 'Ночью';
				
					if($rozn&&($rozn<$zakup)){
					$price = $rozn;
					$prim = 'УЦ.';
					}
					else{
					$price = $zakup;
					$prim = '';
					}
					
				$new_item = array('id'=>$id_pr,'name'=>$name,'price'=>$price,'quantity'=>$quantity.' '.$ed_izmer,'real_price'=>'','real_quantity'=>'','sum'=>'','prim'=>$prim);
				$purchase[] = $new_item;
				$i++;
				}
				else{
				$j=0;
					while($j<2){
					$this->AddPage();
					$this->TableHead($id_pur,$id_user,$id_pay);
					$this->Ln();
					$this->InAddition($phone,$name_1,$name_2,$name_3,$address,$desc,$time);
					$this->Ln();
					$header = array("ID","Наименование продукта","Кол-во","Цена","Кол-во","Сумма (р)","Прим.");
					$this->TablePurchase($header,$purchase);
					$this->Ln();
					$this->SetFont('ArialMT','',8);
					$this->Cell(147,8,'Итого: ',0,0,'R');
					$this->Ln();
/*				
						if((!$status)&&$user_status&&$sum_pay){
						$this->Cell(147,8,'Членский взнос: ',0,0,'R');
						$this->SetFont('ArialMT','',18);
						$this->Cell(15,8,$sum_pay,0,0,'C');
						$this->Ln();
						}
*/					
					$this->SetFont('ArialMT','',8);
					$this->Cell(147,8,'Всего к оплате: ',0,0,'R');
					$this->Ln();
    				$this->Cell(187,0,'','T');
					$this->Ln();
					$this->Cell(40,8,'Дата оплаты: ',0,0,'R');
					$this->Cell(20,8,'','B');
					$this->Ln();
					$this->Cell(40,8,'Клиент: ',0,0,'R');
					$this->Cell(20,8,'','B');
					$this->Ln();
					$this->Cell(40,8,'Менеджер: ',0,0,'R');
					$this->Cell(20);
					$this->Cell(25,8,'/',0,0,'L');
					$this->Cell(10,8,'/',0,0,'L');
					$this->Ln();
					$this->Cell(40);
					$this->Cell(45,8,'','T');
					$j++;
					}
				unset($purchase);
				$id_pur = $id;
				}
			}
		}
	}
	function ListAddress($data){
	$this->SetFont('ArialMT','',16);
    	foreach($data as $row){
		$this->MultiCell(187,8,$row['id'].'; '.$row['phone'].'; '.$row['name_1'].' '.$row['name_2'].' '.$row['name_3'].'; '.$row['address'].'; '.$row['desc']);
		$this->Ln();
		}
	}
	function UpdateStatus($check,$admin){
		if(is_array($check)){
		$query_insert = ' AND (id="'.$check[0].'"';
			foreach($check as $key=>$value) if($key!=0) $query_insert .= ' OR id="'.$value.'"';
		$query_insert .= ')';
		}
	$sql_query = 'UPDATE ne_purchases SET status="1", admin="'.$admin.'" WHERE status<"2"'.$query_insert;
		if(mysql_query($sql_query)) return true;
		else return false;
	}
}  
?>