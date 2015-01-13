<?php
include('../../../connect/db-connect.php');

$input = cleanInput($_POST['input']);
$loginID = cleanInput($_POST['z']);
$loop = $_POST['loop'];

include('../../../connect/members.php');

//get email and name 
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

$numrows = mysql_num_rows($query);
if($numrows==0){
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];
$name = $get['name'];

$query = mysql_query("SELECT * FROM groups ORDER BY LENGTH(group_name) ASC");

$x = 0;

while($get_array = mysql_fetch_array($query)){
	
	$groupName = $get_array['group_name'];
	$groupID = $get_array['group_id'];
	$creatorEmail = $get_array['created_by'];
		$query2 = mysql_query("SELECT * FROM members WHERE email='$creatorEmail'");	
		$get2 = mysql_fetch_assoc($query2);
	$creatorName = $get2['name'];
	$imgFolder = $get_array['image_folder'];
	$groupImg = $get_array['group_img'];
	
	$path = "../group/pics/".$imgFolder."/".$groupImg;
	if(!$groupImg) $path = "../../pics/nightclub3.jpg";
	
	if(stripos($groupName,$input)===0){
	
	//determine if group name should be shortened
	$presentableName = $groupName;
	$chars = strlen($groupName);
	if($chars>25){
		$presentableName = substr($groupName,0,22).'...';
	}//if
	
	//determine the link to group
	$link = "../group/group-view.html?".$groupID;//default link
	$query2 = mysql_query("SELECT * FROM group_members WHERE email='$email' AND group_id='$groupID' AND approved!='no'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2) $link = "../group/group-member.html?".$groupID;//if user is member
	if($creatorEmail==$email) $link = "../group/group.html?".$groupID;//if user is creator
	
	if($loop=='first' && $x<=20 || $loop=='second' && $x>20) $return['results'] .= "
	<div id='results-div".$x."' class='results-div hide'>
	<a href='".$link."' title='".$groupName."'><div class='results-title functionLink buttonLink'>".$presentableName."</div></a>
	<div class='created-by-div'>Created by ".$creatorName."</div>
	<a href='".$link."' title='".$groupName."'><div style='background-image:url(".$path.")' class='results-profile-pics'></div></a>
	</div>
	";
	
	}//if

	$return['numberOfLoops'] = $x;

	if($loop=='first' && $x==20) break;

$x++;
	
}//while

if(!$return['results'] && $loop=='first') $return['results'] = "No results";//only of first loop so user doesn't get "no resultsnoresults"

echo json_encode($return);

?>