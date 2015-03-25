<?php

include('../../../connect/db-connect.php');
include('../../../connect/members.php');

$loginID = $_POST['z'];
$name = cleanInput($_POST['name']);
$website = cleanInput($_POST['website']);
$house = cleanInput($_POST['house']);
$country = cleanInput($_POST['country']);
$street = cleanInput($_POST['street']);
$city = cleanInput($_POST['city']);
$state = cleanInput($_POST['state']);
$twitter = cleanInput($_POST['twitter']);
$instagram = cleanInput($_POST['instagram']);
$facebook = cleanInput($_POST['facebook']);

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

$get = mysql_fetch_assoc($query);

$email = $get['email'];

if($email!='george@abstracthealth.com') return;
if(!$name) return;

$query = mysql_query("SELECT * FROM venue WHERE twitter!='' AND twitter='$twitter' OR instagram!='' AND instagram='$instagram' OR facebook!='' AND facebook='$facebook'");

$numrows = mysql_num_rows($query);

if($numrows!=0){

$return['error'] = 'Already added';
echo json_encode($return);
return;	
} //if

mysql_query("INSERT INTO venue VALUES ('','$name','$country','$state','$city','$street','$house','','','$facebook','$twitter','$instagram','$website','')");

$return['error'] = false;
echo json_encode($return);

?>