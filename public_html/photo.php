<html>
<head>
<meta name="robots" content="index,follow,all">

<title>���� ��� :: ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">

<base href="http://www.irysad.com/">

<meta http-equiv="Expires" content="0">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta HTTP-EQUIV="Cache-Control" CONTENT="no-cache">

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>

<body>
<?php
require ('supple/security.php');
$protection=new security();
$protection->post_decode();
$protection->get_decode();

include ('supple/server_root.php');

$fold = $protection->get['fold'];
$photo = $protection->get['photo'];

	if($fold&&$photo){

	$img = $img_fold.'/photoes/'.$fold.'/'.$photo.'.jpg';
		if (file_exists($img)){
		$size = getimagesize($img);
		echo '<a href="javascript:self.close()"><img src="'.$img.'" width='.$size[0].' height='.$size[1].' border=0 alt="�������" title="�������"></a>';
		}
		else echo '<a href="javascript:self.close()" title="������� ����"><b>�������� �� ������!</b></a><br>
��������, � ��� ��� ����, ��� ��� �����.<br>
���� �� ��������, ��� ��� ������ �������, ��������<br>
<a href="mailto:'.$adm_mail.'">����������</a>';
	}
	else echo '<a href="javascript:self.close()" title="������� ����"><b>�������� �� ������!</b></a><br>
��������, � ��� ��� ����, ��� ��� �����.<br>
���� �� ��������, ��� ��� ������ �������, ��������<br>
<a href="mailto:'.$adm_mail.'">����������</a>';
?>
</body>
</html>