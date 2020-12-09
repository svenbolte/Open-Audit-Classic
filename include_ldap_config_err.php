<?php
/**********************************************************************************************************
Module:	include_ldap_config_err.php

Description:
	Displays warning that LDAP authentication is not correctly configured
		
Recent Changes:

	[Nick Brown]	02/03/2009
	Provides an error notification page where the system is configured to use LDAP authentication, but no LDAP connections have 
	been defined. In this case the user is logged on as admin anyway. This seemed preferable to locking admins out of the 
	system.

	[Nick Brown]	29/04/2009
	Added additional potential LDAP configuration error situations to be displayed - now as a table
	
**********************************************************************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Open-AudIT Login</title>
<link rel="stylesheet" type="text/css" href="ldap_login.css" />
<link rel="stylesheet" type="text/css" href="iefix.css" />
</head>
<body>
	<div class='npb_ldap_login_header'>
		<a href='index.php'><img class='logo' alt='Open Audit Logo' src='images/logo.png'/></a>
	</div>

	<div class='npb_ldap_login'>
		<img alt='Key' src='images/Key.png'/>
		<h2 class='npb_ldap_login'>LDAP Config Error</h2>

		<form action="./index.php" method='post'>
			<p>You have enabled LDAP security but LDAP is not correctly configured:</p>
			<table><tr><th>Possible Issue</th><th>Probable Cause</th></tr>
			<tr><td>You have not defined any LDAP sources for Open Audit to use.</td>
			<td>Please use the Admin -> Config menu option and select the LDAP page to define one or more LDAP connections.</td></tr>
			<tr><td>You do not have the LDAP extension enabled in your PHP configuration.</td>
			<td>Refer to PHP documentation.</td></tr>
			</table>
			<div><input type="submit" id="submit" name="submit" value="<?php echo __("Continue");?>"/></div>
		</form>	
	</div>

</body>
</html>
<?php
$_SESSION["role"] = "Admin";
$_SESSION["username"] = "Anonymous";
?>
