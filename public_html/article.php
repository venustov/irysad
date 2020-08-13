<?php
session_start();

require ('supple/security.php');
$protection=new security();
$protection->get_decode();
$protection->post_decode();

include ('supple/server_root.php');
require ('supple/auth_db.php');
require ('supple/translit.php');
require ('supple/capslock.php');

if(!isset($_SESSION['city'])){
	if ($_COOKIE['city']){
	$str_sql_query = 'SELECT name, icq, phone, admin, country FROM cities WHERE name = "'.$_COOKIE['city'].'"';
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if(!$number){
		$str_sql_query = "SELECT name, icq, phone, admin, country FROM cities WHERE capital = '1'";
		$result = mysql_query($str_sql_query) or die(mysql_error());
		$number = mysql_num_rows($result);
			if($number){
			$i = 0;
			
			$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
			setcookie('city', mysql_result($result,$i,"name"), $end_cookie, '/');
		
			$_SESSION['city'] = mysql_result($result,$i,"name");
			$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
			$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
				if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
			$_SESSION['country'] = mysql_result($result,$i,"country");
			}
		}
		else{
		$i = 0;
		
		$_SESSION['city'] = $_COOKIE['city'];
		$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
		$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
			if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
		$_SESSION['country'] = mysql_result($result,$i,"country");
		}
	}
	else {
	$str_sql_query = "SELECT name, icq, phone, admin, country FROM cities WHERE capital = '1'";
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
		
		$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
		setcookie('city', mysql_result($result,$i,"name"), $end_cookie, '/');
		
		$_SESSION['city'] = mysql_result($result,$i,"name");
		$_SESSION['icq_city'] = mysql_result($result,$i,"icq");
		$_SESSION['phone_city'] = mysql_result($result,$i,"phone");
			if(!isset($_SESSION['referer'])) $_SESSION['referer'] = mysql_result($result,$i,"admin");
		$_SESSION['country'] = mysql_result($result,$i,"country");
		}
	}
}
$id = $protection->get['id'];
	if($id){
	$str_sql_query = 'SELECT * FROM articles AS a WHERE a.id = "'.$id.'"';
	$result = mysql_query($str_sql_query) or die(mysql_error());
	$number = mysql_num_rows($result);
		if($number){
		$i = 0;
		$article = mysql_result($result,$i,"a.title");
		$epigraf = mysql_result($result,$i,"a.epigraf");
		$desc = mysql_result($result,$i,"a.description");
		$author = mysql_result($result,$i,"a.author");
		$link_on = mysql_result($result,$i,"a.link");
		$ankor = mysql_result($result,$i,"a.ankor");
/* Это, если делать отдельно для каждой соц.сети:		
		$break_vk = '<script type="text/javascript">document.write(VK.Share.button(false,{type: "round", text: "поделиться"}));</script>';
		$break_ya = '<a counter="yes" type="button" size="small" name="ya-share"></a><script charset="utf-8" type="text/javascript">if (window.Ya && window.Ya.Share) {Ya.Share.update();} else {(function(){if(!window.Ya) { window.Ya = {} };Ya.STATIC_BASE = \'http:\/\/yandex.st\/wow\/2.7.7\/static\';Ya.START_BASE = \'http:\/\/my.ya.ru\/\';var shareScript = document.createElement("script");shareScript.type = "text/javascript";shareScript.async = "true";shareScript.charset = "utf-8";shareScript.src = Ya.STATIC_BASE + "/js/api/Share.js";(document.getElementsByTagName("head")[0] || document.body).appendChild(shareScript);})();}</script>';
		$break_tw = '<a href="https://twitter.com/share" class="twitter-share-button" data-via="ireysad" data-lang="ru">Твитнуть</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
		$break_fb = '<div class="fb-like" data-send="false" data-layout="button_count" data-width="100" data-show-faces="true" data-font="tahoma"></div>';

		$break = '<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr><td>'.$break_vk.'</td><td>&nbsp;</td><td>'.$break_fb.'</td><td>&nbsp;</td><td>'.$break_ya.'</td><td>&nbsp;</td><td>'.$break_tw.'</td></tr></table>';
*/
		$break = '</p><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,friendfeed,moikrug,gplus"></div><p align="justify">';
		
		$desc = str_replace('[break]',$break,$desc);
		
		$desc = str_replace('<a href="http://www.','<a href="/go.php?be=1&li=',$desc);
		$desc = str_replace('<a href="http://','<a href="/go.php?li=',$desc);
		
		$desc = str_replace("<a href='http://www.","<a href='/go.php?be=1&li=",$desc);
		$desc = str_replace("<a href='http://","<a href='/go.php?li=",$desc);
		
		$desc = str_replace('<a href=http://www.','<a href=/go.php?be=1&li=',$desc);
		$desc = str_replace('<a href=http://','<a href=/go.php?li=',$desc);
		$desc = str_replace('
','<p align="justify">',$desc);

		$desc = str_replace('[[','<a href="',$desc);
		$desc = str_replace('|','">',$desc);
		$desc = str_replace(']]','</a>',$desc);
		}
	}

	if($_COOKIE['referer']) $_SESSION['referer'] = $_COOKIE['referer'];
	elseif($protection->get['referer']){
	$_SESSION['referer'] = $protection->get['referer'];
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $protection->get['referer'], $end_cookie, '/');
	}
	elseif($number){
//	$i = 0;
	$_SESSION['referer'] = mysql_result($result,$i,"maker");
	$end_cookie = mktime(0,0,0,1,1,date("Y")+3);
	setcookie('referer', $_SESSION['referer'], $end_cookie, '/');
	}
