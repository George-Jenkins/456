<?php
//this document is used by view-specific-replies.js
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group = $_POST['group'];

include('../../../../connect/members.php');

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

//make sure this user isn't who created the group
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email' AND group_id='$group'");
$numrows = mysql_num_rows($query);
if($numrows!=0) return;

//make sure user belongs to this group
$query = mysql_query("SELECT * FROM group_members WHERE email='$email' AND group_id='$group'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

mysql_query("DELETE FROM group_members WHERE email='$email' AND group_id='$group'");
mysql_query("DELETE FROM group_invitations WHERE email='$email' AND group_code='$group'");

//delete group photos uploaded by user
$query = mysql_query("SELECT * FROM group_members WHERE email='$email'");
while($get_array = mysql_fetch_array($query)){
	
	$group = $get_array['group_id'];
	//get group's image folder
	$query2 = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
	$get2 = mysql_fetch_assoc($query2);
	$folder = $get2['image_folder'];
	//get images user uploaded
	$query2 = mysql_query("SELECT * FROM group_album WHERE email='$email' AND group_id='$group'");
	while($get_array2 = mysql_fetch_array($query2)){
		
		$image = $get_array2['image'];
		unlink("../../pics/".$folder."/".$image);
		unlink("../../pics/".$folder."/small-".$image);
		mysql_query("DELETE FROM group_album WHERE group_id='$group' AND email='$email'");
		
	}//while
	
}//while

//handle deleting user from posts
	//delete posts that were a reply to a reply to the user
	$query = mysql_query("SELECT * FROM posts WHERE recipient_email='$email' AND group_id='$group'");
	while($get_array = mysql_fetch_array($query)){
	$postID = $get_array['id'];
	mysql_query("DELETE FROM posts WHERE reply_id='$postID' AND group_id='$group'");
	}//while
	//delete posts that were a reply to user's posts
	$query = mysql_query("SELECT * FROM posts WHERE email='$email' AND group_id='$group'");
	while($get_array = mysql_fetch_array($query)){
	$postID = $get_array['id'];
	mysql_query("DELETE FROM posts WHERE originalPostID='$postID' AND group_id='$group'");
	}//while
	mysql_query("DELETE FROM posts WHERE email='$email' AND group_id='$group'");
	mysql_query("DELETE FROM posts WHERE recipient_email='$email' AND group_id='$group'");
	//remove user's email from posts group emails and group emails for pulse
	$query = mysql_query("SELECT * FROM posts WHERE group_id='$group'");
	while($get_array = mysql_fetch_array($query)){
	$group_emails = $get_array['group_emails'];
	$group_emails_for_pulse = $get_array['group_emails_for_pulse'];
	$id = $get_array['id'];
	$group_emails = str_ireplace('---'.$email.'---','',$group_emails);
	$group_emails_for_pulse = str_ireplace('---'.$email.'---','',$group_emails_for_pulse);
	mysql_query("UPDATE posts SET group_emails='$group_emails', group_emails_for_pulse='$group_emails_for_pulse' WHERE group_id='$group' AND id='$id'");
	}//while


$return['done'] = 'done';
echo json_encode($return);

?>