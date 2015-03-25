<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$groupID = cleanInput($_POST['group']);

include('../../../connect/members.php');

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//get email
$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure this user belongs to this group
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$groupID' AND approved!='no'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//create invite if user doesn't already have invite code
$query = mysql_query("SELECT * FROM group_invitations WHERE email='$email' AND group_code='$groupID'");
$numrows = mysql_num_rows($query);
if($numrows!=0){
	$get = mysql_fetch_assoc($query);
	$code = $get['invite_code'];
	$return['code'] = $code;
	echo json_encode($return);
	return;
}//if

while(true){
	
	$rand = md5(mt_rand(1,1000000));
	$query1 = mysql_query("SELECT * FROM group_invitations WHERE group_code='$groupID' AND invite_code='$rand'");
	$numrows1 = mysql_num_rows($query1);
	if($numrows1==0){
		mysql_query("INSERT INTO group_invitations VALUES('','$email','$groupID','$rand')");
		break;
	}//if
}//while

$return['code'] = $rand;
echo json_encode($return);

?>