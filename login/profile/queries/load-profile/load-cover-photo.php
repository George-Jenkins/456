<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$cleanID = cleanInput($_POST['cleanID']);

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];

if($cleanID){
	$query = mysql_query("SELECT * FROM members WHERE id='$cleanID'");
	$get = mysql_fetch_assoc($query);
	$email = $get['email'];
}//if cleanID

//get name
$query = mysql_query("SELECT * FROM members WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$return['name'] = $get['name'];

//get folder
$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");

$get = mysql_fetch_assoc($query);

$folder = $get['folder_name'];

//get image
$query = mysql_query("SELECT * FROM profile_cover_photo WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$image = $get['img'];

if($image) $return['path'] = "pics/".$folder."/".$image;

echo json_encode($return);

?>