<?php

$query2 = mysql_query("SELECT * FROM groups");

while($get_array2 = mysql_fetch_array($query2)){

$group = $get_array2['group_id'];

$group_emails = '';

//create group emails
$query3 = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND approved!='no'");
while($get_array3 = mysql_fetch_array($query3)){
	$group_emails .= "---".cleanInput($get_array3['email'])."---";
}//while

$name = 'RitzKey';
$email = '';
$message = '';
$shareEvent = $jsonStore;
$date = date('Y-m-d');
$time = time();

//mysql_query("DELETE FROM posts WHERE post_share!=''");

//see if this post has already been added
$query3 = mysql_query("SELECT * FROM posts WHERE post_share='$shareEvent' AND group_id='$group'");
$numrows3 = mysql_num_rows($query3);

if($numrows3==0) mysql_query("INSERT INTO posts VALUES('','$name','','$email','$group_emails','$group_emails','$message','$shareEvent','','','$group','$groupIDPhoto','$date','$time','true')");//old

}//while


?>