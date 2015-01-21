<?php
include('../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$email = cleanInput($_POST['email']);

include('../connect/members.php');

$query = mysql_query("SELECT * FROM posts WHERE email='$email'");

$replies;

$numberOfReplies;//this is used for mobile applications

while($get_array = mysql_fetch_array($query)){
	
	$id = $get_array['id'];
	$query2 = mysql_query("SELECT * FROM posts WHERE reply_id='$id' AND email!='$email' AND checked='false'");
	
	$numrows2 = mysql_num_rows($query2);
	
	if($numrows2!=0){
		$replies = true;
		$numberOfReplies++;
	}//if
}//while

//I'll also use this document to check for any posts that are unread. This will be used in mobile notifications
$query = mysql_query("SELECT * FROM group_members WHERE email='$email' AND approved!='no'");
$unreadPosts=0;
while($get_array = mysql_fetch_array($query)){
	$group = $get_array['group_id'];
	$query2 = mysql_query("SELECT * FROM posts WHERE group_id='$group' AND group_emails LIKE '%---$email---%'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0) $unreadPosts++;
}//while

$return['replies'] = $replies;
$return['numberOfReplies'] = $numberOfReplies;
$return['unreadPosts'] = $unreadPosts;

echo json_encode($return);

?>