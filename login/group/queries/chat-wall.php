<?php
include('../../../connect/db-connect.php');

$message = cleanInput($_POST['message']);
$group = cleanInput($_POST['group']);

$loginID = cleanInput($_POST['z']);

include('../../../connect/members.php');

//get email and name 
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
$name = $get['name'];

if(!$message || !$name || !$email) return;

//make sure user is part of group
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved!='no'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//create group emails
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND approved!='no'");
while($get_array = mysql_fetch_array($query)){
	$group_emails .= "---".cleanInput($get_array['email'])."---";
}//while

$date = date('Y-m-d');

$time = time();

$message = nl2br(trim($message));

mysql_query("INSERT INTO posts VALUES('','$name','','$email','$group_emails','$group_emails','$message','','','$group','$date','$time','true')");

//verify email was submitted succesfully
$query = mysql_query("SELECT * FROM posts WHERE email='$email' AND time='$time' AND post='$message'");
$numrows = mysql_num_rows($query);
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