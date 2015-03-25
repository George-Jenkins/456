<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../connect/members.php');

//get email and name 
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

$numrows = mysql_num_rows($query);
if($numrows==0){
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];
$name = $get['name'];

$query = mysql_query("SELECT DISTINCT(state) as state FROM us_cities ORDER BY state ASC");

$stateOption;

while($get_array = mysql_fetch_array($query)){
	
	$state = $get_array['state'];
	
	if(strpos($state,')')==FALSE && strpos($state,'(')==FALSE){
		
		
		$stateOption .= '<option value="'.$state.'">'.$state.'</option>';
	}//if
	
}//while


$return['states'] = $stateOption;

echo json_encode($return);

?>