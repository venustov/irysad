<?php
session_start();

if(!isset($_SESSION['log'])) {$_SESSION['log'] = 0;}
if ($_SESSION['log']) {

include("../config.inc.php");

$subid = (isset($_GET['subid']) and eregi("^[0-9]+$",$_GET['subid']))? $_GET['subid']:"";
$html = "";
$label = array("[name]","[mail]","[sendername]","[url]","[folder]","[id]","[code]");

if(empty($subid)) {

  $refresh = "";
  $mess = "";

  $files = (isset($_FILES['filename_up']) and $_FILES['filename_up'] != "")? $_FILES['filename_up']:"";

  $html .= "<u>Файлы</u>:<br /><br />";

  if(!empty($files)) {

	  if(move_uploaded_file($files['tmp_name'], "../files/".$files['name'])) {
	    $html .= 'Прикрепленный файл загружен!';
    } else {
      $html .= '<font color=\'ref\'>Прикрепленный файл не загружен!</font>';
    }

    $fname = $files['name'];

  }

    if (isset($_POST['filename']) and $_POST['filename']!=""){
      $fname = $_POST['filename'];
    }

    if($lettype == "html") {
      $letterbody = "<html>\n<body>\n".str_replace("\r\n","<br />",$_POST['letterbody'])."</body>\n</html>";
    } else {
      $letterbody = str_replace("\r\n","\n",$_POST['letterbody']);
    }

    $letterbody = stripslashes($letterbody);

		if(empty($fname)) {
	    $headers = "From: $sendername<$sendermail>\n";
	    $headers .= "Errors-To: ".$sendermail."\n";
	    $headers .= "X-Mailer: PHP/".phpversion()."\n";
	    $headers .= "X-Sender: TSB_Subscription\n";
	    $headers .= "Content-Type: text/".$lettype."; charset=".$charset;
	    $body = $letterbody;
    } else {
      $html .= "<br />Имя файла: <b>{$fname}</b>";
		  $file = fopen("../files/".$fname, "r");
			$contents = fread($file, filesize ("../files/".$fname));
			$encoded_attach = chunk_split(base64_encode($contents));
			fclose($file);

			$headers = "From: $sendername<$sendermail>\r\n";
			$headers .= "Errors-To: ".$sendermail."\r\n";
			$headers .= "X-Mailer: PHP / ".phpversion()."\r\n";
			$headers .= "X-Sender: TSB_Subscription\r\n";
			$headers .= "MIME-version: 1.0\n";
			$headers .= "Content-type: multipart/mixed; boundary=\"TSB-Boundary\"\n";

			$body  = "--TSB-Boundary\n";
			$body .= "Content-Type:text/".$lettype."; charset=".$charset." \n";
			$body .= "Content-Transfer-Encoding: 8bit\n\n";
			$body .= $letterbody."\n";
			$body .= "--TSB-Boundary\n";
			$body .= "Content-Type: application/octet-stream; name=\"".$fname."\"\n";
			$body .= "Content-Length: ".strlen($encoded_attach)."\n";
			$body .= "Content-Transfer-Encoding: base64\n";
			$body .= "Content-Disposition: attachment; filename=\"".$fname."\"\n\n";
			$body .=  "$encoded_attach\n";
			$body .= "----TSB-Boundary--\n";
    }

    $str_mail = file("../emails.txt");

    $i = 1;
    $str = "<?php\n\$str_mail = array(\n";
    foreach($str_mail as $value) {
      if($value != "\n" and $value != "") {
        $emails = explode(",",$value);
        if($emails[6] == 2) {
          $mass[$i] = $value;
          $str .= "  {$i} => \"".trim($value)."\",\n";
          $i++;
        }
      }
    }
    $str .= ");\n?>";

    $temp = fopen("data/data.inc.php","w");
    fputs($temp,"<?php\n\$headers = '".addslashes($headers)."';\n\$body = '".addslashes($body)."';\n?>");
    fclose($temp);

    $temp = fopen("data/emails.inc.php","w");
    fputs($temp,$str);
    fclose($temp);

    $sendlet = array("Иван Петрович","ivan@petrovich.mail.ru",$sendername,$senderurl,$senderfolder,"1000","1234567890");

    $html .= "<br /><br /><u>Пример составленного письма</u>:<br /><br /><div align='center'><div style='width: 90%; border: 1px dotted gray;text-align: left;padding: 3px;'>".preg_replace("/src=(\'|\")(.+)(\'|\")/is","src=\\1../files/\\2\\3",str_replace($label,$sendlet,str_replace("\n","<br />",stripslashes($letterbody))))."</div><br /><button style='font-size: 11px; width: 100px;' onclick='window.location=\"send.php?subid=1\"'>Продолжить</button><button style='font-size: 11px; width: 100px;' onclick='window.close();'>Отменить</button></div>";

} else {

include("data/data.inc.php");
include("data/emails.inc.php");
include("data/config.inc.php");

$all_mails = count($str_mail);
$send_mails = ($all_mails == 1)? 1:$subid + $pp - 1;

$maillist = "";
for($i=$subid;$i<($subid+$pp);$i++) {
  $emails = explode(",",$str_mail[$i]);
  if($emails[6] == 2) {
    $orig = array($emails[2],$emails[3],$sendername,$senderurl,$senderfolder,$emails[0],$emails[7]);
    $res = (mail($emails[3],$sendertheme,str_replace($label,$orig,stripslashes($body)),stripslashes($headers)))? "отправлено":"<font color=red>ошибка";
	  $maillist .= "<tr><td>{$emails[0]}&nbsp;</td><td><b>{$emails[3]}</b></td><td>{$res}</td></tr>";
  }
}
$start_id = $i-1;

if($all_mails <= ($start_id)) {
  $a ="stop";
  $stopquery = "a=stop";
  $mess = "<font color='green'><b>Рассылка завершена!</b></font><br /><br /><button style='font-size: 11px; width: 100px;' onclick='window.close();'>Закрыть</button>";
} else {
  $a = "";
  $stopquery = "";
  $mess = "";
}

$refresh = ($a != "stop")? "<meta http-equiv='Refresh' content='{$ps}; url=send.php?subid={$i}&amp;{$stopquery}' />":"";



$html = <<<EOF
Разослано: {$send_mails} из {$all_mails}
<br /><br /><u>Отчет о рассылке:</u><br /><br />
<table style='font-size: 10px;'>
  {$maillist}
</table>
EOF;
}
?>

<html>
<head>
<title>TSB Subscription Send Plugin</title>
<meta http-equiv='Content-Type' content='text/html; charset=windows-1251' />
<?=$refresh?>
<style>
.main {
  color: black;
  background-color: #dfdfdf;
  font-family: verdana,serif;
  font-size: 11px;
  padding-top: 100px;
  width: 550px;
  height: 400px;
  border: 1px solid black;
}
</style>
</head>

<body>
<div align='center'>
  <table class='main'>
    <tr>
      <td valign='middle' align='center' height=30>
        <b>TSB Subscription Send Plugin v.0.1</b>
      </td>
    </tr>
    <tr>
      <td valign='top'>
        <?=$html?>
        <br />
        <div align='center'>
        <?=$mess?>
        </div>
      </td>
    </tr>
    <tr>
      <td valign='bottom' align='center' height=30><br /><br />
        &copy; Powered by <a href='http://tsbs.ru'>TSB Scripts</a>, 2003-06гг.
        <br /><br />
        <div style='text-align: center; width: 90%; color: gray;'>
        Данная разработка защищена авторским правом. Распространение без согласования с автором категорически запрещено.
        </div>
      </td>
    </tr>
  </table>
</body>

</html>

<?php } else header("Location: ../admin.php"); ?>