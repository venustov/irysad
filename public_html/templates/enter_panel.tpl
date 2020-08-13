<?php
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
	      <form action="auth.php" method="post" name="enter"><table width="260" border="0" cellpadding="2" cellspacing="0" bgcolor="#C9D6E7" style="border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-bottom:1px solid #FFFFFF;">
            <tr align="right">
              <td colspan="2" class="text">Вход в аккаунт: </td>
            </tr>
            <tr>
              <td align="left" class="text">UIN:</td>
              <td align="right"><input type="text" name="uin"></td>
            </tr>
            <tr>
              <td align="left" class="text">Пароль:</td>
              <td align="right"><input type="password" name="pass"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="right"><input type="submit" name="enter" value="Войти"></td>
            </tr>
            <tr align="right">
              <td colspan="2"><a href="recall.php">забыли пароль?</a> | <a href="reg.php">регистрация</a></td>
            </tr>
          </table></form>