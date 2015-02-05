<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$imagePosition = cleanInput($_POST['imagePosition']);
$group = cleanInput($_POST['group_id']);

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0) return;

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//get image folder
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email' AND group_id='$group'");
$get = mysql_fetch_assoc($query);
$folderName = $get['image_folder'];
$return['folderName'] = $folderName;

if($imagePosition=='background'){

//get image 
$currentImage = $get['background_img'];
$return['currentImage'] = $currentImage;
echo json_encode($return);
return;
}//if

if($imagePosition=='group'){

//get image 
$currentImage = $get['group_img'];
$return['currentImage'] = $currentImage;
echo json_encode($return);
return;
}//if



?>
