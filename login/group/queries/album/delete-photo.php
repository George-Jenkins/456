<?php
include('../../../../connect/db-connect.php');

$imagePath = cleanInput($_POST['imagePath']);
$rowID = cleanInput($_POST['rowID']);
$group = cleanInput($_POST['groupID']);
$loginID = cleanInput($_POST['z']);

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

$imageArray = explode('/',$imagePath);
$image = $imageArray[count($imageArray)-1];

//see if user is creator of group
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email' AND group_id='$group'");
$numrows1 = mysql_num_rows($query);

//see if user uploaded photo
$query = mysql_query("SELECT * FROM group_album WHERE email='$email' AND image='$image'");
$numrows2 = mysql_num_rows($query);

if($numrows1==0 && $numrows2==0){
$return['error'] = 'error';
echo json_encode($return);	
return;	
}//if
	
mysql_query("DELETE FROM group_album WHERE email='$email' AND id='$rowID' AND image='$image'");
unlink('../../'.$imagePath);
$smallImagePath = str_replace($image,'small-'.$image,$imagePath);
unlink('../../'.$smallImagePath);

//delete discussions about photo
$groupIDPhoto = $group.":imageID-".$rowID; 
mysql_query("DELETE FROM posts WHERE group_id='$group' AND group_id_photo='$groupIDPhoto'");

$return['done'] = $image;
echo json_encode($return);
	




?>