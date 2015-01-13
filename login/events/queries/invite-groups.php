<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$eventID = cleanInput($_POST['eventID']);
$groupString = cleanInput($_POST['groupString']);

if(!$eventID) return;

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

//make sure event exists
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;
//get creator email
$get = mysql_fetch_assoc($query);
$creatorEmail = $get['email'];

//make sure user has permission to invite groups
$approved = false;
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND email='$email'");
$numrows = mysql_num_rows($query);
if($numrows!=0) $approved = true;
//if not creator see if user is in group who was invited
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND open='true'");
$numrows = mysql_num_rows($query);
if($numrows!=0){
	$query = mysql_query("SELECT * FROM event_invited_groups WHERE event_id='$eventID'");
	while($get_array = mysql_fetch_array($query)){
		$groupID = $get_array['group_id'];
		$query2 = mysql_query("SELECT * FROM group_members WHERE email='$email' AND group_id='$groupID'");
		$numrows2 = mysql_num_rows($query2);
		if($numrows2!=0){
		$approved = true;
		break;
		}//if
	}//while
}//if
if($approved == false){
	$return['error'] = 'no permission';
	echo json_encode($return);
	return;
}//if 

$groups = explode('---',$groupString);


//see if the group was already added. If not add it
foreach($groups as $group){
	$query = mysql_query("SELECT * FROM event_invited_groups WHERE group_id='$group' AND event_id='$eventID'");
	$numrows = mysql_num_rows($query);
	if($group && $numrows==0) mysql_query("INSERT INTO event_invited_groups VALUES('','$email','$group','$eventID')");
}//foreach

$query = mysql_query("SELECT * FROM event_invited_groups WHERE event_id='$eventID'");
//this while loops will help delete groups that weren't in latest submission from db
while($get_array = mysql_fetch_array($query)){

	$groupInDB = $get_array['group_id'];
	$invitorEmail = $get_array['email'];
	$found = false;
	
	foreach($groups as $group) if($groupInDB==$group) $found = true;
	
	if($found == false){
		 if($creatorEmail!=$email) mysql_query("DELETE FROM event_invited_groups WHERE group_id='$groupInDB' AND event_id='$eventID' AND email='$email'");
		 //if this is creator user can delete any invited group
		 else mysql_query("DELETE FROM event_invited_groups WHERE group_id='$groupInDB' AND event_id='$eventID'");
	}//if
}//while


//add this to notifications
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND active='true'");
$numrows = mysql_num_rows($query);
if($numrows!=0){//if published
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
		//see if user already got notification
		$query = mysql_query("SELECT * FROM notifications WHERE email='$invitedEmail' AND notice_id='$noticeID'");
		$numrows = mysql_num_rows($query);
		if($numrows==0) mysql_query("INSERT INTO notifications VALUES('','$message','$invitedEmail','$noticeID','$time')");
	}//while
}//while
}//if numrows!=0

$return['done'] = 'done';
echo json_encode($return);
?>