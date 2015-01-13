<?php
include('../../../connect/db-connect.php');

$state = cleanInput($_POST['state']);
$city = cleanInput($_POST['city']);
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

$query = mysql_query("SELECT * FROM profile_hometown WHERE email='$email'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	mysql_query("INSERT INTO profile_hometown VALUES('','$email','$state','$city')");
}//if
else
{
	mysql_query("UPDATE profile_hometown SET state='$state', city='$city' WHERE email='$email'");
}//else

$return['input'] = $city.', '.$state;
echo json_encode($return);
?>