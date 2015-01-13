<?php
include('../../connect/db-connect.php');

$password = cleanInput($_POST['password']);

$code = cleanInput($_POST['code']);

$email = cleanInput($_POST['email']);

if(!$password || !$code || !$email) return;

include('../../connect/members.php');

$query = mysql_query("SELECT * FROM members WHERE email='$email' AND code='$code'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	$return['error'] = true;
	$return['msg'] = 'Sorry. There was an error. Please try following the address in your email again.';
	echo json_encode($return);
	return;
}//if

$salt = mt_rand(1,2000000);

$hashedPassword = hash('sha256',($salt.$password));

$new_code = rand(1,1000);

mysql_query("UPDATE s SET s='$salt' WHERE email='$email'");

mysql_query("UPDATE members SET password='$hashedPassword', code='$new_code' WHERE code='$code' AND email='$email' ");

$return['error'] = false;
$return['msg'] = 'Your password has been reset.';
echo json_encode($return);

?>