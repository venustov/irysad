<?php ob_start();
include("head.php");
if (!file_exists("config.inc.php")){
    echo '<b>Установки не определены! Запустите <a href="setup.php">setup.php</a></b><br>';
} else {
	include("config.inc.php");
function login() {
	global $_POST, $password, $username;
	if(isset($_POST['pass']) && isset($_POST['tname']) && $_POST['pass']==$password && $_POST['tname']==$username) {
    	$_SESSION['log']=1;
        $log=1;
        session_register('log');
        header("Location: admin.php");
    } else if(isset($_POST['pass']) and $_POST['pass']!=$password or isset($_POST['tname'])) $err='Неверные данные!';

?>
<p align="center"><table style="border:1px solid black;margin-top:100px;" cellspacing=10>
<form action="" method="post">
<tr><td align="left" style="font-family:verdana;font-size:11px;"><i>введите ваши данные:</i></td></tr>
<tr><td style="font-family:verdana;font-size:11px;"><b>имя</b>&nbsp;<input type="text" name="tname" class="but">
<b>пароль</b>&nbsp;<input type="password" name="pass" class="but"> <input type="submit" value="Войти!" class="but"></td></tr>
<?php if(isset($err)) echo '<tr><td colspan=2 style="color:red;font-family:verdana;font-size:11px;font-color:red;text-align:center;">'.$err.'</td></tr>'; ?>
<tr><td align="right"></td></tr>
</form>
</table>
<br><font style="font-family:verdana;font-size:11px;text-align:center;">TSB Subscription v.<?php show_ver("ver");  ?><br>Powered by <a href="http://tsbs.ru">TSB Scripts</a>, 2003-06</font>
<?php
}
session_start();
if(!isset($_SESSION['log'])) { $_SESSION['log'] = 0;}
if (!$_SESSION['log']) {
	login();
} else {
	 header("Location: action.php?act=1");
}
}
?>
</body>
</html>
<?php ob_end_flush()?>