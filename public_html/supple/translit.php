<?php
//	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
function translit($cyr_str){
$tr = array(
"�"=>"G","�"=>"Yo","�"=>"E","�"=>"Yi","�"=>"I",
"�"=>"i","�"=>"g","�"=>"yo","�"=>"#","�"=>"e",
"�"=>"yi","�"=>"A","�"=>"B","�"=>"V","�"=>"G",
"�"=>"D","�"=>"E","�"=>"Zh","�"=>"Z","�"=>"I",
"�"=>"Y","�"=>"K","�"=>"L","�"=>"M","�"=>"N",
"�"=>"O","�"=>"P","�"=>"R","�"=>"S","�"=>"T",
"�"=>"U","�"=>"F","�"=>"Kh","�"=>"Ts","�"=>"Ch",
"�"=>"Sh","�"=>"Sch","�"=>"'","�"=>"Yi","�"=>"",
"�"=>"E","�"=>"Yu","�"=>"Ya","�"=>"a","�"=>"b",
"�"=>"v","�"=>"g","�"=>"d","�"=>"e","�"=>"zh",
"�"=>"z","�"=>"i","�"=>"y","�"=>"k","�"=>"l",
"�"=>"m","�"=>"n","�"=>"o","�"=>"p","�"=>"r",
"�"=>"s","�"=>"t","�"=>"u","�"=>"f","�"=>"kh",
"�"=>"ts","�"=>"ch","�"=>"sh","�"=>"sch","�"=>"'",
"�"=>"yi","�"=>"","�"=>"e","�"=>"yu","�"=>"ya",
"������"=>"Russia","���"=>"Saint-Petersburg","������"=>"Moscow"
);
return strtr($cyr_str,$tr);
}
?>