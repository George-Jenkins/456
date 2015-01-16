<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$eventID = cleanInput($_POST['eventID']);

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

//make sure this is an event
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'not event';
	echo json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);

$startDate = date('M, j Y',strtotime($get['start_date']))." at ".$get['start_time'];
$endDate =  date('M, j Y',strtotime($get['end_date']))." at ".$get['end_time'];

$return['active'] = $get['active'];
$return['eventName'] = $get['event_name'];
$return['description'] = $get['description'];
$eventPrice = $get['event_price'];
	$return['eventPrice'] = '$'.$eventPrice;	
$return['minContr'] = '$'.$get['min_contribution'];
$return['collectionMethod'] = $get['collection_method'];
$return['eventStart'] = $startDate;
$return['eventEnd'] = $endDate;

$eventImg = $get['image'];
//get creators image folder
$creatorEmail = $get['email'];
$query = mysql_query("SELECT * FROM profile_images WHERE email='$creatorEmail'");
$get = mysql_fetch_assoc($query);
$imgFolder = $get['folder_name'];

if($eventImg) $imgPath = '../profile/pics/'.$imgFolder.'/'.$eventImg;
else $imgPath = '../../pics/nightclub7.jpg';

//if user is creator send that back to js file incase user must be redirected to right page
if($creatorEmail==$email) $return['creator'] = 'creator';

//get creators info
$query = mysql_query("SELECT * FROM members WHERE email='$creatorEmail'");
$get = mysql_fetch_assoc($query);
$creatorName = $get['name'];
$gender = $get['gender'];
$memberID = $get['id'];
//get creators profile img
$query = mysql_query("SELECT * FROM profile_picture WHERE email='$creatorEmail'");
$get = mysql_fetch_assoc($query);
$profileImage = $get['img'];
if($profileImage) $profileImage = "../profile/pics/".$imgFolder."/".$profileImage;
else $profileImage = "../profile/pics/".$gender."-head.png";

//determine link to profile page
if($creatorEmail == $email) $profileLink = "../profile/profile.html";
else $profileLink = "../profile/profile-view.html?".$memberID;

$return['creatorInfo'] ="
<div>
<div class='creator-image-border inline-middle'>
<a href='".$profileLink."'><div class='creator-image' style='background-image:url(".$profileImage.");'></div></a>
</div>

<div class='text inline-middle'>Organized by ".$creatorName."</div>
</div>
";

$query = mysql_query("SELECT * FROM event_attendees WHERE event_id='$eventID'");
$amountContr = 0;
while($get_array = mysql_fetch_array($query)){
	
	$amountContr += $get_array['contribution'];
}//while

//see if user is attending
$query = mysql_query("SELECT * FROM event_attendees WHERE event_id='$eventID' AND email='$email'");
$numrows = mysql_num_rows($query);
if($numrows!=0) $return['attending'] = 'yes';

//get amount this user is contributing
$get = mysql_fetch_assoc($query);
if($numrows!=0) $return['userContribution'] = $get['contribution'];
else $return['userContribution'] = 0;

if($eventPrice!=0) $return['amountContr'] = '$'.$amountContr;

$return['eventImg'] = $imgPath;

//get people attending
$query = mysql_query("SELECT * FROM event_attendees WHERE event_id='$eventID'");
while($get_array = mysql_fetch_assoc($query)){
	$attendee = $get_array['email'];
	//if user is creator or user is attendee get the amount the attendee is spending
	$contribution = $get_array['contribution'];
	if($contribution==0) $contribution ='';
	if($creatorEmail==$email && $contribution!='') $contribution = '($'.$contribution.')';
	//get attendees info
	$query2 = mysql_query("SELECT * FROM members WHERE email='$attendee'");
	$get2 = mysql_fetch_assoc($query2);
	$attendeeName = $get2['name'];
	$gender = $get2['gender'];
	$memberID = $get2['id'];
	$query2 = mysql_query("SELECT * FROM profile_images WHERE email='$attendee'");
	$get2 = mysql_fetch_assoc($query2);
	$profileFolder = $get2['folder_name'];
	$query2 = mysql_query("SELECT * FROM profile_picture WHERE email='$attendee'");
	$get2 = mysql_fetch_assoc($query2);
	$profileImage = $get2['img'];
	if(!$profileImage) $path = "../profile/pics/".$gender."-head.png";
	else $path = "../profile/pics/".$profileFolder."/".$profileImage;
	//determine link to profile page
	if($attendee == $email) $profileLink = "../profile/profile.html";
	else $profileLink = "../profile/profile-view.html?".$memberID;
	//determine if name must be abbreviated
	$fullName = $attendeeName." ($".$contribution.')';
	$presentableName = $attendeeName." ($".$contribution.')';
	$chars = strlen($fullName);
	if($chars>=19){
		$presentableName = substr($fullName,0,16).'...';
	}//if
	
	$return['attendees'] .= "
	<div class='attendee-inline'>
		<a href='".$profileLink."' title='".$fullName."'>
			<div class='attendee-name functionLink buttonLink'>".$presentableName."</div>
		</a>
		<a href='".$profileLink."' title='".$fullName."'>
			<div class='attendee-img' style='background-image:url(".$path.");'></div>
		</a>
	</div> ";
}//while
if(!$return['attendees']) $return['attendees'] = '<p>Currently no attendees</p>';

//see if user can invite groups
$invite = false;
if($creatorEmail==$email) $invite = true;
//if not creator see if user is in group who was invited
else{
	$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND open='true' AND active='true'");
	$numrows = mysql_num_rows($query);
	if($numrows!=0){
	$query = mysql_query("SELECT * FROM event_invited_groups WHERE event_id='$eventID'");
	while($get_array = mysql_fetch_array($query)){
		$groupID = $get_array['group_id'];
		$query2 = mysql_query("SELECT * FROM group_members WHERE email='$email' AND group_id='$groupID'");
		$numrows2 = mysql_num_rows($query2);
		if($numrows2!=0){
		$invite = true;
		break;
		}//if
	}//while	
	}//if
}//if

$return['invite'] = $invite;

$return['done'] = 'done';
echo json_encode($return);
?>