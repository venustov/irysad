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
<td>Новых <br> <font style="font-size:13px">(с момента последнего посещения)</font></td>
<td><?php if(isset($subs)) { if ($k==0 or $subs > $k) echo "0";
 if ($subs <= $k) echo $k-$subs; } else {echo "нет данных";} ?></td>
</tr>
<tr>
<td>Удалено<br> <font style="font-size:13px">(вручную)</font></td>
<td><?php if(isset($subs)){ if ($subs>$k) echo $subs-$k;
else echo "0"; } else echo "нет данных";?></td>
</tr>
<tr>
<td height="30">Всего</td>
<td><?php  echo $k; ?></td>
</tr>
<tr><td>Размер базы</td><td><?php $size=(filesize ("emails.txt"))/1024; echo $size=round($size,2)." Кб"; ?> </td></tr>
<!-- <tr><td>Последняя подписка:</td><td><?php // echo filemtime("emails.txt"); ?></td></tr>  -->
</table>
<?php }
else
{
setcookie ("subs", "0", time()+2592000);
echo "База адресов еще не создана!";
}
break;
}
case 2:
{
echo "<table border=0>";
echo '<tr><td><input type=submit value=Просмотр style="width:100;" onclick="window.location=(\'function.php?process=view\');"></td><td>&nbsp;</td><td valign=center>Просмотр и редактирование базы</td></form></tr>';
echo "<tr><td><form method=post action=function.php?process=backup>";
echo "<input type=submit value=Создать style=\"width:100;\"></td><td>&nbsp;</td><td valign=center>Сделать копию базы </td></form></tr>";
echo "<tr><td><input type=submit value=Восстановить style=\"width:100;\" onclick=\"window.location=('function.php?process=restore');\"></td><td>&nbsp;</td><td valign=center>Восстановить из копии базы </td></form></tr>";
echo '<tr><td><form method="post" action="function.php"><input type="hidden" name="process" value="delete"><input type=submit value=Обнулить style="width:100;"></td><td width=30 align=center><input type=checkbox name=delcheck></td>';
echo "<td>Обнулить базу адресов</td></form></tr></table>";
break;
}
case 3:
{
echo "Экспортировать базу адресов рассылки в формат";
echo "<form method=post action=function.php?process=export>";
echo "почтовых менеджеров:<br><select name=pocht>";
echo "<option value=1>TheBat!";
echo "<option value=0>Outlook Express";
echo "<option value=2>Becky!";
echo "<option value=3>Mazilla Mail</select>";
echo "&nbsp;<input type=submit value=Экспортировать> </form>";
echo "<form method=post action=function.php?process=export>";
echo "анонимных mail-серверов:<br><select name=pochtserver disabled>";
echo "<option value=1>X - Mailer";
echo "<option value=0>OutlookExpress";
echo "<option value=2>Becky!";
echo "<option value=3>Mazilla Mail</select>";
echo "&nbsp;<input type=submit value=Экспортировать disabled> </form>";
break;
}
case 4:
{
echo "<div align=right>TSB Subscription v. "; show_ver('ver'); echo"<br> build up "; show_ver('date');
echo "<br><font style='font-size:12px;font-family:verdana;'><i>скрипт рассылки и ведения базы данных подписчиков</i></font><br>";
echo "web: <a href='http://tsbs.ru/'>http://tsbs.ru/</a><br>";
echo "e-mail: <a href=mailto:support@tsbs.ru>support@tsbs.ru</a><br>";
echo "2003-06г. <a href=\"http://tsbs.ru/\" target=\"_blank\">TSB Scripts</a><br>";
echo '<div align=left>';
echo 'доступна: <script src=http://tsb.mimozaf.ru/checkver.php?id=tsbsub&ver=';show_ver('ver');echo"></script><br>";
echo 'текущая: '; show_ver('ver'); echo'<br><br>';
echo "<font style='font-size:11;font-family:arial;'>При обнаружении каких - либо ошибок, опечаток или неточностей, пожалуйста, сообщите на <a href='http://tsbs.ru/forum/' target='_blank'>форуме</a> или по <a href='mailto:support@tsbs.ru'>email</a>. <div align=right>С Уважением, TSB Scripts</font></div>";
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
	echo '<b>Общие настройки:</b><br>';
	echo '<input type="text" name="mainurl" value="'.$mainurl.'" style="width:150;">  Главная<br>';
	echo '<input type="checkbox" name="style" ';if(isset($style) and $style==1) echo' checked '; echo'> Запрашивать имя<br>';
  echo '<input type="checkbox" name="digits" ';if(isset($digits) and $digits==1) echo' checked '; echo'> Использовать цифровой код<br><br></td>';
	echo '<td valign=top align=right><b>Безопасность:</b><br>Имя <input type="text" name="username" value="'.$username.'" style="width:100;"><br> Пароль <input type="password" name="password" value="'.$password.'" style="width:100;">';
	echo '</td></tr></table>';
	echo '<b>Почтовые настройки:</b><br>';
	echo '<input type="textbox" name="sendermail" value="'.$sendermail.'" style="width:200;"> E-mail (поле reply-to)<br>';
	echo '<input type="text" name="sendername" value="'.$sendername.'" style="width:200;"> Имя (поле from) - [sendername]<br>';
	echo '<input type="text" name="sendertheme" value="'.$sendertheme.'" style="width:200;"> Тема (поле subject)<br><br>';
	echo '<input type="text" name="senderurl" value="http://'.$_SERVER['SERVER_NAME'].'" style="width:200;"> Адрес (url) сайта - [url]<br>';
	echo '<input type="text" name="senderfolder" value="'.dirname($_SERVER['PHP_SELF']).'" style="width:200;"> Имя папки со скриптом - [folder]<br><br>';
    echo '<input type="radio" name="lettype" value="plain"';if(isset($lettype) and $lettype=="plain") echo ' checked'; echo'> text&nbsp;&nbsp; ';
    echo '<input type="radio" name="lettype" value="html"';if(isset($lettype) and $lettype=="html") echo ' checked'; echo'> html <span style="width:88;">&nbsp;</span> Формат писем<br>';
    echo '<input type="text" name="charset" value="';if(isset($charset)) echo $charset; echo'" style="width:200;"> Кодировка писем<br><font style="font-size:10px;font-family:verdana;"><b>(если вы не знаете, что это такое - не исправляйте!)</b></font><br><br>';
  	echo '<input type="submit" value="Сохранить" style="width:100;">&nbsp;<input type="reset" value="Сбросить" style="width:100;">&nbsp;';
    echo '</form>';
	echo '</td></tr></table>';
	break;
}
case 7: {
    if (isset($_POST['preview'])) {
    	$label = array("\n","[name]","[mail]","[sendername]","[url]","[folder]","[id]","[code]","?id=1&validate=12345010104","/write.php");
    	$orig = array("<br>","<имя подписчика>","<адрес подписчика>",$sendername,"<font color=blue style=\"text-decoration:underline;\">".$senderurl."</font>","<font color=blue style=\"text-decoration:underline;\">".$senderfolder."</font>","<номер>","<код>","<font color=blue style=\"text-decoration:underline;\">?id=1&validate=123456220404</font>","<font color=blue style=\"text-decoration:underline;\">/write.php</font>");
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
    echo '<form action="action.php" name="letform" method="post">Шаблон письма: <select name="letname">';
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
    echo '&nbsp;<input type="submit" value="Подгрузить!">';
    echo '</form>';
    closedir($hndl);
    $plugin = (file_exists("plugins/send.php"))? 'onclick="document.main.target=\'_blank\';document.main.action = \'plugins/send.php\'"':"";
    echo '<form action="function.php" enctype="multipart/form-data" name="main" method="post" style="font-family:verdana;font-size:11px;">';
    echo '<input type="hidden" name="process" value="save_temp">';
    echo '<input type="hidden" name="lettername" value="'.$letname.'">';
//    echo '<input type="hidden" name="pr" value="save">';
    echo '<textarea name="letterbody" rows=15 style="border:1px solid black;width:510;">';/* if(isset($_POST['preview'])) echo $_POST['letterbody']; else echo str_replace("|","\n",$letterbody); */ fpassthru($let);  echo '</textarea><br>';
	if($letname=="send_letter") 	echo '<div align="right">Прикрепить файл:<br>Имя загруженного файла:&nbsp;<input type="text" name="filename" style="width:200;"><br>&nbsp;Загрузка:&nbsp;<input type="file" name="filename_up" style="width:200;"></div><br>';
    echo '<font style="font-size:11px;font-family:verdana;">Переносы строк для html рассылок заменятся автоматически (\r\n на &lt;br>).</font><br>';
	echo '<input type="submit" value="Сохранить" style="width:100;" name="save">&nbsp;<input type="hidden" name="act" value="7"><input type="submit" value="Предпросмотр" style="width:100;" name="preview" onclick = "document.main.action = \'action.php\'">&nbsp;<input type="reset" value="Сбросить" style="width:100;">&nbsp;<input name="send" type="submit" style="border:2px solid black;" value="Отослать!" '.$plugin.'>';
	echo '</form>';
	echo '<table style="font-size:11px;font-family:verdana;width:510px;" border=0><tr>
		<td align=left width=50%>
		<u>используемые коды:</u><br>
		<a href="javascript:ins_tag(\'[name]\');">[name]</a> - имя подписчика<br>
		<a href="javascript:ins_tag(\'[mail]\');">[mail]</a> - почта подписчика<br>
        <a href="javascript:ins_tag(\'[sendername]\');">[sendername]</a> - Ваше имя или имя организации<br>
        <a href="javascript:ins_tag(\'[id]\');">[id]</a> - персональный номер подписчика<br>
        <a href="javascript:ins_tag(\'[code]\');">[code]</a> - персональный код активации/дизактивации рассылки<br>
        <a href="javascript:ins_tag(\'[url]\');">[url]</a> - адрес Вашего сайта<br>
        <a href="javascript:ins_tag(\'[folder]\');">[folder]</a> - папка с этим скриптом<br>
		</td>
		<td valign=top>
        <u>html разметка:</u><br>
        <a href="javascript:ins_tag(\'&lt;br>\');">&lt;br></a> - перенос строки<br>
        <a href="javascript:ins_tag(\'&lt;b>&lt;/b>\');">&lt;b>&lt;/b></a> - жирный текст<br>
        <a href="javascript:ins_tag(\'&lt;i>&lt;/i>\');">&lt;i>&lt;/i></a> - курсивный текст<br>
        <a href="javascript:ins_tag(\'&lt;u>&lt;/u>\');">&lt;u>&lt;/u></a> - подчеркнутый текст<br>
        <a href="javascript:ins_tag(\'&lt;a href=&quot;&quot;>&lt;/a>\');">&lt;a href="">&lt;/a></a> - ссылка<br>
        <a href="javascript:ins_tag(\'&lt;a href=&quot;mailto:&quot;>&lt;/a>\');">&lt;a href="mailto:">&lt;/a></a> - ссылка mailto<br>
        <a href="javascript:ins_tag(\'&lt;div align=&quot;left/center/right&quot;>&lt;/div>\');">&lt;div align="left/center/right">&lt;/div></a> - расположение текста<br>
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