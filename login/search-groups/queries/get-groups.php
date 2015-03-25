<?php
include('../../../connect/db-connect.php');

$input = cleanInput($_POST['input']);
$loginID = cleanInput($_POST['z']);
$loop = $_POST['loop'];

//filter variables
$sex = cleanInput($_POST['sex']);
$minAge = cleanInput(floor($_POST['minAge']));
$maxAge = cleanInput(floor($_POST['maxAge']));
$city = cleanInput($_POST['city']);
$state = cleanInput($_POST['state']);

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
	
	$filterPermit = true;//this will be set to false if a query is not insync with filter
	
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
	
	if(stripos($groupName,$input)===0 || !$input){//*note - if there is no input it's because user is searching by demographic
	
	//determine if group is insync with filter
	//group location
	$creatorCity = $get_array['city']; 
	$creatorState = $get_array['state'];
	$creatorLocation = '';
	if($city && strcasecmp($city,$creatorCity)!=0) $filterPermit = false;
	if($state && strcasecmp($state,$creatorState)!=0) $filterPermit = false;
	if($creatorCity) $creatorLocation = $creatorCity.', ';
	if($creatorState) $creatorLocation .= $creatorState;
	if($city || $state) $creatorLocationDiv = "<div class='created-by-div'>".$creatorLocation."</div>";
	else $creatorLocationDiv = "";
	
	$query2 = mysql_query("SELECT * FROM group_members WHERE group_id='$groupID' AND approved!='no'"); 
	while($get_array2 = mysql_fetch_array($query2)){
		$memberEmail = $get_array2['email'];
		$query3 = mysql_query("SELECT * FROM members WHERE email='$memberEmail'");
		$get3 = mysql_fetch_assoc($query3);
		//check for gender
		$memberGender = $get3['gender'];
		if($sex && $sex!=$memberGender) $filterPermit = false;
		//check age range
		$memberBirthday = $get3['birthday'];
		if($memberBirthday == '0000-00-00' && $minAge || $memberBirthday == '0000-00-00' && $maxAge) $filterPermit = false;//if user didn't provide birthday
		$memberAgeSeconds = strtotime(date('Y-m-d')) - strtotime($memberBirthday);
		$memberAge = floor($memberAgeSeconds/86400/365);//seconds alive by seconds in day by days in year is age. floor rounds down
		if($minAge && $memberAge<$minAge || $maxAge && $memberAge>$maxAge) $filterPermit = false;
	}//while
	
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
	
	if($filterPermit) if($loop=='first' && $x<=20 || $loop=='second' && $x>20) $return['results'] .= "
	<div id='results-div".$x."' class='results-div hide'>
	<a href='".$link."' title='".$groupName."'><div class='results-title functionLink buttonLink'>".$presentableName."</div></a>
	<div class='created-by-div'>Created by ".$creatorName."</div>
	".$creatorLocationDiv."
	<div style='background-image:url(".$path.")' class='results-profile-pics'></div>
	</div>
	";
	
	}//if

	$return['numberOfLoops'] = $x;

	if($loop=='first' && $x==20) break;

$x++;
	
}//while

if(!$return['results'] && $loop=='first') $return['results'] = 'No results';//only of first loop so user doesn't get "no resultsnoresults"

echo json_encode($return);

?>