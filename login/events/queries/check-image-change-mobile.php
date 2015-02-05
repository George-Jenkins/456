<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$imagePosition = cleanInput($_POST['imagePosition']);
$event = cleanInput($_POST['event_id']);

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

if($imagePosition=='event'){

//get image 
$query = mysql_query("SELECT * FROM events WHERE email='$email' AND event_id='$event'");
$get = mysql_fetch_assoc($query);
$currentImage = $get['image'];
$return['currentImage'] = $currentImage;
echo json_encode($return);
return;
}//if



?>
