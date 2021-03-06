<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template1.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Reset Password</title>
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

<!--------using same css as retrieve login page--------->
<link href="css/retrieve-login/retrieve-login.css" rel="stylesheet" type="text/css"/>
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

<div id='form-box'>
<div id='inner-box'>

<div id='get-info'>Reset Password</div>
<p>

<div id='instructions'>Please type your new password</div>
<p>
<div class='clear'></div>
<?php
$email = $_GET['email'];
$code = $_GET['code'];
?>
<form id='form'>
<span class='general-input'><input type='password' id='password'/></span><p>
<input type='hidden' id='email' value="<?php echo $email?>"/>
<input type='hidden' id='code' value='<?php echo $code?>'/>
<span class='general-submit inline'><input type='submit' name='submit' id='submit' value='Reset'/></span> <span id='message' class='inline'></span>
</form>

</div><!---inner box---->
</div><!---form box---->
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
<script src="js/retrieve-login/reset-password.js"></script>
<!-- InstanceEndEditable -->
<script src='sjcl-master/sjcl.js'></script>
<!-- InstanceEnd --></html>