//echo $referer;
//<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> -->
?>
<?php
include ('templates/header.tpl');
?>

<title><?php echo $article; ?></title>
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>

<script type="text/javascript">
  VK.init({apiId: 2974078, onlyWidgets: true});
</script>

<?php
    if (!defined('_SAPE_USER')){
        define('_SAPE_USER', '1802cc91baed5a3f9fcc8490d028aa02');
    }
    require_once(realpath($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php'));
	
    $sape = new SAPE_client();
	
    $sape_context = new SAPE_context();
    ob_start(array(&$sape_context,'replace_in_page'));
?>

</head>

<body>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#B7C9E1">
  <tr>
    <td width="400" align="right" valign="middle" class="head_left"><h1>Потребительский кооператив &quot;Ирий Сад&quot;</h1>
	<h2>качественные продукты для сыроедов</h2>
<?php
	if($_SESSION['city']) echo '<p>'.$_SESSION['city'].'<br>';
	if($_SESSION['phone_city']) echo 'Тел.: '.$_SESSION['phone_city'].'<br>';
	if($_SESSION['icq_city']) echo 'ICQ# '.$_SESSION['icq_city'].'<br>';
?>
	<a href="addcity.php">выбрать другой город</a></p></td>
    <td width="200"><img src="images/head2_02.jpg" alt="магазин видовой еды" width="200" height="150"></td>
    <td width="400" align="right" valign="bottom" class="head_right">
<?php
	if(!isset($_SESSION['logged_user'])){
	include ('templates/enter_panel.tpl');
	}
	else include ('templates/exit_panel.tpl');
?>
	</td>
  </tr>
<?php
include ('templates/menu.tpl');
?>
  <tr>
    <td colspan="3" class="menu"><table width="100%" border="0" cellpadding="10" cellspacing="0">
      <tr>
        <td width="20%" align="right" valign="top" bgcolor="#B7C9E1">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="submenu">
<?php
$str_sql_query = "SELECT a.id, a.category, a.lat_cat, a.tags FROM articles AS a WHERE a.approve = '1' ORDER BY a.id DESC";
$result = mysql_query($str_sql_query);
$number = mysql_num_rows($result);
	if($number){
//Генерация ссылок на случайные статьи:	
	$link_1 = mt_rand(0,($number-1));
	$link_2 = mt_rand(0,($number-1));
	$link_3 = mt_rand(0,($number-1));
		while($link_2==$link_1){
		$link_2 = mt_rand(0,($number-1));
		}
		while(($link_3==$link_1)||($link_3==$link_2)){
		$link_3 = mt_rand(0,($number-1));
		}
	$tag_1 = mysql_result($result,$link_1,"a.tags");
	$link_1 = mysql_result($result,$link_1,"a.id");
	$tag_1 = explode(';',$tag_1);
	$num_1 = mt_rand(0,(count($tag_1)-1));
	$tag_1 = $tag_1[$num_1];
	
	$tag_2 = mysql_result($result,$link_2,"a.tags");
	$link_2 = mysql_result($result,$link_2,"a.id");
	$tag_2 = explode(';',$tag_2);
	$num_2 = mt_rand(0,(count($tag_2)-1));
	$tag_2 = $tag_2[$num_2];
	
	$tag_3 = mysql_result($result,$link_3,"a.tags");
	$link_3 = mysql_result($result,$link_3,"a.id");
	$tag_3 = explode(';',$tag_3);
	$num_3 = mt_rand(0,(count($tag_3)-1));
	$tag_3 = $tag_3[$num_3];
/*	
echo '1='.$link_1.'-'.$tag_1.'
2='.$link_2.'-'.$tag_2.'
3='.$link_3.'-'.$tag_3;*/
//конец блока

	$i = 0;
	$cat_arr[0] = mysql_result($result,$i,"a.category");
		while($i < $number){
		$cat = mysql_result($result,$i,"a.category");
		$n = 0;
			foreach($cat_arr as $value){
				if($cat == $value){
				$n++;
				}
			}
			if(!$n) array_push($cat_arr, $cat);
		$i++;
		}
	}
	if(is_array($cat_arr)){
		for($i=0; $i<count($cat_arr); $i++){
			if(ereg(capslock(translit($cat_arr[$i])),$_SERVER['REQUEST_URI'])) $class = 'mn2';
			else $class = 'mn1';
		echo '<div class="'.$class.'"><a href="/articles.php?cat='.capslock(translit($cat_arr[$i])).'">'.$cat_arr[$i].'</a></div>';
		}
	}
?>
	</td>
  </tr>
  <tr>
    <td align="right">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-5218717493275246";
/* Вертикальный блок в статьях */
google_ad_slot = "3736740212";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</td>
  </tr>
  <tr>
    <td><? echo $sape->return_links(1); ?></td>
  </tr>
  <tr>
    <td><? echo $sape->return_links(1); ?></td>
  </tr>
</table>
		</td>
        <td width="80%" valign="top" style="border-left:1px solid #FFFFFF;"><table width="100%"  border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF" style="border-top:1px solid #758DB3; border-left:1px solid #758DB3; border-right:1px solid #758DB3; border-bottom:1px solid #758DB3;">
          <tr>
            <td><h2><?php echo $article; ?></h2></td>
          </tr>
<?php
	if($epigraf){
	echo '<tr>
            <td align="right"><em>'.$epigraf.'</em></td>
          </tr>';
	}
?>
          <tr>
            <td><?php
$img = $img_fold.'/articles/'.$id.'.jpg';
	if(file_exists($img)){
	echo '<img src="'.$img.'" vspace=5 hspace=18 border=0 align="right" alt="'.$article.'" title="'.$article.'">';
	}
?><div class="article"><p align="justify"><sape_index><?php echo $desc; ?></sape_index></div></td>
          </tr>
          <tr>
            <td><?php echo $author; ?></td>
          </tr>
<?php
	if($link_on&&$ankor){
//	$link_on = str_replace('http://www.','/go.php?be=1&li=',$link_on);
//	$link_on = str_replace('http://','/go.php?li=',$link_on);
	echo '<tr>
            <td>Взято с: <a href="'.$link_on.'" target="_blank">'.$ankor.'</a></td>
          </tr>';
	}
echo '<tr>
		<td align="center">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-5218717493275246";
/* Баннер внизу статьи */
google_ad_slot = "8319887017";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
		</td>
      </tr>
      <tr>
        <td>Популярные запросы: ';
	if($link_1!=$id) echo '<a href="/articles/'.$link_1.'.html">'.$tag_1.'</a>';
	if($link_2!=$id) echo ' <a href="/articles/'.$link_2.'.html">'.$tag_2.'</a>';
	if($link_3!=$id) echo ' <a href="/articles/'.$link_3.'.html">'.$tag_3.'</a>';
echo '</td>
      </tr>';
echo '<tr>
        <td><div id="vk_comments"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 20, width: "720", attach: "*"});
</script></td>
          </tr>';
?>
        </table></td>
        </tr>
    </table></td>
  </tr>
  <tr align="center">
   <td colspan="3"><? echo $sape->return_links(); ?></td>
  </tr>
<?php
include ('templates/footer.tpl');
?>