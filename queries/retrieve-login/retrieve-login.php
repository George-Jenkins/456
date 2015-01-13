<?php
include('../../connect/db-connect.php');

$email = cleanInput($_POST['email']);

include('../../connect/members.php');

if(!$email) return;

$query = mysql_query("SELECT * FROM members WHERE email='$email'");

$numrows = mysql_num_rows($query);

	if($numrows==0){
	$return['error'] = true;
	$return['msg'] = 'Sorry. Wrong email.';
	echo json_encode($return);
	return;
	}//if

$get = mysql_fetch_assoc($query);

$name = ucwords($get['name']);

$first_name = explode(" ",$name);
$first_name = $first_name[0];

$code = rand(1,1000);

mysql_query("UPDATE members SET code='$code' WHERE email='$email'");

//include fonts
include('../../sendgrid/fonts.php');

$subject = "RitzKey Login";

$body_text = "Hey ".$first_name."!
\n\n
Please follow this address to reset your password.
\n\n
http://RitzKey.com/reset-password?code=$code&email=$email
";

$body_html = "<div style='".$font1."'>
<p>Hey ".$first_name."!</p>

<p>Please follow this address to reset your password.</p>

http://RitzKey.com/reset-password?code=$code&email=$email
</div>";

include('../../sendgrid/SendGrid_loader.php');

include('../../sendgrid/sendEmail.php');

$return['error'] = false;
$return['msg'] = 'Please check your email.';
echo json_encode($return);

?>