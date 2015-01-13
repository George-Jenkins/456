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
$query = mysql_query("SELECT * FROM profile_background_img WHERE email='$email'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	
	$profile_background = 'profile-background.jpg';
	$folder='';
}//if
else
{
$get = mysql_fetch_assoc($query);

$profile_background = $get['img'];
}

$return['profile_background'] = 'pics/'.$folder.$profile_background;

echo json_encode($return);

?>