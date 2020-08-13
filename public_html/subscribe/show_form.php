<style>.tf {border:1px solid black; font-size:12px; width:100px; text-align:center;}</style>
<?php
$path= __FILE__;
$path=str_replace("/show_form.php","",$path);
$path=str_replace("\show_form.php","",$path);
include($path."/config.inc.php");
$code = rand(1000,2000).rand(2000,3000);
echo '<table>';
echo '<form name="subform" action="'.$senderfolder.'/write.php" method="post" target="sub">';
echo "<input type='hidden' name='getcode' value={$code}>";
if(!isset($style) or $style==1) {
	echo '<tr><td><input type="textbox" name="name" class="tf" value="[ ваше имя ]" id="fn" onfocus=\'if (this.value=="[ ваше имя ]") this.value="";\' onblur=\'if (this.value=="") this.value="[ ваше имя ]";\'></td></tr>';
	echo '<tr><td><input type="textbox" name="mail" class="tf" value="[ ваша почта ]" id="fm" onfocus=\'if (this.value=="[ ваша почта ]") this.value="";\' onblur=\'if (this.value=="") this.value="[ ваша почта ]";\'></td></tr>';
if(!isset($digits) or $digits==1) {
	echo <<<EOF
        <tr>
          <td>
            <img src='{$senderfolder}/img.php?code={$code}' width=50 height=16 border=0 align='absmiddle'>
            <input type='text' name='code_confirm' class='tf' style='width: 45px;'><br>
          </td>
        </tr>
EOF;
}
	echo '<tr><td><input type="submit" value="Подписаться" class="tf" onclick="sub=window.open(\'\',\'sub\',\'width=550,height=150,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no\');sub.document.write(\'<b>Идет подписка...</b>\');"></td></tr>';
} else {
	echo '<tr><td><input type="textbox" name="mail" class="tf" value="[ ваша почта ]" id="fm" onfocus=\'if (this.value=="[ ваша почта ]") this.value="";\' onblur=\'if (this.value=="") this.value="[ ваша почта ]";\'></td>';
  echo '<td><input type="submit" value="ok" class="tf" style="width:20;" onclick="sub=window.open(\'\',\'sub\',\'width=550,height=150,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no\');sub.document.write(\'<b>Идет подписка...</b>\');"></td></tr>';
}
echo '</form>';
echo '</table>';
?>