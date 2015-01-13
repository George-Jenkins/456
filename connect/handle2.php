<?php
include('db-connect.php');

$return['start'] = 1;
$return['limit'] = 1000;
$return['increase'] = 1;
$return['xsd'] = 555;

include('members.php');

$login1 = cleanInput($_POST['y']);
$login2 = cleanInput($_POST['z']);

$query = mysql_query("SELECT * FROM login_id WHERE login1='$login1' AND login2='$login2'");
			  
$get = mysql_fetch_assoc($query);
	
$return['email'] = $get['email'];		 

echo json_encode($return);	
?>