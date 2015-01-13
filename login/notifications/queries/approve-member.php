<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$approveID = $_POST['approve_id'];
$dicision = $_POST['decision'];
$groupID = $_POST['groupID'];

include('../../../connect/members.php');

//get email and name 
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

$numrows = mysql_num_rows($query);
if($numrows==0){
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];
$name = $get['name'];

if(!$email) return;

//make sure group exists and was created by the user
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email' AND group_id='$groupID'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//get member email
$query = mysql_query("SELECT * FROM group_members WHERE id='$approveID'");
$get = mysql_fetch_assoc($query);
$memberEmail = $get['email'];

$query = mysql_query("SELECT * FROM groups WHERE group_id='$groupID'");
$get = mysql_fetch_assoc($query);
$groupName = $get['group_name'];

if($dicision=='approve'){
	
mysql_query("UPDATE group_members SET approved='yes' WHERE id='$approveID'");
mysql_query("DELETE FROM group_members_invited WHERE email='$memberEmail' AND group_id='$groupID'");
$return['msg'] = 'Approved';

	//add this to notifications
	$message = "You are now a member of the entourage ".$groupName;
	$noticeID = "groups".$groupID;
	$time = time();
	mysql_query("INSERT INTO notifications VALUES ('','$message','$memberEmail','$noticeID','$time')");
}//if

if($dicision=='disapprove'){
	
mysql_query("DELETE FROM group_members WHERE id='$approveID'");
mysql_query("DELETE FROM group_members_invited WHERE email='$memberEmail' AND group_id='$groupID'");
$return['msg'] = 'Disapproved';
}//if


$return['error'] = false;
$return['test'] = $memberEmail;
echo json_encode($return);	

?>