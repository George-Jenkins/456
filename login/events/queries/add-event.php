<?php
include('../../../connect/db-connect.php');

$eventName = cleanInput($_POST['event-name']);
$startDate = $_POST['start-date'];
$startTime = cleanInput($_POST['start-hour'].":".$_POST['start-minute']." ".$_POST['start-am-pm']);
$endDate = $_POST['end-date'];
$endTime = cleanInput($_POST['end-hour'].":".$_POST['end-minute']." ".$_POST['end-am-pm']);
$eventDescription = cleanInput($_POST['event-description']);
$eventPrice = cleanInput($_POST['event-price']);
$minAmount = cleanInput($_POST['min-amount']);
$collectionMethod = cleanInput($_POST['collection-method']);
$whoCanInvite = cleanInput($_POST['who-can-invite']);

//change date format
$startDate = cleanInput(date('Y-m-d',strtotime($startDate)));
$endDate = cleanInput(date('Y-m-d',strtotime($endDate)));

if(!$eventName || !$eventDescription) return;

$loginID = cleanInput($_POST['z']);

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

while(true){
	
	$rand = mt_rand(1,1000000000);
	$query = mysql_query("SELECT * FROM events WHERE event_id='$rand'");
	$numrows = mysql_num_rows($query);
	if($numrows==0) break;
}//while

mysql_query("INSERT INTO events VALUES ('','$email','$eventName','$eventDescription','$eventPrice','$minAmount','$collectionMethod','$rand','','$startDate','$startTime','$endDate','$endTime','$whoCanInvite','false')");

$return['groupID'] = $rand;

$return['done'] = 'done';
echo json_encode($return);

?>