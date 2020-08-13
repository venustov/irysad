<?php
function NormalDate($date) {
$newDate=explode("-",$date);
$newTime=explode(" ",$newDate[2]);
return $newTime[0].".".$newDate[1].".".$newDate[0];
}
?>