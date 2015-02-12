<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$id = cleanInput($_POST['id']);
$time = cleanInput($_POST['time']);

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

//make sure user is part of group
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved!='no'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

mysql_query("DELETE FROM posts WHERE id='$id' AND email='$email' AND time='$time'");
mysql_query("DELETE FROM posts WHERE originalPostID ='$id'");
mysql_query("DELETE FROM posts WHERE reply_id ='$id'");

$return['done'] = 'done';
echo json_encode($return);

?>