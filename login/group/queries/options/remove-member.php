<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group']);
$memberID = $_POST['id'];

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0) return;

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure this user created group
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email' AND group_id='$group'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//delete member from DBs
$query = mysql_query("SELECT * FROM group_members WHERE id='$memberID' AND group_id='$group'");
$get = mysql_fetch_assoc($query);
$memberEmail = $get['email'];

//handle deleting user from posts
	//delete posts that were a reply to a reply to the user
	$query = mysql_query("SELECT * FROM posts WHERE recipient_email='$memberEmail' AND group_id='$group'");
	while($get_array = mysql_fetch_array($query)){
	$postID = $get_array['id'];
	mysql_query("DELETE FROM posts WHERE reply_id='$postID' AND group_id='$group'");
	}//while
	//delete posts that were a reply to user's post
	$query = mysql_query("SELECT * FROM posts WHERE email='$memberEmail' AND group_id='$group'");
	while($get_array = mysql_fetch_array($query)){
	$postID = $get_array['id'];
	mysql_query("DELETE FROM posts WHERE originalPostID='$postID' AND group_id='$group'");
	}//while
	mysql_query("DELETE FROM posts WHERE email='$memberEmail' AND group_id='$group'");
	mysql_query("DELETE FROM posts WHERE recipient_email='$memberEmail' AND group_id='$group'");
	//remove user's email from posts group emails and group emails for pulse
	$query = mysql_query("SELECT * FROM posts WHERE group_id='$group'");
	while($get_array = mysql_fetch_array($query)){
	$group_emails = $get_array['group_emails'];
	$group_emails_for_pulse = $get_array['group_emails_for_pulse'];
	$id = $get_array['id'];
	$group_emails = str_ireplace('---'.$memberEmail.'---','',$group_emails);
	$group_emails_for_pulse = str_ireplace('---'.$memberEmail.'---','',$group_emails_for_pulse);
	mysql_query("UPDATE posts SET group_emails='$group_emails', group_emails_for_pulse='$group_emails_for_pulse' WHERE group_id='$group' AND id='$id'");
	}//while


mysql_query("DELETE FROM group_members WHERE id='$memberID' AND group_id='$group'");

echo json_encode($return);

?>