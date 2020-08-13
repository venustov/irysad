<?php
//	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
require ('security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

$LMI_PREREQUEST = $protection->post['LMI_PREREQUEST'];			//флаг предварительного запроса
$LMI_PAYEE_PURSE = $protection->post['LMI_PAYEE_PURSE'];		//мой кошелек-
$LMI_PAYMENT_AMOUNT = $protection->post['LMI_PAYMENT_AMOUNT'];	//сумма платежа+							sum
$LMI_PAYMENT_NO = $protection->post['LMI_PAYMENT_NO'];			//номер платежа у меня+						
$LMI_MODE = $protection->post['LMI_MODE'];						//режим платежа-
$LMI_SYS_INVS_NO = $protection->post['LMI_SYS_INVS_NO'];		//Номер счета в системе WebMoney+			wm_order_one
$LMI_SYS_TRANS_NO = $protection->post['LMI_SYS_TRANS_NO'];		//Номер платежа в системе WebMoney			wm_order_two
$LMI_SYS_TRANS_DATE = $protection->post['LMI_SYS_TRANS_DATE'];	//дата прохождения платежа+-				date
$LMI_SECRET_KEY = 'microclimat';
$LMI_PAYER_PURSE = $protection->post['LMI_PAYER_PURSE'];		//кошелек покупателя+						purse_payer
$LMI_PAYER_WM = $protection->post['LMI_PAYER_WM'];				//WM ID покупателя+							wmid_payer
$LMI_HASH = $protection->post['LMI_HASH'];
$hash_our = strtoupper(md5($LMI_PAYEE_PURSE.$LMI_PAYMENT_AMOUNT.$LMI_PAYMENT_NO.$LMI_MODE.$LMI_SYS_INVS_NO.$LMI_SYS_TRANS_NO.$LMI_SYS_TRANS_DATE.$LMI_SECRET_KEY.$LMI_PAYER_PURSE.$LMI_PAYER_WM));
$user = $protection->post['user'];
$product = $protection->post['product'];

include ('server_root.php');
require ('auth_db.php');
//include ('mail.php');
	if($product=='shares'){
	$quant=floor($LMI_PAYMENT_AMOUNT/$nominal_share);
	$sum=$nominal_share*$quant;
		if($LMI_PREREQUEST==1){
		$sql_query = 'INSERT INTO ne_user_pay VALUES (0,0,0,"'.$LMI_PAYMENT_AMOUNT.'","'.$user.'","0",0,0,"5","Пополнение счета"), (0,0,0,"'.$sum.'","'.$user.'","0",0,0,"2","Покупка акций кооператива")';
			if(@mysql_query($sql_query,$link)) echo 'YES';
			else echo 'NO';
		}
		elseif($LMI_HASH==$hash_our){
		$sql_query = 'UPDATE ne_user_pay SET
		wm_no = "'.$LMI_SYS_INVS_NO.'",
		status = "1"
		WHERE wm_no="0"
		AND (sum="'.$LMI_PAYMENT_AMOUNT.'" OR sum="'.$sum.'")
		AND user="'.$user.'"
		AND status="0"
		AND (type="5" OR type="2")
		LIMIT 2';
		@mysql_query($sql_query,$link);
		$sql_query = 'SELECT id FROM ne_user_pay WHERE wm_no="'.$LMI_SYS_INVS_NO.'" AND type="2"';
		$result = @mysql_query($sql_query,$link);
		$id_pay = mysql_result($result,0,"id");
		$sql_query = 'INSERT INTO ne_shares VALUES ';
			for($i=1;$i<$quant;$i++) $sql_query .= '(0,'.$nominal_share.','.$user.','.$id_pay.'),';
		$sql_query .= '(0,'.$nominal_share.','.$user.','.$id_pay.')';
			if(@mysql_query($sql_query,$link)){
			$sql_query = 'DELETE FROM ne_shares WHERE owner="0" LIMIT '.$quant;
			@mysql_query($sql_query,$link);
			}
		}
	}
?>