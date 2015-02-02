<?php
include('connect/db-connect.php')
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template1.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>RitzKey | Join your friends</title>
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
.input-width input[type=text]{
	min-width:280px;
}
.no-gend{
	width:15px;
	height:auto;
}
body{
	background-image:url(pics/nightclub8.png);
	background-size:cover;
}
#error-message{
	max-width:300px;
	line-height:24px;
}
#signup-intr{
	line-height:24px;
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

<?php
$invite_code = cleanInput($_GET['ic']);
include('connect/members.php');

$query = mysql_query("SELECT * FROM group_invitations WHERE invite_code='$invite_code'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	echo "<style>
	.form-elements{
	display:none
	}</style>";
}
else
{
	
	$get = mysql_fetch_assoc($query);
	$inviterEmail = $get['email'];
	
	$query = mysql_query("SELECT * FROM members WHERE email='$inviterEmail'");
	$get = mysql_fetch_assoc($query);
	$name = $get['name'];
	$gender = $get['gender'];
	if($gender=='male') $possessive = 'his';
	else $possessive = 'her';
	
	echo "<style>
	#error-message{
	display:none
	}</style>";
}
?>

<br /><br />
<center>

<div class='form-container center-element' style='background-color:#F5F5F0'>

<div id='error-message' class='text'>There was an error. Please follow the link your friend sent you again. Sorry.</div>

<div class='form-elements'>

 <div class='form-title text align-left'>Register</div>

<div id='signup-intr' class='text'><?php echo "Your friend ".$name." wants you to join ".$possessive." entourage. Please complete this form to sign up.<br>
Have an account? <a href='member-login.html?".$invite_code."'>Click here</a>" ?></div>

<form id='form'>
	<table>
	<tr>
	<td class='general-input input-width'>Name<p><input type='text' id='name' name='register-name'/></td>
    </tr>
    
    <tr>
	<td class='general-input input-width'><span id='email-error'>Email</span><p><input type='text' id='email' name='register-email'/></td>
    </tr>
    
    <tr>
	<td class='general-input input-width'><span id='rep-email-error'>Repeat Email</span><p><input type='text' id='rep-email' name='rep-email'/></td>
    </tr>
    
    <tr>
	<td class='general-input input-width'><span id='password-error'>Password</span><p><input type='password' id='password' name='register-password'/></td>
    </tr>
    
    <tr>
    <td class='text general-radio'>Gender <img class='no-gend hide' src='pics/red-x.png'/><input type='radio' name='gender' id='male-gender' value='male'/> <label for='male-gender'>Male</label> 
    <img class='no-gend hide' src='pics/red-x.png'/> <input type='radio' name='gender' id='female-gender' value='female'/><label for='female-gender'>Female</label></td>
    </tr>
   <input type='hidden' id='time-zone' name='time-zone'/>
   <input type='hidden' name='trap'/>
   <input type='hidden' name='inviteCode' value='<?php echo $invite_code?>'/>
    <tr>
    <td class='general-submit'><p><input type='submit' id='submit' value='Register'/> <span id='error-span' class='text red hide'></span> <span class='small-processing hide' style='margin:0 5px'></span></td>
    </tr>
	</table>
</form>
</div><!----form-elements------>

</div><!----form-container------>
</center>

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
<script src='sjcl-master/sjcl.js'></script>
<script src='js/functions.js'></script>
<script src='js/menu/menu.js'></script>
<script src='js/general/lightbox.js'></script>
<script src='js/home-page/register.js'></script>
<script src='js/home-page/login.js'></script>
<script src="phonegap.js"></script>
<!-- InstanceBeginEditable name="EditRegion4" -->
<script src='js/jstz-timezone.js'></script>
<script src='js/invitation.js'></script>
<!-- InstanceEndEditable -->
<!-- InstanceEnd --></html>
