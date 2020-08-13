<?php
$code = (eregi("^[0-9]+$",$_GET['code']))? $_GET['code']:"--";
$code_confirm = hexdec(md5(md5($code.$_SERVER['REMOTE_ADDR'])));
$code_confirm = substr($code_confirm,3,5);

header("Content-type: image/png");

$im = imagecreate(50,16);
$color_white = imagecolorallocate($im,255,255,255);
$color = imagecolorallocate($im,211,211,211);
$color_gray = imagecolorallocate($im,159,159,159);

imagestring($im,5, 8,1, $code_confirm, $color);
imagestring($im,5, 4,1, $code_confirm, $color_gray);
imageline($im,0,0,50,0,$color);
imageline($im,0,6,50,6,$color);
imageline($im,0,9,50,9,$color);
imageline($im,0,15,50,15,$color);

imagepng($im);
imagedestroy($im);
?>