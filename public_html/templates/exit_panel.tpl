<?php
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
          <table width="200" border="0" cellpadding="2" cellspacing="0" bgcolor="#C9D6E7" style="border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-bottom:1px solid #FFFFFF;">
            <tr align="right">
              <td class="submenu">Ваш UIN: 
<?php
echo $_SESSION['logged_user'];
?> (<a href="xunset.php">Выход</a>) </td>
            </tr>
            <tr align="right">
              <td class="submenu"><a href="cabinet/index.php">Личный кабинет</a> | <a href="talking">Форум</a></td>
            </tr>
          </table>