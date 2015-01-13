<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group']);
$firstID = $_POST['firstID'];
$lastID = $_POST['lastID'];

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];

for($x=$firstID;$x<=$lastID;$x++){
	
	$query = mysql_query("SELECT * FROM posts WHERE group_id='$group' AND id='$x'");
	$numrows = mysql_num_rows($query);
	
	if($numrows==0) $return['deletedPost'.$x] = 'deleted';
	else $return['deletedPost'.$x] = 'not deleted';
	
}//for

$return['done'] = 'done';

echo json_encode($return);


?>