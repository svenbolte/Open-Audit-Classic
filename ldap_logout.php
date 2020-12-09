<?php
/**********************************************************************************************************
Module:	ldap_logout.php

Description:
	Logs user out of current PHP session and redirects the browser to the login page
		
Change Control:
	
	[Nick Brown]	02/03/2009
	Now uses the DestroySession() function from "include_ldap_login_functions.php" which ensures that the session cookie 
	is removed. Also logs logout event to the Event Log.
	
**********************************************************************************************************/

session_start();

include "include_config.php";
include "include_lang.php";
include "include_functions.php";
include "include_ldap_login_functions.php";

$user = $_SESSION["username"];
DestroySession();
header('Location: ldap_login.php');
LogEvent("ldap_logout.php","Main","User ".$user." logged out.");
exit;
?>
