<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//get old img
$query = mysql_query("SELECT * FROM profile_cover_photo WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$oldImage = $get['img'];


//get img folder name
$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$folder = $get['folder_name'];

//unlink old image
unlink('../pics/'.$folder.'/'.$oldImage);

mysql_query("DELETE FROM profile_cover_photo WHERE email='$email'");

echo 'done';

?>
