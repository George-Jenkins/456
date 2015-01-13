<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group']);

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0) return;

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure this is creator
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email' AND group_id='$group'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error']='wrong group';
	echo json_encode($return);
	return;
}//if

$y = 0;

//get group members
	$query2 = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND approved!='no'");
	//make sure this group has members
	$numrows2 = mysql_num_rows($query2);
	
	while($get_array2 = mysql_fetch_array($query2)){
	
		$groupMemberEmail = $get_array2['email'];
		$query3 = mysql_query("SELECT * FROM members WHERE email='$groupMemberEmail'");
		$get3 = mysql_fetch_assoc($query3);
		$groupMemberName = $get3['name'];
		$gender = $get3['gender'];
		//get member id in group
		$query3 = mysql_query("SELECT * FROM group_members WHERE email='$groupMemberEmail'");
		$get3 = mysql_fetch_assoc($query3);
		$groupMemberID = $get3['id'];
		//get img folder
		$query3 = mysql_query("SELECT * FROM profile_images WHERE email='$groupMemberEmail'");
		$get3 = mysql_fetch_assoc($query3);
		$folder = $get3['folder_name'];
		//get profile pic
		$query3 = mysql_query("SELECT * FROM profile_picture WHERE email='$groupMemberEmail'");
		$get3 = mysql_fetch_assoc($query3);
		$profilePic = $get3['img'];
	
		$path = "../profile/pics/".$gender."-head.png";
		if($profilePic) $path = "../profile/pics/".$folder."/".$profilePic;
		
		$return['groupMember'] .= "
		<div id='member-div".$y."' class='group-div-list member-inline groupID".$groupID." '>
		<div class='member-name functionLink buttonLink' onClick='selectMember(".$y.")' title='".$groupMemberName."'>".$groupMemberName."</div>
		
		<div id='delete-div".$y."' class='delete-div hide'>
		<div class='text-bold'>Remove</div>
		<table class='functionLink'>
		<tr>
		<td class='buttonLink no-link' onClick='noRemove(".$y.")'>No</td>
		<td><div style='width:15px'></div></td>
		<td class='buttonLink' onClick='yesRemove(".$y.",".$groupMemberID.")'>Yes</td>
		</tr>
		</table>
		</div><!--delete div--->
		
		<div style='background-image:url(".$path.")' class='profile-pic-list functionLink' onClick='selectMember(".$y.")' title='".$groupMemberName."'></div>
		
		</div><!---group-div-list----->";
	
	$y++;
	}//while

echo json_encode($return);

?>