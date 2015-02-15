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

//get event name and start date
$get = mysql_fetch_assoc($query);
$eventName = $get['event_name'];
$startDate = $get['start_date'];

//get users name
$query = mysql_query("SELECT * FROM members WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$name = $get['name'];

//get users timezone
$query = mysql_query("SELECT * FROM account_settings WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$timeZone = $get['timezone'];
date_default_timezone_set($timeZone);

//alert attendees of cancelation
//create variables for notifications
$message = "The event ".$eventName." created by ".$name." has been canceled";
$noticeID = "events".$eventID;

//delete invited notifications
mysql_query("DELETE FROM notifications WHERE notice_id='$noticeID'");

$query = mysql_query("SELECT * FROM event_attendees WHERE event_id='$eventID' AND email!='$email'");
while($get_array = mysql_fetch_array($query)){
	
$invitedEmail = $get_array['email'];
$date = date('Y-m-d');
$time = time();

if($date<=$startDate) mysql_query("INSERT INTO notifications VALUES('','$message','$invitedEmail','$noticeID','$time')");
}//while

mysql_query("DELETE FROM events WHERE event_id='$eventID'");
mysql_query("DELETE FROM event_attendees WHERE event_id='$eventID'");
mysql_query("DELETE FROM event_invited_groups WHERE event_id='$eventID'");

$return['done'] = 'done';
echo json_encode($return);
?>