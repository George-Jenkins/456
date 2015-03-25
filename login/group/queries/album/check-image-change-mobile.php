<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group_id']);
$lastImageDataBaseID = $_POST['lastImageDataBaseID'];

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0) return;

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//get last image added by user
$query = mysql_query("SELECT * FROM group_album WHERE group_id='$group' AND email='$email' AND id>$lastImageDataBaseID ORDER BY id ASC");
$numrows = mysql_num_rows($query);

if($numrows!=0){

$get = mysql_fetch_assoc($query);	
$lastDBID = $get['id'];
$new_name = $get['image'];
//get id of new row
$id = $lastDBID;

//get name of person who uploaded
$query2 = mysql_query("SELECT * FROM members WHERE email='$email'");
$get2 = mysql_fetch_assoc($query2);
$name = ucwords($get2['name']);
//get image folder name
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
$get = mysql_fetch_assoc($query);
$folder = $get['image_folder'];

$imagePath = "pics/".$folder."/small-".$new_name;
$functionImagePath = "pics/".$folder."/".$new_name;
//since this was uploaded by user they can edit it
$deleteable = 'true';
$editCaption = 'true';

$returnImage = "<div id='".$functionImagePath."' id2='".$id."' onClick=showImage('".$functionImagePath."') class='group-photos-inline' deletable='".$deleteable."' editCaption='".$editCaption."' style='background-image:url(".$imagePath.")'></div>";

$linkToProfile = "<a href='../profile/profile.html'>".$name."</a>";	
$uploadedByDiv = "<div uploadedByID='".$id."' class='uploaded-by hide'>Uploaded by ".$linkToProfile."</div>";

$returnCaption = "<div captionID='".$id."' class='caption hide'></div>";

$return['lastDBID'] = $lastDBID;
$return['result'] = $returnImage;
$return['caption'] = $returnCaption;
$return['uploadedBy'] = $uploadedByDiv;
	
echo json_encode($return);	
}//if

?>
