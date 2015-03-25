<?php
include('../../../connect/db-connect.php');

$reply = cleanInput($_POST['reply']);

$group = cleanInput($_POST['group']);
$groupIDPhoto = cleanInput($_POST['groupIDPhoto']);

$replyID = cleanInput($_POST['id']);
$replyTime = cleanInput($_POST['time']);//I may not use this
$loginID = cleanInput($_POST['z']);

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
$email = cleanInput($get['email']);
$name = $get['name'];

if(!$reply || !$replyID || !$replyTime || !$name) return;

//make sure user is part of group
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved!='no'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//create group emails
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND approved!='no'");
while($get_array = mysql_fetch_array($query)){
	$group_emails .= "---".cleanInput($get_array['email'])."---";
}//while

$query = mysql_query("SELECT * FROM posts WHERE id='$replyID'");

$get = mysql_fetch_assoc($query);

$posterEmail = cleanInput($get['email']);

$originalPostID = $get['originalPostID'];

if($originalPostID==0) $originalPostID = $replyID;

$date = date('Y-m-d');

$time = time();

$reply = nl2br(trim($reply));

mysql_query("INSERT INTO posts VALUES('','$name','$posterEmail','$email','$group_emails','$group_emails','$reply','','$replyID','$originalPostID','$group','$groupIDPhoto','$date','$time','false')");

//verify email was submitted succesfully
$query = mysql_query("SELECT * FROM posts WHERE email='$email' AND time='$time' AND post='$reply'");
$numrows = mysql_num_rows($query);
//get the id
$get = mysql_fetch_assoc($query);
$id = $get['id'];
$return['replyID'] = $id;

if($numrows!=0){
$return['error'] = 'false';
echo json_encode($return);
}//if
else
{
$return['error'] = 'true';
echo json_encode($return);	
}//else
?>