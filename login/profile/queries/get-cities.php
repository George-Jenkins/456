<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$state_selected = $_POST['state'];
$city_input = $_POST['input'];

include('../../../connect/members.php');

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

$numrows = mysql_num_rows($query);

if($numrows==0) return;

$query = mysql_query("SELECT * FROM us_cities ORDER BY city ASC");

$cityOption;

$x=0;

while($get_array = mysql_fetch_array($query)){
	
	$city = $get_array['city'];
	$state = $get_array['state'];
	
	if(stripos($city,$city_input)===0 && stripos($state,$state_selected)!==FALSE){
	
	$city = str_replace(')','',$city);
	$city = str_replace('(','',$city);
	$city = str_replace("'",'&#39',$city);
	$cityOption .= '<div id="city-num'.$x.'" class="city-div" onClick=\'getCity("'.$city.'",'.$x.')\'>'.$city.'</div>';
	
	$x++;
	}//if
	
}//while

echo $cityOption;

?>