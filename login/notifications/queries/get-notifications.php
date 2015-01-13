<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

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

if(!$email) return;

//get notifications
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email'");

$x=0;

//get groups the user created
while($get_array = mysql_fetch_array($query)){
	
	$groupID = $get_array['group_id'];
	$groupName = $get_array['group_name'];
	
	$query2 = mysql_query("SELECT * FROM group_members WHERE group_id='$groupID' AND approved='no' ORDER BY id ASC");
	
	//get email and name of members trying to apply
	while($get_array2 = mysql_fetch_array($query2)){
		
		$memberEmail = $get_array2['email'];
		$id = $get_array2['id'];
		$query3 = mysql_query("SELECT * FROM members WHERE email='$memberEmail'");
		$get3 = mysql_fetch_assoc($query3);
		$memberName = $get3['name'];
		
		//get person who invite member
		$query3 = mysql_query("SELECT * FROM group_members_invited WHERE email='$memberEmail'");
		$get3 = mysql_fetch_assoc($query3);
		$inviteCode = $get3['invite_code'];
		$query3 = mysql_query("SELECT * FROM group_invitations WHERE invite_code='$inviteCode'");
		$get3 = mysql_fetch_assoc($query3);
		$inviterEmail = $get3['email'];
		$query3 = mysql_query("SELECT * FROM members WHERE email='$inviterEmail'");
		$get3 = mysql_fetch_assoc($query3);
		$inviterName = $get3['name'];
	
		
		if($inviterEmail && $inviterEmail!=$email) $message = $inviterName." invited ".$memberName." to join your entourage ".$groupName;
		if($inviterEmail==$email) $message = "You invited ".$memberName." to join your entourage ".$groupName;
		if(!$inviterEmail) $message = $memberName." wants to join your entourage ".$groupName;
		
		$return['message'] .= "<div class='notification-div'>".$message."
		<table id='approve-disapprove-table".$id."'>
		<tr>
		<td class='functionLink buttonLink approve-button' onClick='approveMember(".$id.",".$groupID.")'>Approve</td>
		<td><div style='width:15px'></div></td>
		<td class='functionLink buttonLink' onClick='disapproveMember(".$id.",".$groupID.")'>Disapprove</td>
		</tr>
		</table>
		
		<table id='approve-disapprove-decision-table".$id."' class='hide'>
		<tr>
		<td id='approve-disapprove-msg".$id."' class='text-bold'></td>
		</tr>
		</table>
		
		</div><!---notification-div---->";
	}//while

	$return['numberOfLoops'] = $x;

$x++;	
}//while


$query = mysql_query("SELECT * FROM notifications WHERE email='$email'");

while($get_array = mysql_fetch_array($query)){
	
	$message = $get_array['message'];
	$noticeID = $get_array['id'];
	
	$return['message'] .= "<div class='notification-div'>".$message."
		<table>
		<tr>
		<td onClick='seenNotice(".$noticeID.")' id='notice".$noticeID."' class='functionLink buttonLink'>Got it</td>
		<td class='text-bold' id='doneMessage".$noticeID."'></d>
		</tr>
		</table>
	</div><!---notification-div---->";
	
	$return['numberOfLoops'] = $x;

$x++;
	
}//while

if(!$return['message']) $return['message'] = '<div>No new notifications</div>';

echo json_encode($return);	

?>