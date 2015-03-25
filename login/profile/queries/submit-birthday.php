<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$birthdayDay = cleanInput($_POST['birthdayDay']);
$birthdayMonth = cleanInput($_POST['birthdayMonth']);
$birthdayYear = cleanInput($_POST['birthdayYear']);

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = "wrong z";
	echo json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

$birthdayDateFormat = date('Y-m-d',strtotime($birthdayMonth.'/'.$birthdayDay.'/'.$birthdayYear));

mysql_query("UPDATE members SET birthday='$birthdayDateFormat' WHERE email='$email'");

$return['birthday'] = date('M j, Y',strtotime($birthdayDateFormat));
$return['done'] = 'done';

echo json_encode($return);
?>