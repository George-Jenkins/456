<?php
include('db-connect.php');

$loginID = cleanInput($_POST['z']);
$key = cleanInput($_POST['k']);

include('members.php');

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID' AND key_='$key'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	
	$return['error'] = 'wrong z';
	echo json_encode($return);
	return;
}//if

$return['error'] = 'right z';
echo json_encode($return);

?>