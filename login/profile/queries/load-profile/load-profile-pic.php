<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];

//get image directory
$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");

$get = mysql_fetch_assoc($query);

$folder = $get['folder_name'].'/';

$return['folder'] = $folder;

//get profile pic
$query = mysql_query("SELECT * FROM profile_picture WHERE email='$email'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	
	$query2 = mysql_query("SELECT * FROM members WHERE email='$email'");
	$get2 = mysql_fetch_assoc($query2);
	$gender = $get2['gender'];
	if($gender=='male') $profile_pic = 'male-head.png';
	else $profile_pic = 'female-head.png';
	$folder='';
}//if
else
{
$get = mysql_fetch_assoc($query);

$profile_pic = $get['img'];
}

$return['profile_pic'] = 'pics/'.$folder.$profile_pic;

echo json_encode($return);

?>