<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'wrong z';
	json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//determine if this is the creator of the event

$query = mysql_query("SELECT * FROM group_members WHERE email='$email' AND approved!='no'");


$x = 0;

while($get_array = mysql_fetch_array($query)){
	
	$groupID = $get_array['group_id'];
	
	$query2 = mysql_query("SELECT * FROM groups WHERE group_id='$groupID'");
	$get2 = mysql_fetch_assoc($query2);
	$groupName = $get2['group_name'];
	$id = $get2['id'];
	$imgFolder = $get2['image_folder'];
	$image = $get2['group_img'];
	if($image) $path = "../group/pics/".$imgFolder."/".$image;
	else $path = "../../pics/nightclub3.jpg";
	
	$titleName = $groupName;
		
	//determine if group name should be shortened
	if(strlen($groupName)>16){
		
	$groupName = substr($groupName,0,13).'...';	
		
	}//strlen($groupName

	
	$return['groupInfo'] .= "
	<div class='group-inline text'>
	<div class='group-list-title' title='".$titleName."'><img src='../../../pics/checkmark1.png' id='checkMark".$x."' group='".$groupID."' class='check-group hide'/> ".$groupName."</div>
	<div class='group-img-div' title='".$titleName."' style='background-image:url(".$path.");' onClick='selectGroup(".$x.")'></div>
	</div>
	";

$x++;
	
}//while

$return['done'] = 'done';
echo json_encode($return);
?>