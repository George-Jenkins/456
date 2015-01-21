<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$newEmail = cleanInput($_POST['change-email']);
$emailPost = cleanInput($_POST['posts-email']);
$emailReply = cleanInput($_POST['reply-email']);

include('../../../connect/members.php');

//get email and name 
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'wrong z';
	echo json_encode($return);
	 return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];
$name = $get['name'];

//update email_posts
mysql_query("UPDATE account_settings SET email_posts='$emailPost' WHERE email='$email'");

//update email_reply
mysql_query("UPDATE account_settings SET email_replies='$emailReply' WHERE email='$email'");

//change email
if($email!=$newEmail){

//get groups this user is in
$query = mysql_query("SELECT * FROM group_members WHERE email='$email'");
while($get_array = mysql_fetch_array($query)){
	
	$group = $get_array['group_id'];
	
	//change the group_emails
	$query2 = mysql_query("SELECT * FROM posts WHERE group_id='$group' AND group_emails LIKE '%---$email---%'");
	while($get_array2 = mysql_fetch_array($query2)){
		$id = $get_array2['id'];
		$groupEmails = $get_array2['group_emails'];
		$groupEmails = str_ireplace("---".$email."---","---".$newEmail."---",$groupEmails);
		mysql_query("UPDATE posts SET group_emails='$groupEmails' WHERE id='$id'");
	
	}//while
	
	$query2 = mysql_query("SELECT * FROM posts WHERE group_id='$group' AND group_emails_for_pulse LIKE '%---$email---%'");
	while($get_array2 = mysql_fetch_array($query2)){
		$id = $get_array2['id'];
		$groupEmailsForPulse = $get_array2['group_emails_for_pulse'];
		$groupEmailsForPulse = str_ireplace("---".$email."---","---".$newEmail."---",$groupEmailsForPulse);
		mysql_query("UPDATE posts SET group_emails_for_pulse='$groupEmailsForPulse' WHERE id='$id'");
	}//while
	
}//while

//change email
mysql_query("UPDATE account_settings SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE events SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE event_attendees SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE event_invited_groups SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE groups SET created_by='$newEmail' WHERE created_by='$email'");
mysql_query("UPDATE group_invitations SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE group_members SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE group_members_invited SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE login_id SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE members SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE notifications SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE posts SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE posts SET recipient_email='$newEmail' WHERE recipient_email='$email'");
mysql_query("UPDATE profile_background_img SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE profile_cover_photo SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE profile_going_out SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE profile_hometown SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE profile_images SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE profile_picture SET email='$newEmail' WHERE email='$email'");
mysql_query("UPDATE s SET email='$newEmail' WHERE email='$email'");

}//if emails are different

$return['done'] = 'done';
echo json_encode($return);	



?>