<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../../connect/members.php');

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//get email
$get = mysql_fetch_assoc($query);
$email = $get['email'];

//set time zone
$query = mysql_query("SELECT * FROM account_settings WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$timezone = $get['timezone'];
date_default_timezone_set($timezone);

//get events user created
$query = mysql_query("SELECT * FROM events WHERE email='$email'");

while($get_array = mysql_fetch_array($query)){
	
	$eventName = $get_array['event_name'];
	$image = $get_array['image'];
	$eventID = $get_array['event_id'];
	
	$query2 = mysql_query("SELECT * FROM profile_images WHERE email='$email'");
	$get2 = mysql_fetch_assoc($query2);
	$folder = $get2['folder_name'];
	
	if($image) $path = "../profile/pics/".$folder."/".$image;
	else $path = "../../pics/nightclub7.jpg";
	
	$fullEventName = $eventName.' (Yours)';
	$eventName = $fullEventName;
	
	$chars = strlen($eventName);//count chars in name
	
	if($chars>=16){//abbreviate if it's too long
		$eventName = substr($eventName,0,13).'...';
	}//if
	
	$return['Events'] .= "
	<div class='event-inline'>
	<a href='event.html?".$eventID."' title='".$fullEventName."'><div class='event-name functionLink buttonLink'>".$eventName."</div></a>
	<a href='event.html?".$eventID."' title='".$fullEventName."'><div class='event-image-div' style='background-image:url(".$path.");'></div></a>
	</div>
	";
	
}//while

//get events user was invited to
$query = mysql_query("SELECT * FROM group_members WHERE email='$email' AND approved!='no'");

while($get_array = mysql_fetch_array($query)){
	
	$group = $get_array['group_id'];
	
	$query2 = mysql_query("SELECT * FROM event_invited_groups WHERE group_id='$group'");
	
	while($get_array2 = mysql_fetch_array($query2)){
		
		$event_id = $get_array2['event_id'];
		
		$query3 = mysql_query("SELECT * FROM events WHERE event_id='$event_id' AND email!='$email' AND active='true'");
		$get3 = mysql_fetch_assoc($query3);
		$creatorEmail = $get3['email'];
		$eventName = $get3['event_name'];
		$image = $get3['image'];
		$endDate = $get3['end_date'];
		$dayAfter = date('Y-m-d',strtotime($endDate."+1 day"));
		$today = date("Y-m-d");
		
		$query3 = mysql_query("SELECT * FROM profile_images WHERE email='$creatorEmail'");
		$get3 = mysql_fetch_assoc($query3);
		$folder = $get3['folder_name'];
	
		if($image) $path = "../profile/pics/".$folder."/".$image;
		else $path = "../../pics/nightclub7.jpg";
		
		$fullEventName = $eventName;
		$eventName = $fullEventName;
	
		$chars = strlen($eventName);//count chars in name
	
		if($chars>=19){//abbreviate if it's too long
		$eventName = substr($eventName,0,16).'...';
		}//if
		
		if($eventName && $today>=$dayAfter) $return['Events'] .= "
		<div class='event-inline'>
		<a href='event-view.html?".$event_id."' title='".$fullEventName."'><div class='event-name functionLink buttonLink'>".$eventName."</div></a>
		<a href='event-view.html?".$event_id."' title='".$fullEventName."'><div class='event-image-div' style='background-image:url(".$path.");'></div></a>
		</div>
		";
		
	}//while
	
}//while

if(!$return['Events']) $return['Events'] = 'Currently no activities';

$return['done'] = 'done';
echo json_encode($return);

?>