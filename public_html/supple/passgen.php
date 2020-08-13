<?php
function passgen($length){
$vals = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	for($i=1; $i<=$length; $i++){
	$result.=$vals{rand(0, strlen($vals))};
	}
return $result;
}
?>