<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$imagePosition = cleanInput($_POST['imagePosition']);

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0) return;

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//get image folder
$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$folderName = $get['folder_name'];
$return['folderName'] = $folderName;

if($imagePosition=='background'){

//get image 
$query = mysql_query("SELECT * FROM profile_background_img WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$currentImage = $get['img'];
$return['currentImage'] = $currentImage;
echo json_encode($return);
return;
}//if

if($imagePosition=='cover'){

//get image 
$query = mysql_query("SELECT * FROM profile_cover_photo WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$currentImage = $get['img'];
$return['currentImage'] = $currentImage;
echo json_encode($return);
return;
}//if

if($imagePosition=='profile'){

//get image 
$query = mysql_query("SELECT * FROM profile_picture WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$currentImage = $get['img'];
$return['currentImage'] = $currentImage;
echo json_encode($return);
return;
}

?>
