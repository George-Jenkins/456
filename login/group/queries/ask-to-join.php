<?php
include('../../../connect/db-connect.php');

$group = cleanInput($_POST['group']);

$loginID = cleanInput($_POST['z']);

include('../../../connect/members.php');

//get email and name 
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'wrong z';
	echo json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];
$name = $get['name'];

//make sure member isn't already in group member db
$query = mysql_query("SELECT * FROM group_members WHERE email='$email' AND group_id='$group'");
$numrows = mysql_num_rows($query);

if($numrows!=0) return;

mysql_query("INSERT INTO group_members VALUES('','$group','$email','no')");

$return['done'] = $group;

echo json_encode($return);	



?>