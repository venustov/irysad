<?php
ob_start();

if (file_exists("config.inc.php")) include("config.inc.php");
include_once ("head.php");
session_start();
if(!isset($_SESSION['log'])) { $_SESSION['log'] = 0;}
if ($_SESSION['log']) {

?>
<body bgcolor=#D1D1D1>
<script language="JavaScript">
function ins_tag (tag) {
	document.main.letterbody.value+=tag+' ';
	document.main.letterbody.focus();
}
</script>
<center>
<table width="510">
<tr><td align=center valign=top>
<?php
switch ($_REQUEST['act'])
{
case 1:
{
if (file_exists("emails.txt"))
{
$fp= fopen("emails.txt", "r");
$k=0;
while ($data=fgetcsv($fp, 100, ",")) $k=$k+1;
setcookie("subs", $k, time()+2592000);
fclose($fp);
if (isset($_COOKIE['subs'])) $subs=$_COOKIE['subs'];
?>
<table width=300>
<tr>
<td>����� <br> <font style="font-size:13px">(� ������� ���������� ���������)</font></td>
<td><?php if(isset($subs)) { if ($k==0 or $subs > $k) echo "0";
 if ($subs <= $k) echo $k-$subs; } else {echo "��� ������";} ?></td>
</tr>
<tr>
<td>�������<br> <font style="font-size:13px">(�������)</font></td>
<td><?php if(isset($subs)){ if ($subs>$k) echo $subs-$k;
else echo "0"; } else echo "��� ������";?></td>
</tr>
<tr>
<td height="30">�����</td>
<td><?php  echo $k; ?></td>
</tr>
<tr><td>������ ����</td><td><?php $size=(filesize ("emails.txt"))/1024; echo $size=round($size,2)." ��"; ?> </td></tr>
<!-- <tr><td>��������� ��������:</td><td><?php // echo filemtime("emails.txt"); ?></td></tr>  -->
</table>
<?php }
else
{
setcookie ("subs", "0", time()+2592000);
echo "���� ������� ��� �� �������!";
}
break;
}
case 2:
{
echo "<table border=0>";
echo '<tr><td><input type=submit value=�������� style="width:100;" onclick="window.location=(\'function.php?process=view\');"></td><td>&nbsp;</td><td valign=center>�������� � �������������� ����</td></form></tr>';
echo "<tr><td><form method=post action=function.php?process=backup>";
echo "<input type=submit value=������� style=\"width:100;\"></td><td>&nbsp;</td><td valign=center>������� ����� ���� </td></form></tr>";
echo "<tr><td><input type=submit value=������������ style=\"width:100;\" onclick=\"window.location=('function.php?process=restore');\"></td><td>&nbsp;</td><td valign=center>������������ �� ����� ���� </td></form></tr>";
echo '<tr><td><form method="post" action="function.php"><input type="hidden" name="process" value="delete"><input type=submit value=�������� style="width:100;"></td><td width=30 align=center><input type=checkbox name=delcheck></td>';
echo "<td>�������� ���� �������</td></form></tr></table>";
break;
}
case 3:
{
echo "�������������� ���� ������� �������� � ������";
echo "<form method=post action=function.php?process=export>";
echo "�������� ����������:<br><select name=pocht>";
echo "<option value=1>TheBat!";
echo "<option value=0>Outlook Express";
echo "<option value=2>Becky!";
echo "<option value=3>Mazilla Mail</select>";
echo "&nbsp;<input type=submit value=��������������> </form>";
echo "<form method=post action=function.php?process=export>";
echo "��������� mail-��������:<br><select name=pochtserver disabled>";
echo "<option value=1>X - Mailer";
echo "<option value=0>OutlookExpress";
echo "<option value=2>Becky!";
echo "<option value=3>Mazilla Mail</select>";
echo "&nbsp;<input type=submit value=�������������� disabled> </form>";
break;
}
case 4:
{
echo "<div align=right>TSB Subscription v. "; show_ver('ver'); echo"<br> build up "; show_ver('date');
echo "<br><font style='font-size:12px;font-family:verdana;'><i>������ �������� � ������� ���� ������ �����������</i></font><br>";
echo "web: <a href='http://tsbs.ru/'>http://tsbs.ru/</a><br>";
echo "e-mail: <a href=mailto:support@tsbs.ru>support@tsbs.ru</a><br>";
echo "2003-06�. <a href=\"http://tsbs.ru/\" target=\"_blank\">TSB Scripts</a><br>";
echo '<div align=left>';
echo '��������: <script src=http://tsb.mimozaf.ru/checkver.php?id=tsbsub&ver=';show_ver('ver');echo"></script><br>";
echo '�������: '; show_ver('ver'); echo'<br><br>';
echo "<font style='font-size:11;font-family:arial;'>��� ����������� ����� - ���� ������, �������� ��� �����������, ����������, �������� �� <a href='http://tsbs.ru/forum/' target='_blank'>������</a> ��� �� <a href='mailto:support@tsbs.ru'>email</a>. <div align=right>� ���������, TSB Scripts</font></div>";
break;
}
case 5:
{
  if(file_exists("emails_thebat.txt")) unlink ("emails_thebat.txt");
  if(file_exists("emails_outlook.csv")) unlink ("emails_outlook.csv");
  if(file_exists("emails_becky.txt")) unlink ("emails_becky.txt");
  if(file_exists("emails_mazilla.ldif")) unlink ("emails_mazilla.ldif");
  session_destroy();
  header("Location: ".$mainurl);
break;
}
case 6: {
	echo '<table><tr><td>';
	echo '<form name="_config" action="function.php" method="post">';
	echo '<input type="hidden" name="process" value="config">';
	echo '<input type="hidden" name="act" value=6>';
	echo '<table width=100% border=0 cellpadding=0 cellspacing=0><tr><td valign=top>';
	echo '<b>����� ���������:</b><br>';
	echo '<input type="text" name="mainurl" value="'.$mainurl.'" style="width:150;">  �������<br>';
	echo '<input type="checkbox" name="style" ';if(isset($style) and $style==1) echo' checked '; echo'> ����������� ���<br>';
  echo '<input type="checkbox" name="digits" ';if(isset($digits) and $digits==1) echo' checked '; echo'> ������������ �������� ���<br><br></td>';
	echo '<td valign=top align=right><b>������������:</b><br>��� <input type="text" name="username" value="'.$username.'" style="width:100;"><br> ������ <input type="password" name="password" value="'.$password.'" style="width:100;">';
	echo '</td></tr></table>';
	echo '<b>�������� ���������:</b><br>';
	echo '<input type="textbox" name="sendermail" value="'.$sendermail.'" style="width:200;"> E-mail (���� reply-to)<br>';
	echo '<input type="text" name="sendername" value="'.$sendername.'" style="width:200;"> ��� (���� from) - [sendername]<br>';
	echo '<input type="text" name="sendertheme" value="'.$sendertheme.'" style="width:200;"> ���� (���� subject)<br><br>';
	echo '<input type="text" name="senderurl" value="http://'.$_SERVER['SERVER_NAME'].'" style="width:200;"> ����� (url) ����� - [url]<br>';
	echo '<input type="text" name="senderfolder" value="'.dirname($_SERVER['PHP_SELF']).'" style="width:200;"> ��� ����� �� �������� - [folder]<br><br>';
    echo '<input type="radio" name="lettype" value="plain"';if(isset($lettype) and $lettype=="plain") echo ' checked'; echo'> text&nbsp;&nbsp; ';
    echo '<input type="radio" name="lettype" value="html"';if(isset($lettype) and $lettype=="html") echo ' checked'; echo'> html <span style="width:88;">&nbsp;</span> ������ �����<br>';
    echo '<input type="text" name="charset" value="';if(isset($charset)) echo $charset; echo'" style="width:200;"> ��������� �����<br><font style="font-size:10px;font-family:verdana;"><b>(���� �� �� ������, ��� ��� ����� - �� �����������!)</b></font><br><br>';
  	echo '<input type="submit" value="���������" style="width:100;">&nbsp;<input type="reset" value="��������" style="width:100;">&nbsp;';
    echo '</form>';
	echo '</td></tr></table>';
	break;
}
case 7: {
    if (isset($_POST['preview'])) {
    	$label = array("\n","[name]","[mail]","[sendername]","[url]","[folder]","[id]","[code]","?id=1&validate=12345010104","/write.php");
    	$orig = array("<br>","<��� ����������>","<����� ����������>",$sendername,"<font color=blue style=\"text-decoration:underline;\">".$senderurl."</font>","<font color=blue style=\"text-decoration:underline;\">".$senderfolder."</font>","<�����>","<���>","<font color=blue style=\"text-decoration:underline;\">?id=1&validate=123456220404</font>","<font color=blue style=\"text-decoration:underline;\">/write.php</font>");
    	echo '<table bgcolor=#EBEBEB border=1><tr><td>';
    	if(isset($HTTP_POST_FILES['filename_up']['name'])) {move_uploaded_file($HTTP_POST_FILES['filename_up']['tmp_name'],"files/".$HTTP_POST_FILES['filename_up']['name']);}
      $letterbody = stripslashes($_POST['letterbody']);
    	$letterbody = preg_replace("/src=(\'|\")(.+)(\'|\")/is","src=\\1files/\\2\\3",$letterbody);
    	echo str_replace($label,$orig,$letterbody);
    	echo '</td></tr></table><br>';
    }

    $hndl=opendir('letters');
    if(!isset($_POST['letname']) and !isset($_GET['letname'])) $letname="send_letter";
    elseif(isset($_POST['letname'])) $letname=str_replace("/","",$_POST['letname']);
    elseif(isset($_GET['letname'])) $letname=str_replace("/","",$_GET['letname']);
    $let=fopen("letters/".$letname.".txt","r");
    if(isset($_POST['pr']) and $_POST['pr']=="read") $let=fopen("letters/".str_replace("/","",$_POST['letname']).".txt","r");
    echo '<form action="action.php" name="letform" method="post">������ ������: <select name="letname">';
    while ($file=readdir($hndl)) {
      $ext = substr($file,-3,3);

    	if($file!='.' and $file!='..' and $ext == "txt") {
    		$letters=substr($file,0,-4);
    		echo '<option value="'.$letters.'"'; if($letters==$letname) echo ' selected ';  echo' >'.$letters;
    	}
    }
    echo '</select>';
    echo '<input type="hidden" name="act" value="7">';
    echo '<input type="hidden" name="pr" value="read">';
    echo '&nbsp;<input type="submit" value="����������!">';
    echo '</form>';
    closedir($hndl);
    $plugin = (file_exists("plugins/send.php"))? 'onclick="document.main.target=\'_blank\';document.main.action = \'plugins/send.php\'"':"";
    echo '<form action="function.php" enctype="multipart/form-data" name="main" method="post" style="font-family:verdana;font-size:11px;">';
    echo '<input type="hidden" name="process" value="save_temp">';
    echo '<input type="hidden" name="lettername" value="'.$letname.'">';
//    echo '<input type="hidden" name="pr" value="save">';
    echo '<textarea name="letterbody" rows=15 style="border:1px solid black;width:510;">';/* if(isset($_POST['preview'])) echo $_POST['letterbody']; else echo str_replace("|","\n",$letterbody); */ fpassthru($let);  echo '</textarea><br>';
	if($letname=="send_letter") 	echo '<div align="right">���������� ����:<br>��� ������������ �����:&nbsp;<input type="text" name="filename" style="width:200;"><br>&nbsp;��������:&nbsp;<input type="file" name="filename_up" style="width:200;"></div><br>';
    echo '<font style="font-size:11px;font-family:verdana;">�������� ����� ��� html �������� ��������� ������������� (\r\n �� &lt;br>).</font><br>';
	echo '<input type="submit" value="���������" style="width:100;" name="save">&nbsp;<input type="hidden" name="act" value="7"><input type="submit" value="������������" style="width:100;" name="preview" onclick = "document.main.action = \'action.php\'">&nbsp;<input type="reset" value="��������" style="width:100;">&nbsp;<input name="send" type="submit" style="border:2px solid black;" value="��������!" '.$plugin.'>';
	echo '</form>';
	echo '<table style="font-size:11px;font-family:verdana;width:510px;" border=0><tr>
		<td align=left width=50%>
		<u>������������ ����:</u><br>
		<a href="javascript:ins_tag(\'[name]\');">[name]</a> - ��� ����������<br>
		<a href="javascript:ins_tag(\'[mail]\');">[mail]</a> - ����� ����������<br>
        <a href="javascript:ins_tag(\'[sendername]\');">[sendername]</a> - ���� ��� ��� ��� �����������<br>
        <a href="javascript:ins_tag(\'[id]\');">[id]</a> - ������������ ����� ����������<br>
        <a href="javascript:ins_tag(\'[code]\');">[code]</a> - ������������ ��� ���������/������������ ��������<br>
        <a href="javascript:ins_tag(\'[url]\');">[url]</a> - ����� ������ �����<br>
        <a href="javascript:ins_tag(\'[folder]\');">[folder]</a> - ����� � ���� ��������<br>
		</td>
		<td valign=top>
        <u>html ��������:</u><br>
        <a href="javascript:ins_tag(\'&lt;br>\');">&lt;br></a> - ������� ������<br>
        <a href="javascript:ins_tag(\'&lt;b>&lt;/b>\');">&lt;b>&lt;/b></a> - ������ �����<br>
        <a href="javascript:ins_tag(\'&lt;i>&lt;/i>\');">&lt;i>&lt;/i></a> - ��������� �����<br>
        <a href="javascript:ins_tag(\'&lt;u>&lt;/u>\');">&lt;u>&lt;/u></a> - ������������ �����<br>
        <a href="javascript:ins_tag(\'&lt;a href=&quot;&quot;>&lt;/a>\');">&lt;a href="">&lt;/a></a> - ������<br>
        <a href="javascript:ins_tag(\'&lt;a href=&quot;mailto:&quot;>&lt;/a>\');">&lt;a href="mailto:">&lt;/a></a> - ������ mailto<br>
        <a href="javascript:ins_tag(\'&lt;div align=&quot;left/center/right&quot;>&lt;/div>\');">&lt;div align="left/center/right">&lt;/div></a> - ������������ ������<br>
		</td>
		</tr></table>';
	break;
}

}
echo '<br><div align=center><font style="font-size:11px;font-family:verdana;">Powered by <a href="http://tsbs.ru" target="_blank">TSB Subscription</a> v. ';show_ver('ver');echo'</font>';

?>
</td>
</table>
</body>
<?php
} else {
  header("Location:admin.php");
}
ob_end_flush();
?>