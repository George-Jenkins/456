<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];

//get hometown
$query = mysql_query("SELECT * FROM profile_hometown WHERE email='$email'");

$get = mysql_fetch_assoc($query);

$state = $get['state'];
$city = $get['city'];

$return['hometown'] = $city.', '.$state;

//get going out info
$query = mysql_query("SELECT * FROM profile_going_out WHERE email='$email'");

$get = mysql_fetch_assoc($query);

$input = $get['input'];

$return['going_out'] = nl2br($input);

echo json_encode($return);

?>