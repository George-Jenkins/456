<?php
//mysql_connect('localhost','root','');

mysql_connect('localhost','abstrag6_Abstrac','Wonder1!');

function cleanInput($input){
	
	$input = mysql_real_escape_string(htmlspecialchars($input));
	return $input;
}

?>