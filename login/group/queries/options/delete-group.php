<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group']);
$memberID = $_POST['id'];

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0) return;

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure this user created group
$query = mysql_query("SELECT * FROM groups WHERE created_by='$email' AND group_id='$group'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

mysql_query("DELETE FROM groups WHERE group_id='$group'");
mysql_query("DELETE FROM group_invitations WHERE group_code='$group'");
mysql_query("DELETE FROM group_members WHERE group_id='$group'");
mysql_query("DELETE FROM posts WHERE group_id='$group'");

echo json_encode($return);

?>