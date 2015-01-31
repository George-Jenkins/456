<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

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

//handle deleting user from posts
	//delete posts that were a reply to a reply to the user
	$query = mysql_query("SELECT * FROM posts WHERE recipient_email='$email'");
	while($get_array = mysql_fetch_array($query)){
	$postID = $get_array['id'];
	mysql_query("DELETE FROM posts WHERE reply_id='$postID'");
	}//while
	$query = mysql_query("SELECT * FROM posts WHERE email='$email'");
	while($get_array = mysql_fetch_array($query)){
	$postID = $get_array['id'];
	mysql_query("DELETE FROM posts WHERE originalPostID='$postID'");
	}//while
	mysql_query("DELETE FROM posts WHERE email='$email'");
	mysql_query("DELETE FROM posts WHERE recipient_email='$email'");
	//remove user's email from posts group emails and group emails for pulse
	$query = mysql_query("SELECT * FROM posts");
	while($get_array = mysql_fetch_array($query)){
	$group_emails = $get_array['group_emails'];
	$group_emails_for_pulse = $get_array['group_emails_for_pulse'];
	$id = $get_array['id'];
	$group_emails = str_ireplace('---'.$email.'---','',$group_emails);
	$group_emails_for_pulse = str_ireplace('---'.$email.'---','',$group_emails_for_pulse);
	mysql_query("UPDATE posts SET group_emails='$group_emails', group_emails_for_pulse='$group_emails_for_pulse' WHERE id='$id'");
	}//while

//remove profile image folder
$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$folder = $get['folder_name'];
function removeDirectory($path) {
 	$files = glob($path . '/*');
	foreach ($files as $file) {
		is_dir($file) ? removeDirectory($file) : unlink($file);
	}
	rmdir($path);
 	
}//for each
$path = '../../profile/pics/'.$folder;
removeDirectory($path);

//remove group images
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email'");
while($get_array = mysql_fetch_array($query)){
	
$folder = $get_array['image_folder'];

$path = '../../group/pics/'.$folder;
removeDirectory($path);
}//while

//delete all groups user created
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email'");
$get = mysql_fetch_assoc($query);
$group = $get['group_id'];
mysql_query("DELETE FROM groups WHERE group_id='$group'");
mysql_query("DELETE FROM group_invitations WHERE group_code='$group'");
mysql_query("DELETE FROM group_members WHERE group_id='$group'");
mysql_query("DELETE FROM group_members_invited WHERE group_id='$group'");

//delete from other DBs
mysql_query("DELETE FROM account_settings WHERE email='$email'");
mysql_query("DELETE FROM device_notifications WHERE email='$email'");
mysql_query("DELETE FROM events WHERE email='$email'");
mysql_query("DELETE FROM event_attendees WHERE email='$email'");
mysql_query("DELETE FROM event_invited_groups WHERE email='$email'");
mysql_query("DELETE FROM groups WHERE created_by='$email'");
mysql_query("DELETE FROM group_invitations WHERE email='$email'");
mysql_query("DELETE FROM group_members WHERE email='$email'");
mysql_query("DELETE FROM group_members_invited WHERE email='$email'");
mysql_query("DELETE FROM login_id WHERE email='$email'");
mysql_query("DELETE FROM members WHERE email='$email'");
mysql_query("DELETE FROM profile_background_img WHERE email='$email'");
mysql_query("DELETE FROM profile_cover_photo WHERE email='$email'");
mysql_query("DELETE FROM profile_going_out WHERE email='$email'");
mysql_query("DELETE FROM profile_hometown WHERE email='$email'");
mysql_query("DELETE FROM profile_images WHERE email='$email'");
mysql_query("DELETE FROM profile_picture WHERE email='$email'");
mysql_query("DELETE FROM s WHERE email='$email'");

$return['done'] = 'done';
echo json_encode($return);	



?>