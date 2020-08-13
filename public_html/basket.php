<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

require ('supple/translit.php');

$id = $protection->post['id'];
	if(!$id) $id = $protection->get['id'];
//$name = $protection->post['name'];
$name = translit(str_replace('"','&quot;',$protection->post['name']));
$ed_izmer = $protection->post['ed_izmer'];
$lot = $protection->post['lot'];
$price = $protection->post['price'];

$basket = $_COOKIE['basket'];
	if(!$basket) $basket = $_SESSION['basket'];
$list = $_COOKIE['list'];
	if(!$list) $list = $_SESSION['list'];

	if($id&&$name){
		if($protection->post['add_basket']){
			if ($basket){
//			$items = explode("-",$basket);
			$items = unserialize($basket);
/*			$i = 0;
				foreach($items as $key => $value){
					if($id == $key){
					$i++;
					break;
					}
				}*/
			$k = 0;
				for($i=0;$i<count($items);$i++){
					if($items[$i]['id']==$id){
					$k++;
					break;
					}
				}
				if(!$k){
//				$items[$id] = $name;
				$new_item = array('id'=>$id,'name'=>$name,'ed_izmer'=>$ed_izmer,'lot'=>$lot,'price'=>$price);
				$items[] = $new_item;
//				$basket = implode("-",$items);
				$basket = serialize($items);
				setcookie('basket', $basket, time()+172800, '/');
				$_SESSION['basket'] = $basket;
				}
			}
			else{
//			$items[$id] = $name;
			$new_item = array('id'=>$id,'name'=>$name,'ed_izmer'=>$ed_izmer,'lot'=>$lot,'price'=>$price);
			$items[] = $new_item;
//			$basket = implode("-",$items);
			$basket = serialize($items);
			setcookie('basket', $basket, time()+172800, '/');
			$_SESSION['basket'] = $basket;
			}
		}
		elseif($protection->post['add_list']){
			if ($list){
			$items = unserialize($list);
/*			$i = 0;
				foreach($items as $key => $value){
					if($id == $key){
					$i++;
					break;
					}
				}*/
			$k = 0;
				for($i=0;$i<count($items);$i++){
					if($items[$i]['id']==$id){
					$k++;
					break;
					}
				}
				if(!$k){
//				$items[$id] = $name;
				$new_item = array('id'=>$id,'name'=>$name,'ed_izmer'=>$ed_izmer,'lot'=>$lot,'price'=>$price);
				$items[] = $new_item;
				$list = serialize($items);
				setcookie('list', $list, time()+172800, '/');
				$_SESSION['list'] = $list;
				}
			}
			else{
//			$items[$id] = $name;
			$new_item = array('id'=>$id,'name'=>$name,'ed_izmer'=>$ed_izmer,'lot'=>$lot,'price'=>$price);
			$items[] = $new_item;
			$list = serialize($items);
			setcookie('list', $list, time()+172800, '/');
			$_SESSION['list'] = $list;
			}
		}
	$HTTP_REFERER = eregi_replace('#[0-9]{0,}','',$_SERVER['HTTP_REFERER']);
	header('Location: '.$HTTP_REFERER.'#'.$id);
	}
	elseif($protection->post['go_purchase']){
	$check_basket = $protection->post['check_basket'];
		if(is_array($check_basket)){
			foreach($check_basket as $key => $value){
			$item = explode('|',$value);
			$new_item = array('id'=>$item[0],'name'=>$item[1],'ed_izmer'=>$item[2],'lot'=>$item[3],'price'=>$item[4]);
			$items[] = $new_item;
			}
			if($basket){
			$basket = unserialize($basket);
				foreach($items as $key => $value){
				$k = 0;
					foreach($basket as $subk => $subv){
						if($value['id']==$subv['id']){
						$k++;
						break;
						}
					}
				if(!$k) $basket[] = $value;
				}
			$basket = serialize($basket);
			}
			else $basket = serialize($items);
		setcookie('basket', $basket, time()+172800, '/');
		$_SESSION['basket'] = $basket;
		}
	$check_list = $protection->post['check_list'];
		if(is_array($check_list)){
			foreach($check_list as $key => $value){
			$item = explode('|',$value);
			$new_item = array('id'=>$item[0],'name'=>$item[1],'ed_izmer'=>$item[2],'lot'=>$item[3],'price'=>$item[4]);
			$items_list[] = $new_item;
			}
			if($list){
			$list = unserialize($list);
				foreach($items_list as $key => $value){
				$k = 0;
					foreach($list as $subk => $subv){
						if($value['id']==$subv['id']){
						$k++;
						break;
						}
					}
				if(!$k) $list[] = $value;
				}
			$list = serialize($list);
			}
			else $list = serialize($items_list);
		setcookie('list', $list, time()+172800, '/');
		$_SESSION['list'] = $list;
		}
	header('Location: purchase.php');
	}
	elseif($protection->get['del_from']&&$id&&($basket||$list)){
	$del_from = $protection->get['del_from'];
		if($del_from == 'basket'){
		$items = unserialize($basket);
/*			foreach($items as $key => $value){
				if($id == $key){
				unset($items[$id]);
				break;
				}
			}*/
/*			for($i=0;$i<count($items);$i++){
				if($items[$i]['id']==$id){
				unset($items[$i]);
				break;
				}
			}*/
			foreach($items as $key => $value){
				if($value['id'] == $id){
				unset($items[$key]);
				break;
				}
			}
			if(!count($items)){
			setcookie('basket', '', time()-3600, '/');
			$_SESSION['basket'] = 0;
			}
			else{
			$basket = serialize($items);
			setcookie('basket', $basket, time()+172800, '/');
			$_SESSION['basket'] = $basket;
			}
		}
		elseif($del_from == 'list'){
		$items = unserialize($list);
/*			foreach($items as $key => $value){
				if($id == $key){
				unset($items[$id]);
				break;
				}
			}*/
/*			for($i=0;$i<count($items);$i++){
				if($items[$i]['id']==$id){
				unset($items[$i]);
				break;
				}
			}*/
			foreach($items as $key => $value){
				if($value['id'] == $id){
				unset($items[$key]);
				break;
				}
			}
			if(!count($items)){
			setcookie('list', '', time()-3600, '/');
			$_SESSION['list'] = 0;
			}
			else{
			$list = serialize($items);
			setcookie('list', $list, time()+172800, '/');
			$_SESSION['list'] = $list;
			}
		}
	$HTTP_REFERER = eregi_replace('#[0-9]{0,}','',$_SERVER['HTTP_REFERER']);
	header('Location: '.$HTTP_REFERER);
	}
exit();
?>
<html><head><meta name="robots" content="noindex, nofollow"></head><body></body></html>