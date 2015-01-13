<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$message = nl2br(cleanInput($_POST['message']));

include('../../../connect/members.php');

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'wrong z';
	echo json_encode($return);
	return;
}//if 

//get email
$get = mysql_fetch_assoc($query);
$email = $get['email'];

include('../../../sendgrid/fonts.php');
include('../../../sendgrid/admin-email.php');

$fromEmail = $email;

$email = $adminEmail;

$subject = 'User contact';

$body_html = "<div style='".$font1."'>".$fromEmail."<p><p>
".$message."</div>";

$body_text = $fromEmail."\n\n
".$message;

include('../../../sendgrid/SendGrid_loader.php');
include('../../../sendgrid/sendEmail.php');

$return['done'] = 'done';

echo json_encode($return);

?>