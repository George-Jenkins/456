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

//make sure the event is active
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND active='true'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'wrong event';
	echo json_encode($return);
	return;
}//if

//make sure this user is either the creator or an invited user
$query = mysql_query("SELECT * FROM event_invited_groups WHERE event_id='$eventID'");
$invited = false;
while($get_array = mysql_fetch_array($query)){
	
	$invitedGroup = $get_array['group_id'];
	$query2 = mysql_query("SELECT * FROM group_members WHERE group_id='$invitedGroup' AND email='$email' AND approved='yes'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0) $invited = true;
}//while

$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID'");
$get = mysql_fetch_assoc($query);
$creatorEmail = $get['email'];
if($invited == false && $email!=$creatorEmail){//if user wasn't invited and isn't creator stop
	$return['error'] = 'not invited';
	echo json_encode($return);
	return;
}//if

//remove user
mysql_query("DELETE FROM event_attendees WHERE email='$email' AND event_id='$eventID'");

$return['done'] = 'done';
echo json_encode($return);
?>