<?php

include('../../../connect/db-connect.php');
include('../../../connect/members.php');

$currentVal = $_POST['currentVal'];

$query = mysql_query("SELECT * FROM venue WHERE city LIKE '%$currentVal%'");

//add suggestions to list so they aren't repeated
$suggestionList = array();

$x = 0;

while($get_array = mysql_fetch_array($query)){
	
$city = $get_array['city'];
$state = $get_array['state'];

if(!in_array($city.' '.$state,$suggestionList)) $suggestion .="<div id='each-suggestion".$x."' class='each-suggestion' onMouseOver=\"highlightHover('each-suggestion".$x."')\" onClick=\"highlightClick('each-suggestion".$x."')\">".$city.", ".$state."</div>"; 

$suggestionList[] = $city.' '.$state;

$x++;
	
}//while

$return['suggestion'] = $suggestion;

echo json_encode($return);

?>