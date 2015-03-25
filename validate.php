<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template1.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>RitzKey | Raise money with friends to have more fun!</title>
<!-- InstanceEndEditable -->
<meta name="description" content="FriendPlan helps you and your friends access the fun and exclusive activities you've always wanted to do without you having to spend too much money."/>
<meta name="viewport" content="device-width=width, initial-scale=1, maximum-scale=1, minimum-scale=1"/>

<link rel="icon" type="image/png" href="pics/favicon.png"/>
<link rel="apple-touch-icon" href="pics/favicon.png"/>

<!------css------->
<link href="css/general/all-pages.css" type="text/css" rel="stylesheet"/>
<link href="css/body/body.css" rel="stylesheet" type="text/css"/>
<link href="css/menu/menu.css" rel="stylesheet" type="text/css"/>
<link href="css/templates/container.css" rel="stylesheet" type="text/css"/>
<link href="fonts/ptsans/stylesheet.css" rel="stylesheet" type="text/css"/>
<link href="css/general/forms.css" rel="stylesheet" type="text/css"/>
<!-- InstanceBeginEditable name="head" -->
<style>
#error-div{
	width:100%;
	max-width:400px;
	margin:0 auto;
}
</style>
<!-- InstanceEndEditable -->
</head>

<body>
<div id='menu'>

	<a href='index.html'  class='inline1'>
	<img src='pics/ritzkey-logo5.png' id='logo'/>
	</a>
    <a href='' class='inline1' id='menu-dropdown-icon'>
    <img src='pics/menu-dropdown.png'/>
    </a>
	<table id='menu-links' class='inline1'>
	<tr>
	<td class='menu-link'><a href='member-login.html'>Login</a></td> 
	</tr>
	</table>

	<table id='login-table' class='inline1'>
	<tr>
    <form id='login-form' method='POST'>
	<td class='menu-input'><span id='email-span'>Email</span><br /><input type='text' id='login-email' name='login-email'/></td>
	<td class='menu-input'><span id='password-span'>Password <a href='retrieve-login.html'>(Forgot?)</a></span><br /><input type='password' id='login-password' name='login-password'/></td>
    <input type='hidden' id='inviteCodeMenu'/>
    <td id='login-link'><br /><a href=''>Login</a></td>
    <td style='display:none'><input type='submit'/></td>
    </form>
	</tr>
	</table>
	<div id='mobile-menu'>
    	<div class='mobile-menu-link'><a href='member-login.html'>Login</a></div>
    </div>
	

</div><!-----menu-------->
<div id='container'>
<!-- InstanceBeginEditable name="EditRegion3" -->
<p>
<?php
include('connect/db-connect.php');

date_default_timezone_set('America/New_York');

$code = cleanInput($_GET['code']);
$email = cleanInput($_GET['email']);

if(!$code || !$email) echo "<div id='error-div' class='text'>
Sorry. There was an error. Please follow the link in your email again and if that doesn't work <a href='index'>try registering again.</a>
</div><!---error-div---->";
else{
	
	include('connect/members.php');
	
	$query = mysql_query("SELECT * FROM pre_members WHERE code='$code' AND email='$email'");
	
	$numrows = mysql_num_rows($query);
	
	if($numrows==0){
		
		echo "<div id='error-div' class='text'>
Sorry. There was an error. Please follow the link in your email again and if that doesn't work <a href='index'>try registering again.</a>
</div><!---error-div---->";
		
	}//if numrows==0
	else{
		
		$get = mysql_fetch_assoc($query);
		$name = cleanInput($get['name']);
		$password = cleanInput($get['password']);
		$email = cleanInput($get['email']);
		$gender = cleanInput($get['gender']);
		$code = rand(1,1000);
		$salt = $get['s'];
		$inviteCode = $get['invite_code'];
		$timezone = $get['timezone'];
		$date = date('Y-m-d');
		$time = time();
		
		if($inviteCode!=''){
			$query = mysql_query("SELECT * FROM group_invitations WHERE invite_code='$inviteCode'");
			$get = mysql_fetch_assoc($query);
			$groupID = $get['group_code'];
			mysql_query("INSERT INTO group_members VALUES ('','$groupID','$email','no')");
			mysql_query("INSERT INTO group_members_invited VALUES('','$email','$inviteCode','$groupID')");//this db is needed so that creator know who
			//invited member
		}//if 
		
		mysql_query("INSERT INTO members VALUES('','$name','$password','$email','$gender','','$code','$date','$time')");
		mysql_query("INSERT INTO s VALUES('','$email','$salt')");
		mysql_query("DELETE FROM pre_members WHERE email='$email'");
		
		//account setting
		mysql_query("INSERT INTO account_settings VALUES ('','$email','true','true','true','$timezone')");
		
		//add login id
		while(true){
			$rand1 = rand(1,1000000);
			$rand2 = rand(1,1000000);
			$query = mysql_query("SELECT * FROM login_id WHERE login_id='$rand1' AND key='$rand2'");
			$numrows = mysql_num_rows($query);
			if($numrows==0){
			mysql_query("INSERT INTO login_id VALUES('','$email','$name','$rand1','$rand2','')");
			mysql_query("INSERT INTO login_id VALUES('','$email','$name','$rand1','$rand2','mobile')");
			break;
			}//if
		}//while
		
		//make dir for profile images
	//go through while loop and stop only if folder name isn't taken
	while(true){
		$rand3 = rand(1,200);
		$rand4 = rand(1,200);
		
		$folder_name = $rand3.$rand4;
		$query2 = mysql_query("SELECT * FROM profile_images WHERE folder_name='$folder_name'");
		$numrows = mysql_num_rows($query2);
		if($numrows==0) break;
	}//while
	mkdir("login/profile/pics/".$folder_name);
	mysql_query("INSERT INTO profile_images VALUES('','$email','$folder_name')");
	
	echo "
	<script src='sjcl-master/sjcl.js'></script>
	
	<script>
		
	//start sessions
	localStorage.setItem('loginName','".$name."');
	
	localStorage.setItem('k',".$rand2.");
	var k = localStorage.getItem('k')
	
	var i = sjcl.encrypt(k,'".$rand1."');
	localStorage.setItem('i',i);
	
	localStorage.setItem('userEmail','".$email."');
	
	window.location='login/profile/profile.html'
	
	</script>";
		
	}//else
	
}//else
?>

<!-- InstanceEndEditable -->
</div><!-----container------->


<div id='bottom-menu' class='hide'>
	<span id='back-button'><img src='pics/left-icon.png'/></span>
    <span id='forward-button'><img src='pics/right-icon.png'/></span>
</div><!--bottom-menu-->

<!-- getclicky -->
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100810496); }catch(e){}</script>

</body>
<script src='js/jquery/jquery.js'></script>
<script src='js/jquery/jquery-animate-enhanced.js'></script>
<script src='js/jquery-ui/jquery-ui.js'></script>
<script src='js/plugins/fastclick.js'></script>
<script src='js/functions.js'></script>
<script src='js/root-dir-pulse.js'></script>
<script src='js/menu/menu.js'></script>
<script src='js/general/lightbox.js'></script>
<script src='js/home-page/register.js'></script>
<script src='js/home-page/login.js'></script>
<script src="phonegap.js"></script>
<!-- InstanceBeginEditable name="EditRegion4" -->

<!-- InstanceEndEditable -->
<script src='sjcl-master/sjcl.js'></script>
<!-- InstanceEnd --></html>
