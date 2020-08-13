<?
	if (isset($_GET['li'])){
		if(isset($_GET['be'])) $begin = 'http://www.';
		else $begin = 'http://';
	header('Location: '.$begin.$_GET['li']);
	}
?>