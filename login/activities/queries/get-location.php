<?php

include('../../../connect/db-connect.php');
include('../../../connect/members.php');

$loginID = $_POST['z'];
$lastID = $_POST['lastID'];
$currentID = $_POST['currentID'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

$get = mysql_fetch_assoc($query);

$email = $get['email'];

if($email!='george@abstracthealth.com') return;

if(!$currentID){

$query = mysql_query("SELECT * FROM venue WHERE id>$lastID AND lat='0' ORDER BY id ASC");

$get = mysql_fetch_assoc($query);

$currentID = $get['id'];
$state = $get['state'];
$city = $get['city'];
$street = $get['house'].' '.$get['street'];

$return['currentID'] = $currentID;
$return['city'] = $city;
$return['state'] = $state;
$return['street'] = $street;

echo json_encode($return);
return;
}//if

if($currentID){
	
mysql_query("UPDATE venue SET lat='$latitude', lon='$longitude' WHERE id='$currentID'");

$query = mysql_query("SELECT * FROM venue WHERE id>$currentID ORDER BY id ASC");

$get = mysql_fetch_assoc($query);

$nextID = $get['id'];

$return['nextID'] = $nextID;
	
echo json_encode($return);

return;	
}//if

?>