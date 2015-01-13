<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$eventID = cleanInput($_POST['eventID']);

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

$query = mysql_query("SELECT * FROM event_attendees WHERE event_id='$eventID'");

$contribution = 0;

while($get_array = mysql_fetch_array($query)){
	
	$contribution += $get_array['contribution'];
}//while

$return['contribution'] = '$'.$contribution;

$return['done'] = 'done';
echo json_encode($return);
?>