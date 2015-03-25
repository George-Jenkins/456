<?php
//this document is used by view-specific-replies.js
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$postID = $_POST['id'];

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);

if($numrows==0){
	
	$return['error'] = 'wrong z';
	echo json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure the right user is updating the database. Their email will validate this
$query = mysql_query("SELECT * FROM posts WHERE id='$postID'");
$get = mysql_fetch_assoc($query);
$dbemail = $get['email'];
if($email!=$dbemail) return;

//update database
mysql_query("UPDATE posts SET checked='true' WHERE originalPostID='$postID'");

$return['done'] = 'done';
echo json_encode($return);

?>