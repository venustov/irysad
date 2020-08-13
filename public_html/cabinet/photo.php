<?php
$title = 'Добавление [изменение] изображений продукта';
//<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
?>
<?php
include ('templates/header.tpl');
?>
<?php
require ('../supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

require ('../supple/translit.php');
require ('../supple/capslock.php');
include ('../supple/server_root.php');

$id = $protection->get['id'];
$fold = $protection->get['fold'];

	if($protection->post['accept_fold']){
	$fold = $protection->post['fold'];
	$other_fold = $protection->post['other_fold'];
		if($other_fold&&($protection->post['fold']==1)){
		$other_fold = preg_replace('/[^a-z]{1,}/','',capslock(translit(trim($other_fold))));
		$fold = $other_fold;
			if((!eregi("preview",$other_fold))||(!eregi("labels",$other_fold))){
				if(!file_exists('../'.$img_fold.'/photoes/'.$fold)) mkdir('../'.$img_fold.'/photoes/'.$fold);
				if(!file_exists('../'.$img_fold.'/photoes/'.$fold.'/labels')) mkdir('../'.$img_fold.'/photoes/'.$fold.'/labels');
				if(!file_exists('../'.$img_fold.'/photoes/'.$fold.'/labels/preview')) mkdir('../'.$img_fold.'/photoes/'.$fold.'/labels/preview');
				if(!file_exists('../'.$img_fold.'/photoes/'.$fold.'/preview')) mkdir('../'.$img_fold.'/photoes/'.$fold.'/preview');
			}
		}
		elseif($protection->post['fold']!=1) $fold = $protection->post['fold'];
	}

require ('../supple/auth_db.php');
$str_sql_query = "SELECT name, fold, images, label_img FROM items WHERE id = '$id'";
$result = mysql_query($str_sql_query);
$i = 0;
$name = mysql_result($result,$i,"name");
	if(!$fold) $fold = mysql_result($result,$i,"fold");
$images = mysql_result($result,$i,"images");
$label = mysql_result($result,$i,"label_img");

$dir = '../'.$img_fold.'/photoes/';
$handle = opendir($dir);
	while (($res = readdir($handle))!==FALSE){
//		if(($res!='.')&&(($res!='..'))&&(($res!='no_photo.jpg'))) $fold_arr[] = $res;
		if(eregi("^[a-z]{1,}$",$res)&&(!eregi("preview$",$res))&&(!eregi("labels$",$res))) $fold_arr[] = $res;
	}
	if($fold_arr) sort($fold_arr);
closedir($handle);

	if($fold){
	$dir = '../'.$img_fold.'/photoes/'.$fold.'/';
	$handle = opendir($dir);
		while (($res = readdir($handle))!==FALSE){
			if(eregi(".jpg$",$res)) $img_arr[] = substr($res,0,-4);
		}
	closedir($handle);

	$dir = '../'.$img_fold.'/photoes/'.$fold.'/labels/';
	$handle = opendir($dir);
		while (($res = readdir($handle))!==FALSE){
			if(eregi(".jpg$",$res)) $labels_arr[] = substr($res,0,-4);
		}
	closedir($handle);
	}

	if($fold&&$img_arr){
	echo '<script language="JavaScript"> 
<!--//
	';
		foreach($img_arr as $value){
		echo 'frmname.s'.$value.'.disabled=false;
	';
		}
	echo '
function setphoto(obj)
{
frmname = document.upload;
';
		foreach($img_arr as $value){
		echo '
if(frmname.u'.$value.'.checked){
	frmname.s'.$value.'.disabled=true;
}
else{
	frmname.s'.$value.'.disabled=false;
}
';
		}
	echo '
}
//-->
</script>

';
	}
function TdPhoto($id,$img_fold,$fold,$img,$view){
$user = $_SESSION['logged_user'];
	if(preg_match('/_'.$user.'$/',$img)){
	$str = '<a href="change_del_add_photo.php?id='.$id.'&fold='.$fold.'&photo='.$img.'&action=del" class="del" title="Удалить">удалить(Х)</a>
';
	}
$image = '../'.$img_fold.'/photoes/'.$fold.'/'.$img.'.jpg';
$size_img = getimagesize($image);
$preview = '../'.$img_fold.'/photoes/'.$fold.'/preview/'.$img.'.jpg';
$size_prev = getimagesize($preview);
$str .= '
		  <a href="../photo.php?fold='.$fold.'&photo='.$img.'" target="_blank" alt="Увеличить" title="Увеличить" onClick="popupWin = window.open(this.href, \'main_photo\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,left=339,top=140,width='.$size_img[0].',height='.$size_img[1].'\'); popupWin.focus(); return false;"><img src="'.$preview.'" width='.$size_prev[0].' height='.$size_prev[1].' border=0></a><br>
		  <input onclick="setphoto(this);" name="main_img" type="radio" value="'.$img.'" id="u'.$img.'"';
	if($view=='main') $str .= ' checked';
$str .= '>основная<br>
		  <input';
	if($view=='main') $str .= ' disabled="true"';
$str .= ' name="dop_img[]" type="checkbox" value="'.$img.'" id="s'.$img.'"';
	if($view=='secondary') $str .= ' checked';
$str .= '>дополнительная';
return $str;
}

function TdLabel($id,$img_fold,$fold,$img,$check){
$user = $_SESSION['logged_user'];
	if(preg_match('/_'.$user.'$/',$img)){
	$str = '<a href="change_del_add_photo.php?id='.$id.'&fold='.$fold.'/labels&photo='.$img.'&action=del" class="del" title="Удалить">удалить(Х)</a>
';
	}
$image = '../'.$img_fold.'/photoes/'.$fold.'/labels/'.$img.'.jpg';
$size_img = getimagesize($image);
$preview = '../'.$img_fold.'/photoes/'.$fold.'/labels/preview/'.$img.'.jpg';
$size_prev = getimagesize($preview);
$str .= '
		  <a href="../photo.php?fold='.$fold.'/labels&photo='.$img.'" target="_blank" alt="Увеличить" title="Увеличить" onClick="popupWin = window.open(this.href, \'main_photo\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,left=339,top=140,width='.$size_img[0].',height='.$size_img[1].'\'); popupWin.focus(); return false;"><img src="'.$preview.'" width='.$size_prev[0].' height='.$size_prev[1].' border=0></a><br>
		  <input name="label" type="radio" value="'.$img.'"';
	if($check) $str .= ' checked';
$str .= '>';
return $str;
}
?>
<script language="JavaScript"> 
<!--//
function setfield(frmname){
var mt=frmname.other_fold;
var ct=frmname.fold;
if (ct.value==1){
mt.disabled=false;
}
else{
mt.disabled=true; 
}
}
//-->
</script>
<div class="hdr1">
  <div class="hdr2" style="background-position:0 0">Продукт: <?php if($name) echo $name; ?>:</div>
</div>
<table width="100%" border="0" cellpadding="10" cellspacing="0" id="err">
<?php
	if($protection->get['err']){
	echo '<tr>
    <td colspan="2" valign="top"><b>Ошибка закачки #'.$protection->get['err'].': ';
		if($protection->get['err'] == 1){
		echo 'Ошибка при изменении размера картинки';
		}
		elseif($protection->get['err'] == 2){
		echo 'Ошибка при копировании фотографии';
		}
		elseif($protection->get['err'] == 3){
		echo 'Ошибка при создании превьюшки';
		}
		elseif($protection->get['err'] == 4){
		echo 'Ошибка удаления фотографии';
		}
		elseif($protection->get['err'] == 5){
		echo 'Ошибка удаления фотографии';
		}
		elseif($protection->get['err'] == 6){
		echo 'Не выбрана папка для закачки картинок';
		}
/*		elseif($protection->get['err'] == 7){
		echo 'Ошибка удаления фотографии';
		}
		elseif($protection->get['err'] == 8){
		echo 'Ошибка удаления фотографии';
		}*/
	echo '</b></td>
  </tr>';
	}
?>
  <form name="form" method="post" action="<?php if($id) echo '?id='.$id; ?>"><tr bgcolor="#f0f0f0">
    <td style="border-bottom: 2px solid #000000;"><b>(*)</b>Папка для закачки фото:</td>
    <td style="border-bottom: 2px solid #000000;"><select onchange="javascript:setfield(document.form);" name="fold">
<?php
	if(is_array($fold_arr)){
		for($i=0; $i<count($fold_arr); $i++){
		echo '<option value="'.$fold_arr[$i].'"';
			if($fold_arr[$i]==$fold){
			echo ' selected';
			}
		echo '>'.$fold_arr[$i].'</option>';
		}
	}
?>
        <option value="0"<?php if(!$fold) echo ' selected'; ?>>Не выбрана</option>
        <option value="1">Другая [указать]</option>
      </select>
    другая (латиницей):
    <input disabled="true" name="other_fold" type="text" id="other_fold" size="27" maxlength="36">
    <input name="accept_fold" type="submit" id="accept_fold" value="Применить"></td>
  </tr></form>
  <form name="upload" enctype="multipart/form-data" method="post" action="change_del_add_photo.php"><tr bgcolor="#fbfbfb">
    <td colspan="2" valign="top">Фотографии для основной и дополнительных картинок продукта:</td>
    </tr>
<?php
	if($img_arr){
	$images = explode('-',$images);
	echo '
  <tr bgcolor="#fbfbfb">
    <td colspan="2"><table width="100%"  border="0" cellspacing="10" cellpadding="5" bgcolor="#FFFFFF" style="border-top:1px solid #CCCCCC; border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; border-bottom:1px solid #CCCCCC;">
        ';
	$i = 0;
	$m = 5;	//	Количество столбцов
		while($i < count($img_arr)){
		$j = 1;
			while($j <= $m && $i < count($img_arr)){
				if(($images[0]==$img_arr[$i])||(count($img_arr)==1)){
				$view = 'main';
				$border = '#FF0000';
				}
				else{
					for($k = 1; $k < count($images); $k++){
						if($images[$k]==$img_arr[$i]){
						$view = 'secondary';
						$border = '#0000FF';
						}
					}
				}
				
				if(!$border) $border = '#CCCCCC';
				if($j == 1) echo '<tr>
          ';
			echo '<td width="20%" align="left" valign="bottom" style="border-top:1px solid '.$border.'; border-left:1px solid '.$border.'; border-right:1px solid '.$border.'; border-bottom:1px solid '.$border.';">
		  ';
			echo TdPhoto($id,$img_fold,$fold,$img_arr[$i],$view);
			echo '</td>
          ';
			$j++;
				if($j == ($m+1)) echo '
        </tr>
';
			$view = '';
			$border = '#CCCCCC';
			$i++;
			}
		}
		if($j<6&&$j>1){
		echo '<td width="20%">&nbsp;</td>';
			if($j==2) echo '<td width="20%">&nbsp;</td><td width="20%">&nbsp;</td><td width="20%">&nbsp;</td>';
			elseif($j==3) echo '<td width="20%">&nbsp;</td><td width="20%">&nbsp;</td>';
			elseif($j==4) echo '<td width="20%">&nbsp;</td>';
		echo '
        </tr>
';
		}
	echo '
      </table>
    </td>    
  </tr>
';
	}
?>
  <tr bgcolor="#fbfbfb">
    <td width="25%" style="border-bottom: 2px solid #000000;">Загрузить свои фото: </td>
    <td width="75%" style="border-bottom: 2px solid #000000;"><input name="img" type="file" id="img" size="30">
       <input name="upload_img" type="submit" id="upload_img" value="Загрузить"></td>
  </tr>
  <tr bgcolor="#f0f0f0">
    <td colspan="2" valign="top">Выбрать наклейку продукта:</td>
  </tr>
  <tr bgcolor="#f0f0f0">
    <td colspan="2">
				
<?php
echo '
	  <table width="100%"  border="0" cellspacing="10" cellpadding="5" bgcolor="#FFFFFF" style="border-top:1px solid #CCCCCC; border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC; border-bottom:1px solid #CCCCCC;">
          <tr>
            <td width="20%" align="center" valign="bottom" style="border-top:1px solid #000000; border-left:1px solid #000000; border-right:1px solid #000000; border-bottom:1px solid #000000;"><img src="../images/photoes/no_label.jpg" width=150 height=50 border=0><br>
                <input name="label" type="radio" value="0"';
	if(!$labels_arr) echo ' checked';
	else{
	$n = 0;
		foreach($labels_arr as $value){
//			if(preg_match('/^'.$label.'_/',$value)) $n++;
			if($label==$value) $n++;
		}
		if(!$n) echo ' checked';
	}
echo '></td>';
	if($labels_arr){
	$i = 0;
	$m = 5;	//	Количество столбцов
		while($i < count($labels_arr)){
		$j = 2;
			while($j <= $m && $i < count($labels_arr)){
//				if(preg_match('/^'.$label.'_/',$labels_arr[$i])){
				if($label==$labels_arr[$i]){
				$check = 1;
				$border = '#FF0000';
				}
				else{
				$check = 0;
				$border = '#CCCCCC';
				}
				
				if($j == 1) echo '<tr>
          ';
			echo '
            <td width="20%" align="center" valign="bottom" style="border-top:1px solid '.$border.'; border-left:1px solid '.$border.'; border-right:1px solid '.$border.'; border-bottom:1px solid '.$border.';">';
			echo TdLabel($id,$img_fold,$fold,$labels_arr[$i],$check);
			echo '</td>
            ';
			$j++;
				if($j == ($m+1)) echo '
        </tr>
';
			$check = 0;
			$border = '#CCCCCC';
			$i++;
			}
		}
		if($j<6&&$j>1){
		echo '<td width="20%">&nbsp;</td>';
			if($j==2) echo '<td width="20%">&nbsp;</td><td width="20%">&nbsp;</td><td width="20%">&nbsp;</td>';
			elseif($j==3) echo '<td width="20%">&nbsp;</td><td width="20%">&nbsp;</td>';
			elseif($j==4) echo '<td width="20%">&nbsp;</td>';
		echo '
        </tr>
';
		}
	}
	else echo '<td width="20%">&nbsp;</td><td width="20%">&nbsp;</td><td width="20%">&nbsp;</td><td width="20%">&nbsp;</td>
          </tr>';
echo '
      </table>
';
?>
	</td>
  </tr>
  <tr bgcolor="#f0f0f0">
    <td style="border-bottom: 2px solid #000000;">Загрузить другую наклейку: </td>
    <td style="border-bottom: 2px solid #000000;"><input name="label" type="file" id="label" size="30">
        <input name="upload_label" type="submit" id="upload_label" value="Загрузить">
    </td>
  </tr>
  <tr>
    <td width="25%" valign="top"><input name="fold" type="hidden" value="<?php echo $fold; ?>"><input name="id" type="hidden" value="<?php echo $id; ?>"></td>
    <td width="75%" valign="top"><input type="submit" name="ready" value="Сохранить изменения"></td>
  </tr></form>
</table>
<?php
include ('templates/footer.tpl');
?>