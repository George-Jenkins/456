<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$eventID = cleanInput($_POST['eventID']);

$description = trim($_POST['description']);
$cleanDescription = cleanInput($description);

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'wrong z';
	json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure user is creator of event
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND email='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0){
$return['error'] = 'wrong group';
	json_encode($return);
	return;
}//if

mysql_query("UPDATE events SET description='$cleanDescription' WHERE event_id='$eventID' AND email='$email'");

$return['description'] = nl2br($description);
$return['done'] = 'done';
echo json_encode($return);
?>