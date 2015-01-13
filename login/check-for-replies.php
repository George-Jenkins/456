<?php
include('../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../connect/members.php');

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//get email
$get = mysql_fetch_assoc($query);
$email = $get['email'];

$query = mysql_query("SELECT * FROM posts WHERE email='$email'");

$replies;

while($get_array = mysql_fetch_array($query)){
	
	$id = $get_array['id'];
	
	$query2 = mysql_query("SELECT * FROM posts WHERE reply_id='$id' AND checked='false'");
	
	$numrows2 = mysql_num_rows($query2);
	
	if($numrows2!=0) $replies = true;
	
}//while

$return['replies'] = $replies;

echo json_encode($return);

?>