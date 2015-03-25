<?php
include('../../../connect/db-connect.php');

$group = cleanInput($_POST['groupID']);
$loginID = cleanInput($_POST['z']);
$groupState = $_POST['groupState'];
$groupCity = $_POST['groupCity'];
$groupStateClean = cleanInput($groupState);
$groupCityClean = cleanInput($groupCity);

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);

if($numrows==0){
	
	$return['error'] = 'wrong z';
	echo json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//verify group exists
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'no group';
	echo json_encode($return);
	return;
}//if

//make sure user is creator of group
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group' AND created_by='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'not creator';
	echo json_encode($return);
	return;
}//if

mysql_query("UPDATE groups SET state='$groupStateClean', city='$groupCityClean' WHERE group_id='$group'");

if($groupCity) $groupCity .= ', ';
$sendLocation = $groupCity.$groupState;

$return['sendLocation'] = $sendLocation;
echo json_encode($return);

?>