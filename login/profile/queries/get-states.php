<?php
include('../../../connect/db-connect.php');

include('../../../connect/members.php');

$query = mysql_query("SELECT DISTINCT(state) as state FROM us_cities ORDER BY state ASC");

$stateOption;

while($get_array = mysql_fetch_array($query)){
	
	$state = $get_array['state'];
	
	if(strpos($state,')')==FALSE && strpos($state,'(')==FALSE){
		
		
		$stateOption .= '<option value="'.$state.'">'.$state.'</option>';
	}//if
	
}//while

echo $stateOption;

?>