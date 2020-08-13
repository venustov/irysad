<form method="post">
<input type="text" name="zpost" value="inside">
<input type="submit" value="Check POST method">
Click here for test engine.php: <small><a href="engine.php?list=check">Check script for problems</a></small><br><br>
</form>
<?php

if ((!isset($HTTP_GET_VARS["ip"])) and (isset($_GET["ip"]))) { $HTTP_GET_VARS["ip"] = $_GET["ip"]; }
if ((!isset($HTTP_GET_VARS["list"])) and (isset($_GET["list"]))) { $HTTP_GET_VARS["list"] = $_GET["list"]; }
if ((!isset($HTTP_POST_VARS["xrumer"])) and (isset($_POST["xrumer"]))) { $HTTP_POST_VARS["xrumer"] = $_POST["xrumer"]; }
if ((!isset($HTTP_POST_VARS["zpost"])) and (isset($_POST["zpost"]))) { $HTTP_POST_VARS["zpost"] = $_POST["zpost"]; }

if ($HTTP_GET_VARS["ip"]=="get")
{
  printf("|%s|",$_SERVER["REMOTE_ADDR"]);
}

if ($HTTP_GET_VARS["list"]=="check")
{
  echo '<br><u>Dianostics result:</u><br>';
  echo '<font color=green><b>Your IP: '.$_SERVER['REMOTE_ADDR'].'</font></b><br>';
  if (file_exists('list.txt'))
  {
     echo '<font color=green><b>list.txt exist: OK</font></b><br>';
     $linkslist = file('list.txt');
     if (sizeof($linkslist)==0)
     {
        echo '<font color=red><b>list.txt size = 0!</font></b><br>';
     }
     else
     {
        echo '<font color=green><b>list.txt lines size = '.sizeof($linkslist).': OK</font></b><br>';
     }
  }
  else
  {
     echo '<font color=red><b>list.txt NOT exist in current folder, you should upload list.txt</font></b><br>';
  }
  echo '<br><br>';

}


if ($HTTP_GET_VARS["list"]=="get")
{
        $linkslist = file('list.txt');
	print('|');
	for ($current_number = 0; $current_number < sizeof($linkslist); $current_number++) {
		$linkslist[$current_number] = trim($linkslist[$current_number]);
		if ($linkslist[$current_number]<>'') print($linkslist[$current_number].'|');
	}
} else
{       echo "<small>Header values list:</small>";
        echo "<hr><i><small>";
	foreach($_SERVER as $key => $value)
	{
	if(preg_match("/^HTTP_|^REMOTE_/i",$key))
		printf("%s: %s\n",$key,$value);
	}
	foreach($HTTP_POST_VARS as $key => $value)
	{
		printf("$%s=%s\n",$key,$value);
	}
	echo "FLASHWM.NET\n";
        echo "</small></i><hr>";
}
?>