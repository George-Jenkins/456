<?php
include('../connect/db-connect.php');

$name = cleanInput($_POST['register-name']);

$email = cleanInput($_POST['register-email']);

$password = cleanInput($_POST['register-password']);

$gender = cleanInput($_POST['gender']);

$trap = $_POST['trap'];

if($name && $email && $password && $gender && !$trap){
	
	date_default_timezone_set('America/New_York');
	
	$date = date('Y-m-d');
	
	$time = time();
	
	$salt = mt_rand(1,2000000);
	
	$hashedPassword = hash('sha256',($salt.$password));
	
	include('../connect/members.php');
	
	$query = mysql_query("SELECT * FROM members WHERE email='$email'");
	
	$numrows = mysql_num_rows($query);
	
	if($numrows!=0){
		
	$return['error'] = true;
	$return['msg'] = 'That email is in use';
	echo json_encode($return);
	return;
	}//if
	
	$code = rand(1,1000);
	
	mysql_query("INSERT INTO pre_members VALUES('','$name','$hashedPassword','$email','$gender','$code','$salt','','$date','$time')");
	
	//send email
	include('../sendgrid/fonts.php');
	
	$firstName = explode(" ",$name);
	$firstName = ucwords($firstName[0]);
	
	$subject = 'RitzKey Account';
	
	include('../sendgrid/fonts.php');
	
	$body_html = "<div style='".$font1."'>
	<p>Hey ".$firstName."!</p> 
	<p>Please follow the address below to finish registering.</p>
	http://RitzKey.com/validate?code=$code&email=$email
	</div>";
	
	$body_text = "Hey ".$firstName."! 
	\n\n
	Please follow the address below to finish registering.
	\n\n
	http://RitzKey.com/validate?code=$code&email=$email";
	
	
	include('../sendgrid/SendGrid_loader.php');
	include('../sendgrid/sendEmail.php');
	
	$return['error'] = false;
	$return['msg'] = "Please check your email to finish signing up.";
	echo json_encode($return);
	
	}//if

?>