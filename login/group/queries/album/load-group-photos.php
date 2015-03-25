<?php
include('../../../../connect/db-connect.php');

$group = cleanInput($_POST['groupID']);
$loginID = cleanInput($_POST['z']);
$loop = $_POST['loop'];
$limit = $_POST['limit'];

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

//verify group exists
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'no group';
	echo json_encode($return);
	return;
}//if

//see if user is in group
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved!='no'");
$numrows = mysql_num_rows($query);
if($numrows!=0) $groupMember = true;
else $groupMember = false;
$return['groupMember'] = $groupMember;

//get group name and image
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
$get = mysql_fetch_assoc($query);
$folder = $get['image_folder'];
$groupImage = $get['group_img'];
$groupImgPath = "pics/".$folder."/".$groupImage;
if(!$groupImage) $groupImgPath = "../../pics/nightclub3.jpg";
$return['groupImage'] = $groupImgPath;
$return['groupName'] = $get['group_name'];
$creatorEmail = $get['created_by'];

$query = mysql_query("SELECT * FROM group_album WHERE group_id='$group' ORDER BY time DESC");

$x = 0;

while($get_array = mysql_fetch_array($query)){
	
	if($loop==0 && $x<$limit || $loop==1 && $x>=$limit){//the limit for first loop is 10 and the start of second loop is 10 or above
	
	$memberEmail = $get_array['email'];
	$image = $get_array['image'];
	$id = $get_array['id'];
	$caption = $get_array['caption'];
	//get name of person who uploaded
	$query2 = mysql_query("SELECT * FROM members WHERE email='$memberEmail'");
	$get2 = mysql_fetch_assoc($query2);
	$name = ucwords($get2['name']);
	$profileID = $get2['id'];	
	
	$imagePath = "pics/".$folder."/small-".$image;
	$functionImagePath = "pics/".$folder."/".$image;
	//determine if user can delete photo
	$deleteable = 'false';
	if($creatorEmail == $email || $memberEmail == $email) $deleteable = 'true';
	//determine if user can edit caption
	$editCaption = 'false';
	if($memberEmail == $email) $editCaption = 'true';
	//create link to the profile of person who uploaded photo
	$linkToProfile = "<a href='../profile/profile-view.html?".$profileID."'>".$name."</a>";	
	if($memberEmail == $email) $linkToProfile = "<a href='../profile/profile.html'>".$name."</a>";	
	
	$hideStyle = '';
	if($x>=$limit) $hideStyle = 'display:none;';
	
	$imagesDiv .= "<div id='".$functionImagePath."' id2='".$id."' showMoreID='".$x."' onClick=\"showImage('".$functionImagePath."')\" class='group-photos-inline' deletable='".$deleteable."'
	editCaption='".$editCaption."' style='background-image:url(".$imagePath.");".$hideStyle."'></div>";
	$uploadedByDivs .= "<div uploadedByID='".$id."' class='uploaded-by hide'>Uploaded by ".$linkToProfile."</div>";
	$captionDivs .= "<div captionID='".$id."' class='caption hide'>".$caption."</div>";
	
	}//if
	
	$return['loops'] = $x;//don't confuse with "loop"
	
	$x++;
	
	
	if($loop==0 && $x==$limit) break;//stop on first loop here
	
}//while
$return['loop'] = $loop;
$return['images'] = $imagesDiv;
$return['uploadedByDivs'] = $uploadedByDivs;
$return['captionDivs'] = $captionDivs;

//determine link to group
$linkToGroup = "group-view.html?".$group;
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved!='no'");
$numrows = mysql_num_rows($query);
if($numrows!=0) $linkToGroup = "group-member.html?".$group;
if($creatorEmail == $email) $linkToGroup = "group.html?".$group;

$return['linkToGroup'] = $linkToGroup;



echo json_encode($return);

?>