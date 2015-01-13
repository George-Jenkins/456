<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../connect/members.php');

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//get email
$get = mysql_fetch_assoc($query);
$email = $get['email'];

$x=0;

$query = mysql_query("SELECT * FROM group_members WHERE email='$email'");

while($get_array = mysql_fetch_array($query)){
	
	//get group id
	$group_id = $get_array['group_id'];
	$return['group_id'.$x] = $group_id;
	
	//get group emails
	$query2 = mysql_query("SELECT * FROM posts WHERE group_id='$group_id'");
	while($get_array2 = mysql_fetch_assoc($query2)){
		
	$group_emails = $get_array2['group_emails'];
	if(substr_count($group_emails,"---".$email."---")!=0) $return['unchecked'.$x] = true;
	}//while
	
	$return['numberOfLoops'] = $x;
	
$x++;
}//while

echo json_encode($return);

?>