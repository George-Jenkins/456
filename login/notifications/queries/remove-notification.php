<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$noticeID = $_POST['id'];

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

mysql_query("DELETE FROM notifications WHERE id='$noticeID' AND email='$email'");

$return['done'] = 'done';
echo json_encode($return);	

?>