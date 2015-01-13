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

//make sure this is creator of event
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND email='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'not creator';
	echo json_encode($return);
	return;
}//if

mysql_query("DELETE FROM events WHERE event_id='$eventID'");
mysql_query("DELETE FROM event_attendees WHERE event_id='$eventID'");
mysql_query("DELETE FROM event_invited_groups WHERE event_id='$eventID'");

$noticeID = "events".$eventID;
mysql_query("DELETE FROM notifications WHERE notice_id='$noticeID'");

$return['done'] = 'done';
echo json_encode($return);
?>