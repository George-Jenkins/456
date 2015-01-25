<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group']);

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){

	$return['error'] = 'wrong z'; 
	echo json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//set time zone
$query = mysql_query("SELECT * FROM account_settings WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$timezone = $get['timezone'];
date_default_timezone_set($timezone);

//get image folder name and creators name
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
$get = mysql_fetch_assoc($query);
$creatorEmail = $get['created_by'];
$query = mysql_query("SELECT * FROM members WHERE email='$creatorEmail'");
$get = mysql_fetch_assoc($query);
$creatorName = $get['name'];
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group' AND created_by='$creatorEmail'");

//if user didn't create group or group doesn't exist stop
$numrows = mysql_num_rows($query);
if($numrows==0){
	
	return;
}//if 

$get = mysql_fetch_assoc($query);

$folder = $get['image_folder'];

//get group name
$group_name = $get['group_name'];

//get group img
$group_img = $get['group_img'];
$path1 = "pics/".$folder."/".$group_img;
if(!$group_img) $path1 = "../../pics/nightclub3.jpg";

//get background
$background = $get['background_img'];
$path2 = "pics/".$folder."/".$background;
if(!$background) $path2 = "../../pics/nightclub.jpg";

//get mission
$mission = $get['mission'];
if(!$mission) $mission = 'No mission specified.';

//get group members
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND approved!='no'");


while($get_array = mysql_fetch_array($query)){
	
	$memberEmail = $get_array['email'];
	
	//get name and gender
	$query2 = mysql_query("SELECT * FROM members WHERE email='$memberEmail'");
	$get2 = mysql_fetch_assoc($query2);
	$name = $get2['name'];
	$gender = $get2['gender'];
	$memberID = $get2['id'];
	
	//get img folder
	$query2 = mysql_query("SELECT * FROM profile_images WHERE email='$memberEmail'");
	$get2 = mysql_fetch_assoc($query2);
	$folder_name = $get2['folder_name'];
	
	//get profile img
	$query2 = mysql_query("SELECT * FROM profile_picture WHERE email='$memberEmail'");
	$get2 = mysql_fetch_assoc($query2);
	$profile_img = $get2['img'];
	
	$profile_path = "../profile/pics/".$folder_name."/".$profile_img;
	if(!$profile_img) $profile_path = "../profile/pics/".$gender."-head.png";
	
	//determine link to profile
	if($memberEmail == $email) $profile_link = "../profile/profile.html";
	else $profile_link = "../profile/profile-view.html?".$memberID;
	
	//determine if I need to abbreviate name
	$presentableName = $name;
	$chars = strlen($name);
	if($chars>=16){
		$presentableName = substr($name,0,13).'...';
	}//if
	
	$members .= "<div class='members-list'>
	<a href='".$profile_link."' title='".$name."'>
	<div class='member-name buttonLink'>".$presentableName."</div>
	</a>
	
	<a href='".$profile_link."' title='".$name."'>
	<div style='background-image:url(".$profile_path.");' class='members-img'></div>
	</a>
	
	</div>
	";
	
}//while

//get any activities this group is invited to
$query = mysql_query("SELECT * FROM event_invited_groups WHERE group_id='$group'");
$x = 0;
while($get_array = mysql_fetch_array($query)){
	
	$event_id = $get_array['event_id'];
	$query2 = mysql_query("SELECT * FROM events WHERE event_id='$event_id'");
	$get2 = mysql_fetch_assoc($query2);
	$eventName = $get2['event_name'];
	$eventImage = $get2['image'];
	$creator = $get2['email'];
	$endDate = $get2['end_date'];
	$dayAfter = date('Y-m-d',strtotime($endDate.'+1 day'));
	$today = date('Y-m-d');
	//get creators profile folder
	$query2 = mysql_query("SELECT * FROM profile_images WHERE email='$creator'");
	$get2 = mysql_fetch_assoc($query2);
	$folder = $get2['folder_name'];
	
	
	if($eventImage) $path = "../profile/pics/".$folder."/".$eventImage;
	else $path = "../../pics/nightclub7.jpg";
	
	//determine the link to the event
	if($creator==$email) $link = "../events/event.html?".$event_id;
	else $link = "../events/event-view.html?".$event_id; 
	
	//determine if I need to shorten name
	$presentableName = $eventName;
	$chars = strlen($eventName);
	if($chars>=16){
		$presentableName = substr($presentableName,0,13).'...';
	}//if
	
	if($dayAfter>=$today){
		 $return['events'] .= "
	<div class='group-event-div event-inline'>
	<a href='".$link."' title='".$eventName."'><div class='group-event-title functionLink buttonLink'>".$presentableName."</div></a>
	<a href='".$link."' title='".$eventName."'><div class='group-event-img' style='background-image:url(".$path.");'></div></a>
	</div>
	";
	$x++;
	}//if today isn't greater than day before next day
}//while

//make sure user is on the right page
$query = mysql_query("SELECT * FROM group_members WHERE email='$email' AND group_id='$group' AND approved!='no'");	
$numrows = mysql_num_rows($query);	
if($numrows!=0)	$return['userType'] = 'member';//if user is member
else $return['userType'] = 'not member';//if user is non member
if($creatorEmail==$email) $return['userType'] = 'creator';//if user is creator
	
$return['numberOfEvents'] = $x;

if(!$return['events']) $return['events'] = 'Currently no activities planned';

//see if user applied to this group
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved='no'");
$numrows = mysql_num_rows($query);
if($numrows!=0) $return['pending_approval'] = true;

$return['creatorName'] = $creatorName;
$return['groupName'] = $group_name;
$return['img1'] = $path1;
$return['img2'] = $path2;
$return['mission'] = $mission;
$return['members'] = $members;
echo json_encode($return);

?>