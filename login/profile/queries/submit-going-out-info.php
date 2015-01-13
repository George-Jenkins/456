<?php
include('../../../connect/db-connect.php');

$input = htmlspecialchars($_POST['input']);
$input_for_db = cleanInput($_POST['input']);
$loginID = cleanInput($_POST['z']);

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

$query = mysql_query("SELECT * FROM profile_going_out WHERE email='$email'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	mysql_query("INSERT INTO profile_going_out VALUES('','$email','$input_for_db')");
}//if
else
{
	mysql_query("UPDATE profile_going_out SET input='$input_for_db' WHERE email='$email'");
}//else

$return['input'] = nl2br($input);
echo json_encode($return);
?>