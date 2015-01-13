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

//determine if this is the creator of the event

$query = mysql_query("SELECT * FROM group_members WHERE email='$email' AND approved!='no'");

while($get_array = mysql_fetch_array($query)){
	
	$groupID = $get_array['group_id'];
	
	$query2 = mysql_query("SELECT * FROM groups WHERE group_id='$groupID'");
	$get2 = mysql_fetch_assoc($query2);
	$groupName = $get2['group_name'];
	$id = $get2['id'];
	$imgFolder = $get2['image_folder'];
	$image = $get2['group_img'];
	if($image) $path = "../group/pics/".$imgFolder."/".$image;
	else $path = "../../pics/nightclub3.jpg";
	
	//see if this group was invited
	$query2 = mysql_query("SELECT * FROM event_invited_groups WHERE group_id='$groupID' AND event_id='$eventID'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0) $class = "check-group shown";
	else $class = "check-group hide";
	$get2 = mysql_fetch_assoc($query2);
	$invitorEmail = $get2['email'];
	
	//see if this user created event 
	$query2 = mysql_query("SELECT * FROM events WHERE event_id='$eventID'");
	$get2 = mysql_fetch_assoc($query2);
	$creatorEmail = $get2['email'];
	if($creatorEmail!=$email && $numrows2!=0 && $invitorEmail!=$email){//if this user did not create this event and someone invited this group already don't let them invite/uninvite group
		$groupListTitle = "".$groupName." (Already invited)";
		$onClick = "";
	}//elseif
	else{
		$groupListTitle = "<img src='../../pics/checkmark1.png' id='check-mark".$groupID."' class='".$class."'> ".$groupName;
		$onClick = "selectGroup(".$groupID.")";
	}//else
	
	$return['groupInfo'] .= "
	<div class='group-inline'>
	<div class='group-list-title'>".$groupListTitle."</div>
	<div class='group-img-div' style='background-image:url(".$path.");' onClick='".$onClick."'></div>
	</div>
	";
	
}//while

$return['done'] = 'done';
echo json_encode($return);
?>