<?php
include('../../../../connect/db-connect.php');

$imageID = cleanInput($_POST['imageID']);
$loginID = cleanInput($_POST['z']);
$caption = cleanInput($_POST['captionInput']);

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);

if($numrows==0){
	
	$return['error'] = 'wrong z';
	echo json_encode($return);
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure user added photo
$query = mysql_query("SELECT * FROM group_album WHERE email='$email' AND id='$imageID'");
$numrows = mysql_num_rows($query);

if($numrows==0) return;

mysql_query("UPDATE group_album SET caption='$caption' WHERE email='$email' AND id='$imageID'");

$return['done'] = 'done';
echo json_encode($return);

?>