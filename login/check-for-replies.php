<?php
include('../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$email = cleanInput($_POST['email']);

include('../connect/members.php');

/*
//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];
*/

if(!$email) return;

$query = mysql_query("SELECT * FROM posts WHERE email='$email'");

$replies;

$numberOfReplies = 0;//this is for mobile 
$numberOfPosts = 0;//this is for mobile 

$replies = false;

while($get_array = mysql_fetch_array($query)){
	
	$id = $get_array['id'];
	$query2 = mysql_query("SELECT * FROM posts WHERE reply_id='$id' AND email!='$email' AND checked='false'");
	
	$numrows2 = mysql_num_rows($query2);
	
	if($numrows2!=0){
		$numberOfReplies++;
		$replies = true;
	}//if
}//while

//I'll also get unchecked posts (for mobile)
$query = mysql_query("SELECT * FROM group_members WHERE email='$email'");
while($get_array = mysql_fetch_array($query)){
	
	$group = $get_array['group_id'];
	$query2 = mysql_query("SELECT * FROM posts WHERE group_id='$group' AND group_emails LIKE '%---$email---%'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0) $numberOfPosts++;
	
}//while


$return['replies'] = $replies;
$return['numberOfReplies'] = $numberOfReplies;
$return['numberOfPosts'] = $numberOfPosts;

echo json_encode($return);

?>