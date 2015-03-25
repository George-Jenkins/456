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

//delete images
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
$get = mysql_fetch_assoc($query);
$folder = $get['image_folder'];
function removeDirectory($path) {
 	$files = glob($path . '/*');
	foreach ($files as $file) {
		is_dir($file) ? removeDirectory($file) : unlink($file);
	}
	rmdir($path);
 	
}//for each
$path = '../../pics/'.$folder;
removeDirectory($path);

mysql_query("DELETE FROM groups WHERE group_id='$group'");
mysql_query("DELETE FROM group_invitations WHERE group_code='$group'");
mysql_query("DELETE FROM group_members WHERE group_id='$group'");
mysql_query("DELETE FROM group_album WHERE group_id='$group'");
mysql_query("DELETE FROM posts WHERE group_id='$group'");

$return['done'] = 'done';

echo json_encode($return);

?>