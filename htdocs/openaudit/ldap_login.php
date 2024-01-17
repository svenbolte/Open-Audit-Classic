<?php
/**********************************************************************************************************
Module:	include_ldap_login.php

Description:
	Displays logon page for user to supply credentials and authenticates user against LDAP Source.
		
Recent Changes:
	
	[Nick Brown]	02/03/2009
	The main functional change is that LDAP authentication now uses the LDAP connections defined in Admin->Config-LDAP,
	not the $ldap_base_dn and $ldap_server values from "include_config.php". This means that multiple LDAP
	sources can now be used for authentication..
	
	Also, $admin_list and $admin_list have been moved to  "include_config.php" where they should be (rather than this page)
	and can now include the user's primary group in either list.
	
	Most of the detailed functionality has been moved into "incldue_ldap_login_functions.php". This makes the logic on this 
	page more easy to follow IMO.

	[Nick Brown]	11/03/2009	$_SESSION["username"] now set in this module, not in the call to AuthenticateUsingLdap()
	[Nick Brown]	24/03/2009	Added detection and handling for UPN format username to login
	[Nick Brown]	07/04/2009	Username text field now has focus on page load:
	(http://www.open-audit.org/phpBB3/viewtopic.php?f=9&t=3157&start=0&st=0&sk=t&sd=a)
	[Nick Brown]	29/04/2009	Checks for availability of LDAP functions  - redirects to "include_ldap_config_err.php" 
	if not available
	[Nick Brown]	01/05/2009	Included "application_class.php" to provide access to the global $TheApp object. 
	[Chad Sikorra]	02/10/2009	Add URL redirection

**********************************************************************************************************/

session_start();

include "application_class.php";
include "include_config.php";
include "include_lang.php";
include "include_functions.php";
include "include_ldap_login_functions.php";

if (($_SERVER["SERVER_PORT"]!=443) && ($use_https == "y")){ header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']); exit(); }

$ldap_connections = GetLdapConnectionsFromDb();
// Show error if no LDAP connections defined
if ((count($ldap_connections) == 0) or !$TheApp->LdapEnabled) 
{
	include "include_ldap_config_err.php";
	exit;
}

// If we have logon details POST'ed - perform LDAP authentication
if (isset($_POST['username']))
{	
	// Make sure to save the redirect url on logon failures
	if (isset($_GET['redirect'])) {
		$redirect_persist = "&redirect=".urlencode($_GET['redirect']);
	}

	// Connect (authenticate) to LDAP
	$connect = AuthenticateUsingLdap($_POST['username'],$_POST['password'],$ldap_connections[$_POST['ldap_connection']]);
	// Check for connection error
	if (is_array($connect))
	{
		DestroySession();
		RedirectToUrl($_SERVER['SCRIPT_NAME'].'?Result=Failed'.$redirect_persist);
	}

	// Set Session value - remove domain suffix if UPN was used
	if(isEmailAddress($_POST['username']))
	{
		$username = explode("@",$_POST['username']);
		$_SESSION["username"] = $username[0];
	}
	else {$_SESSION["username"] = $_POST['username'];}
	
	// Get user's role
	$_SESSION["role"] = GetUserRole($connect, $ldap_connections[$_POST['ldap_connection']]);	
	ldap_unbind($connect);
	
	// Redirect to appropriate url based on role
	if ($_SESSION["role"] != "none")
	{
		LogEvent("ldap_login.php","Main","User ".$_SESSION["username"]." succesfully logged on as ".$_SESSION["role"]);
		$url = (isset($_GET['redirect'])) ? urldecode($_GET['redirect']) : './index.php';
		RedirectToUrl($url);
	} 
	else
	{
		DestroySession();
		RedirectToUrl($_SERVER['SCRIPT_NAME'].'?Result=NoAccess'.$redirect_persist);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Open-AudIT Setup</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" type="text/css" href="default.css" />
  </head>
	<body onload='document.getElementById("username").focus()'>
<?php
	get_headerbanner();
?>
  </div>
	<div class='npb_ldap_login'>
	<h2 class='npb_ldap_login'>Please Login</h2>

	<form action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="POST">

	<label><?php echo __("Login Name:");?></label>
	<input TYPE="Text" id="username" name="username"><br />
	<label><?php echo __("Password:");?></label>
	<input TYPE="Password" name="password"><br />
	<label><?php echo __("Account Database:");?></label>
	<select name="ldap_connection">
<?php
foreach($ldap_connections as $key => $ldap_connection) {echo "<option value='".$key."'>".$ldap_connection['name']."";}
?>
		<!--<option value='BUILTIN'>Open Audit-->
	</select><br />
	
	<input TYPE="Submit" id="submit" name="submit" value="<?php echo __("Submit");?>">

	</form>
</div>

<div class='npb_ldap_login_disclaimer'>
	<br /><?php echo __("This System is for the use of authorized users only.");?>
	<br /><?php echo __("Please login using your LDAP or Active Directory User Name and Password.");?>

<?php 
// Look for Result=false in the calling URI (actually just look for 'sult' cos we aren't that bothered ;} )
// This method seems to work regardless of register_globals, see http://uk2.php.net/manual/en/reserved.variables.php
if (@preg_match("/Result=Failed/i",$_SERVER['REQUEST_URI'])) {

// Warn them off if they screwed up the login.
echo '<b><br /><br />'. __("Unauthorised use of this site may be a criminal offence.");
echo '<br />'.__(" Access attempt from ").' '.$_SERVER['REMOTE_ADDR'];
echo '<br />'.__("Your IP address and browser details will be logged.");
echo '<br />'. __("Any malicious attempt to access this site will be investigated.");
echo '<br />'. __("Please contact the administrator if you are having problems logging in.") . '</b>';

} elseif (@preg_match("/Result=NoAccess/i",$_SERVER['REQUEST_URI'])) {

// Let them know they need to request access for the site
echo '<b><br /><br />'.__("Your are not in the correct group to access this site.");
echo '<br />'. __("Please contact the administrator or local Help-Desk to request access.") . '</b>';

}else{
// Be gentle with them the first time.
echo '<br />'. __("Use of this site is subject to legal restrictions.");
}
?>
</div>
</body>
</html>
