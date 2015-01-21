<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$cleanID = cleanInput($_POST['cleanID']);

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];

$originalEmail = $email;

if($cleanID){
	$query = mysql_query("SELECT * FROM members WHERE id='$cleanID'");
	$get = mysql_fetch_assoc($query);
	$email = $get['email'];
	
	//determine if user is the creator of this profile. This is used in load-profile-info-view.js
	if($email==$originalEmail){
	$return['creator'] = 'creator';
	echo json_encode($return);
	return;
	}//if
}//if cleanID

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

//cover photo//
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

if($image) $return['cover_path'] = "pics/".$folder."/".$image;

//background//
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

//get hometown//
$query = mysql_query("SELECT * FROM profile_hometown WHERE email='$email'");

$get = mysql_fetch_assoc($query);

$state = $get['state'];
if($state) $state = ','.$state;

$city = $get['city'];

$return['hometown'] = $city.' '.$state;

//get going out info
$query = mysql_query("SELECT * FROM profile_going_out WHERE email='$email'");

$get = mysql_fetch_assoc($query);

$input = $get['input'];

$return['going_out'] = nl2br($input);

//get entourages//
$query = mysql_query("SELECT * FROM group_members WHERE email='$email'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	
	$return['list'] = 'Currently no entourages';
	
}//if

while($get_array = mysql_fetch_array($query)){
	
	$group_id = $get_array['group_id'];
	$approved = $get_array['approved'];
	
	$pending = '';
	$pendingTitle = '';
	if($approved=='no'){
		$pending = '<span class="red">(Pending)</span>';
		$pendingTitle = ' (Pending)';
	}
	$query2 = mysql_query("SELECT * FROM groups WHERE group_id='$group_id'");
	
	$get = mysql_fetch_assoc($query2);
	
	$full_group_name = $get['group_name'];
	$image_folder = $get['image_folder'];
	$group_image = $get['group_img'];
	
	$image_path = "../group/pics/".$image_folder."/".$group_image."";
	if(!$group_image) $image_path = "../../pics/nightclub3.jpg";
	
	//determine the link to the group
	$link = "../group/group-view.html?".$group_id;//this is the default link
	
	$query2 = mysql_query("SELECT * FROM group_members WHERE email='$originalEmail' AND group_id='$group_id' AND approved!='no'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0) $link = "../group/group-member.html?".$group_id;//this link is for member
	
	$query2 = mysql_query("SELECT * FROM groups WHERE created_by='$originalEmail' AND group_id='$group_id'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0) $link = "../group/group.html?".$group_id;//this link is for creator
	
	
	//determine whether or not I need to abbreviate group name
	$group_name = $full_group_name;
	$chars = strlen($group_name);
	if($chars>=16 && !$pending){
		$group_name = substr($group_name,0,13).'...';
	}//if
	if($chars>=8 && $pending){
		$group_name = substr($group_name,0,13).'...';
		$pending = '';
	}//if
	
	$return['list'] .= "
	<div class='entourage-list'>
	
	<a href='".$link."' title='".$full_group_name.$pendingTitle."'>
		<div class='group-name buttonLink'><span id='groupID".$group_id."' class='ent-alert'></span>
	".$group_name." ".$pending."
		</div>
	</a>
	<a href='".$link."' title='".$full_group_name.$pendingTitle."'>
		<div style='background-image:url(".$image_path.")' class='entourage-list-img'></div>
	</a>
	
	</div>
	";
	
}//while

echo json_encode($return);

?>