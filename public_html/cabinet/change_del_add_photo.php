<?php
session_start();
	if(!isset($_SESSION['logged_user'])){
	header("Location: ../index.php");
	exit();
	}
include ('../supple/server_root.php');

$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
	if(eregi("^$SERVER_ROOT",$HTTP_REFERER)){
	
	require ('../supple/security.php');
	$protection=new security();
	$protection->get_decode();
	$protection->post_decode();
	
	$id = $protection->post['id'];
		if(!$id) $id = $protection->get['id'];
	$fold = $protection->post['fold'];
		if(!$fold) $fold = $protection->get['fold'];
	
		if(($protection->post['upload_img']&&is_uploaded_file($_FILES['img']['tmp_name']))||($protection->post['upload_label']&&is_uploaded_file($_FILES['label']['tmp_name']))){
			if($fold){
			require ('../supple/imgresize.php');
				if(is_uploaded_file($_FILES['img']['tmp_name'])){
				$file = $_FILES['img']['tmp_name'];
				$dir_img = '../'.$img_fold.'/photoes/'.$fold;
				$dir_preview = '../'.$img_fold.'/photoes/'.$fold.'/preview';
				}
				elseif(is_uploaded_file($_FILES['label']['tmp_name'])){
				$file = $_FILES['label']['tmp_name'];
				$dir_img = '../'.$img_fold.'/photoes/'.$fold.'/labels';
				$dir_preview = '../'.$img_fold.'/photoes/'.$fold.'/labels/preview';
				}
			$handle = opendir($dir_img);
			$last_number_file = 0;
				while (($res = readdir($handle))!=FALSE){
					if(ereg("([0-9]{1,})_",$res,$regs)){
					$number_res = $regs[1];
					}
					if (($number_res > $last_number_file)&&(eregi(".jpg$",$res))){
					$last_number_file = $number_res;
					}
				}
			$current_file = $last_number_file + 1;
			closedir($handle);
		
			$file_img = $dir_img.'/'.$current_file.'_'.$_SESSION['logged_user'].'.jpg';
			$file_preview = $dir_preview.'/'.$current_file.'_'.$_SESSION['logged_user'].'.jpg';
		
			$size = getimagesize($file);
//	echo $size[0].' '.$size[1];
				if($size[0]>$size[1]){
				$xw = 600;
				$xh = (600*$size[1])/$size[0];
				$yw = 150;
				$yh = (150*$size[1])/$size[0];
				}
				else{
				$xh = 600;
				$xw = (600*$size[0])/$size[1];
				$yh = 150;
				$yw = (150*$size[0])/$size[1];
				}
			
				if(($size[0]>600)||($size[1]>600)){
					if(!img_resize($file,$file_img,$xw,$xh)) $err = 1;	//Ошибка при изменении размера картинки
				}
				elseif(!copy($file,$file_img)){
				$err = 2;	//Ошибка при копировании фотографии
				}
			
				if((!$err)&&(!img_resize($file,$file_preview,$yw,$yh))){
				$err = 3;	//Ошибка при создании превьюшки
				}
			
			}
			else $err = 6;	//Не выбрана папка для закачки картинок
		header("Location: photo.php?id=$id&fold=$fold&err=$err");
		exit();
		}
		elseif($protection->get['action'] == 'del'){
		$number_file = $protection->get['photo'];

		$img = '../'.$img_fold.'/photoes/'.$fold.'/'.$number_file.'.jpg';
		$preview = '../'.$img_fold.'/photoes/'.$fold.'/preview/'.$number_file.'.jpg';
		
			if ((file_exists($img))&&(!unlink($img))){
			$err = 4;	//Ошибка удаления фотографии
			}
			if ((file_exists($preview))&&(!unlink($preview))){
			$err = 5;	//Ошибка удаления фотографии
			}
		$fold = str_replace('/labels','',$fold);
		header("Location: photo.php?id=$id&fold=$fold&err=$err");
		exit();
		}
		elseif($protection->post['ready']){
		require ('../supple/auth_db.php');
		$main_img = $protection->post['main_img'];
		$dop_img = $protection->post['dop_img'];
		$label = $protection->post['label'];
		$images[0] = $main_img;
			if(is_array($dop_img))
				foreach($dop_img as $value)
					if($value!=$main_img) $images[] = $value;
			if(is_array($images)) $images = implode('-',$images);
		$str_sql_query = "UPDATE items SET
		fold = '$fold',
		images = '$images',
		label_img = '$label'
		WHERE id = '$id'";
		mysql_query($str_sql_query, $link) or die(mysql_error());
		header("Location: products.php");
		exit();
		}
		else{
		header("Location: photo.php?id=$id&fold=$fold&err=$err");
		exit();
		}
	}
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body></body></html>