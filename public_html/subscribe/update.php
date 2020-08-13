<?php
if (file_exists("config.inc.php")) include("config.inc.php");
if (!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_USER'])) {
	header("WWW-Authenticate: Basic realm=\"TSB-Subscription\"");
	header('HTTP/1.0 401 Unauthorized');
	echo '<b>���������� ��� ������������ � ������.</b>';
	exit;
}
if ($_SERVER['PHP_AUTH_USER']==$username && $_SERVER['PHP_AUTH_PW']==$password) {
include ("head.php");
if (isset($_POST['process'])) {
	if (file_exists("emails.txt")) {
		switch ($_POST['version']) {
// ���������� ���� ������ 1.35.2 � ������ �� ����� ������� ������;
			case 0: {
	            $oldbase=fopen("emails.txt","r");
	            $updatebase=fopen("emails_update.txt","w");
	            while($data=fgetcsv($oldbase,100,",")) {
	            	if (isset($data[5])) {echo '���������� ����������... ������ ������ ������ ����.';break(2);}
	                if (!isset($data[2])) fputs ($updatebase, " ,".$data[0].",".$data[1].", , , , , \n");
	                else fputs ($updatebase, $data[0].",".$data[1].",".$data[2]."\n");
	            }
	            fclose($oldbase);
	            fclose($updatebase);
	            copy("emails_update.txt","emails.txt");
	            unlink("emails_update.txt");
	            unlink("config.inc.php");
	            echo '���������� ���������! ������� �� �������������.';
	            break;
			}
// ���������� ���� ������ 1.35.4 �� ����� ������� ������;
			case 1: {
			    copy ('emails.txt','emails_up_backup.txt');
                $oldbase=fopen("emails.txt","r");
	            $updatebase=fopen("emails_update.txt","w");
	            $i=0;
	            while($data=fgetcsv($oldbase,100,",")) {
	            	if (isset($data[7])) {echo '���������� ����������... ������ ������ ������ ����.';break(2);}
	                if (!isset($data[3])and !isset($data[4]))  $newstr[$i] = $data[0].",".$data[1].",".$data[2].", , , , \n";
	                else $newstr = $data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7]."\n";
                    $i++;
	            }
                $newstr=array_reverse($newstr);
	            for ($i=0;$i<count($newstr);$i++) fputs($updatebase,(count($newstr)-$i).",".$newstr[$i]);
	            fclose($oldbase);
	            fclose($updatebase);
	            copy("emails_update.txt","emails.txt");
	            unlink("emails_update.txt");
	            unlink("config.inc.php");
	            echo '���������� ���������! ������� �� �������������.<br>�� ����� ���������� ���� ������� ��������� ����� ����. (emails_up_backup.txt)';
				break;
			}
		}
	} else echo '���� �������� ��� - ���� �� ������� ����������. �������� ��� ����� ������� � �������� admin.php. ';
} else {
	echo '<p class="ft">��������! ���������� � ���������� �� ������� ������ ���������� ������ ���� �� ���� �������� � <a href="readme.txt">readme</a>!</p><br>';
    echo '�������� ������, ������� ������ � ��� �� ����� � ������� <b>����������.</b><br>';
	echo '<form action="update.php" method="post" name="formupdate">';
	echo '<input type="hidden" name="process" value="update">';
	echo '<select name="version"><option value=0>1.35.2 � �����<option value=1>1.35.4</select>&nbsp;<input type="submit" value="����������">';
	echo '</form>';
}
} else {
  	header("WWW-Authenticate: Basic realm=\"TSB-Subscription\"");
	Header("HTTP/1.0 401 Auth Required");
    echo '<b>������������ ��� ������������ ��� ������</b>';
    exit;
}
?>