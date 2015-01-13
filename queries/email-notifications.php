<?php
include('../connect/db-connect.php');
include('../connect/members.php');

include('../sendgrid/fonts.php');
include('../sendgrid/sendGrid_loader.php');

$query = mysql_query("SELECT * FROM members");

//notify users of replies
while($get_array = mysql_fetch_array($query)){
	
	$email = $get_array['email'];
	$name = $get_array['name'];
	$first_name = explode(' ',$name);
	$first_name = $first_name[0];
	
	//make sure user's settings allow for email notifications when someone replies
	$query2 = mysql_query("SELECT * FROM account_settings WHERE email='$email' AND email_replies='true'");
	$numrows2 = mysql_num_rows($query2);
	
	//see if replies to this user
	$query2 = mysql_query("SELECT * FROM posts WHERE recipient_email='$email' AND checked='false'");
	$repliesNumrows = mysql_num_rows($query2);
	
	//send email
	if($numrows2!=0 && $repliesNumrows!=0){
		
		$subject = 'RitzKey messages';
		
		$body_html = "<div style='".$font1."'>
		<p>".$first_name."!</p>
		<p>Just so you know, you have unchecked replies.</p>
		<a href='http://ritzkey.com/login/replies/replies.html'>Click here</a> to view.
		</div>";
		
		$body_text = $first_name."!<p>
		Just so you know, you have unchecked replies.<p>
		<a href='http://ritzkey.com/login/replies/replies.html'>Click here</a> to view.
		";
	
		include('../sendgrid/sendEmail.php');	
		
	}//if
	
}//while


?>