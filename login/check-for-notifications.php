<?php
include('../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];

if(!$email) return;

$notification;

$numberOfNotifications=0;//this is used for mobile notifications

//check for pending group members
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email'");
while($get_array = mysql_fetch_array($query)){
	
	$groupID = $get_array['group_id'];
	
	$query2 = mysql_query("SELECT * FROM group_members WHERE group_id='$groupID' AND approved='no'");
	
	$numrows2 = mysql_num_rows($query2);
	
	if($numrows2!=0){
		$notification = true;
		$numberOfNotifications+=$numrows2;
	}//if
}//while

//check for general notifications
$query = mysql_query("SELECT * FROM notifications WHERE email='$email'");
$numrows = mysql_num_rows($query);

if($numrows!=0) $notification = true; 
$numberOfNotifications+=$numrows;


$return['notifications'] = $notification;
$return['numberOfNotifications'] = $numberOfNotifications;

echo json_encode($return);

?>