<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../connect/members.php');

//get email and name 
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0) return;

$get = mysql_fetch_assoc($query);
$email = $get['email'];
$name = $get['name'];

//get emails for replies settings
$query = mysql_query("SELECT * FROM account_settings WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$replyEmailSetting = $get['email_replies'];

$return['userEmail'] = $email;
$return['reply_email_setting'] = $replyEmailSetting;
echo json_encode($return);	



?>