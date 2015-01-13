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

mysql_query("UPDATE events SET active='true' WHERE event_id='$eventID'");

//add this to notifications
	//get groups that were invited
$query = mysql_query("SELECT * FROM event_invited_groups WHERE event_id='$eventID'");
while($get_array = mysql_fetch_array($query)){
	$group = $get_array['group_id'];
	//get name of group
	$query2 = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
	$get2 = mysql_fetch_assoc($query2);
	$groupName = $get2['group_name'];
	//get the members of each group
	$query2 = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email!='$email' AND approved!='no'");
	while($get_array2 = mysql_fetch_array($query2)){
		$invitedEmail = $get_array2['email'];
		$message = "Your entourage ".$groupName." has been invited to an activity";
		//update db
		$noticeID = "events".$eventID;
		$time = time();
		mysql_query("INSERT INTO notifications VALUES('','$message','$invitedEmail','$noticeID','$time')");
	}//while
}//while

$return['done'] = 'done';
echo json_encode($return);
?>