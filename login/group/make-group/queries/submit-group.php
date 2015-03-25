<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group_name = cleanInput($_POST['group_name']);
$group_mission = cleanInput($_POST['group_mission']);

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = "wrong z";
	echo json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

$query = mysql_query("SELECT * FROM groups WHERE group_name='$group_name' AND created_by='$email'");

$numrows = mysql_num_rows($query);

if($numrows!=0){
	$return['error'] = true;
	echo json_encode($return);
	return;
}//if

$date = date('Y-m-d');
$time = time();

//make image folder name
while(true){
	$folder_name = rand(1,2000).rand(1,2000);
	$query = mysql_query("SELECT * FROM groups WHERE image_folder='$folder_name'");
	$numrows = mysql_num_rows($query);
	if($numrows==0) break;
}//while

mkdir("../../pics/".$folder_name);

//make group id
while(true){
	$groupID = rand(1,2000).rand(1,2000);
	$query = mysql_query("SELECT * FROM groups WHERE group_id='$groupID'");
	$numrows = mysql_num_rows($query);
	if($numrows==0) break;
}//while


mysql_query("INSERT INTO groups VALUES('','$group_name','$groupID','$email','$group_mission','$folder_name','','','','','$date','$time')");

mysql_query("INSERT INTO group_members VALUES ('','$groupID','$email','')");

$query = mysql_query("SELECT * FROM groups WHERE group_name='$group_name' AND created_by='$email'");

$get = mysql_fetch_assoc($query);

$id = $get['id'];

$return['groupID'] = $groupID;

$return['error'] = false;
echo json_encode($return);

?>