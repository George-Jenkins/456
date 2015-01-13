<?php
include('../connect/db-connect.php');

$email = cleanInput($_POST['email']);
$password = cleanInput($_POST['password']);
$inviteCode = $_POST['inviteCode'];

if($email && $password){
	
	include('../connect/members.php');
	
	//see if correct email
	$query = mysql_query("SELECT * FROM members WHERE email='$email'");
	$numrows = mysql_num_rows($query);
	
	if($numrows==0){
	$return['error'] = 'email';	
	echo json_encode($return);	
	return;
	}//if
	
	//get salt
	$query = mysql_query("SELECT * FROM s WHERE email='$email'");
	$get = mysql_fetch_assoc($query);
	$salt = $get['s'];
	
	$hashedPassword = hash('sha256',($salt.$password));
	
	//find the password
	$query = mysql_query("SELECT * FROM members WHERE email='$email' AND password='$hashedPassword'");
	$numrows = mysql_num_rows($query);	
	
	if($numrows==0){
	$return['error'] = 'password';	
	echo json_encode($return);	
	return;
	}//if	
	
	$get = mysql_fetch_assoc($query);	
	$name = $get['name'];
	
	$return['error'] = 'login';
	
	$return['email'] = $email;
	$return['name']	= $name;
	
	//reset loginID and key upon login
	while(true){
		$rand1 = hash('sha256',rand(1,1000000));
		$rand2 = hash('sha256',rand(1,1000000));
		$query = mysql_query("SELECT * FROM login_id WHERE login_id='$rand1' OR key_='$rand2'");
		$numrows = mysql_num_rows($query);
		if($numrows==0){
			mysql_query("UPDATE login_id SET login_id='$rand1', key_='$rand2' WHERE email='$email'");
			break;
		}//if
	}//while
	
	//process invitation if there was one
	if($inviteCode!=''){
		//get group id
		$query = mysql_query("SELECT * FROM group_invitations WHERE invite_code='$inviteCode'");
		$numrows = mysql_num_rows($query);
		$get = mysql_fetch_assoc($query);
		$groupID = $get['group_code'];
		//get founder email
		$query = mysql_query("SELECT * FROM groups WHERE group_id='$groupID'");
		$get = mysql_fetch_assoc($query);
		$founderEmail = $get['created_by'];
		//see if member was already invited or is in group already
		$query = mysql_query("SELECT * FROM group_members WHERE group_id='$groupID' AND email='$email'");
		$numrows2 = mysql_num_rows($query);
		if($founderEmail!=$email && $numrows!=0 && $numrows2==0){
			mysql_query("INSERT INTO group_members_invited VALUES('','$email','$inviteCode','$groupID')");//this db is needed so that 
			mysql_query("INSERT INTO group_members VALUES ('','$groupID','$email','no')");
		}//if
	}//if
	
	//set local storage vals
	$query = mysql_query("SELECT * FROM login_id WHERE email='$email'");
	$get = mysql_fetch_assoc($query);
	$return['i'] = $get['login_id'];
	//value for sessionStorage
	$return['k'] = $get['key_'];
	
	echo json_encode($return);	
	
}//if

?>