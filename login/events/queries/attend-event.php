<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$eventID = cleanInput($_POST['eventID']);
$contribution = cleanInput($_POST['contribution']);

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

//see if event has expired
$get = mysql_fetch_assoc($query);
$endDate = $get['end_date'];
$date = date('Y-m-d');
if($date>$endDate){
	$return['expired'] = 'expired';
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

//make sure contribution is enough
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID'");
$get = mysql_fetch_assoc($query);
$minContribution = $get['min_contribution'];
if($minContribution!=0){
	if($minContribution>$contribution){
		$return['error'] = 'not enough';
		echo json_encode($return);
		return;
	}//if
}//if

//see if user has already made contribution
$query = mysql_query("SELECT * FROM event_attendees WHERE event_id='$eventID' AND email='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0) mysql_query("INSERT INTO event_attendees VALUES('','$email','$contribution','$eventID')");
else mysql_query("UPDATE event_attendees SET contribution='$contribution' WHERE event_id='$eventID' AND email='$email'");

$return['done'] = 'done';
echo json_encode($return);
?>