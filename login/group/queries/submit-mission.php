<?php
include('../../../connect/db-connect.php');

$group = cleanInput($_POST['group']);
$loginID = cleanInput($_POST['z']);
$mission = $_POST['mission'];
$clean_mission = cleanInput($mission);

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

mysql_query("UPDATE groups SET mission='$clean_mission' WHERE group_id='$group'");

$return['mission'] = $mission;
$return['done'] = 'done';
echo json_encode($return);

?>