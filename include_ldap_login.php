<?php
/**********************************************************************************************************
Module:	include_ldap_login.php

Description:
	This module is included by "include.php" if $use_ldap_login = 'y'. Determines if user is authenticated by checking 
	the value of $_SESSION["role"]. If user is not authenticated, browser is redirected to "ldap_login.php" to login.
	If user is authenticated with a role of "user" then access to the pages defined in $user_deny_pages (which should 
	really be in "include_config.php"?) is not permitted.
		
Change Control:
	
	[Nick Brown]	02/03/2009
	Minor change - removed check for value of $use_ldap_login - not needed as this has been done in "include.php" which is
	why we are here.
	[Chad Sikorra]	02/10/2009	Add URL redirection
	
**********************************************************************************************************/
session_start();

//put pages here that you wish to deny regular users access
$user_deny_pages = array("admin_config.php", "database_backup_form.php", "database_restore_form.php");

if(!isset($_SESSION["role"]))
{
	$redirect =
		( empty($_SERVER['QUERY_STRING']) ) ?
		"?redirect=".urlencode($_SERVER['PHP_SELF']) :
		"?redirect=".urlencode("{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}") ;
	header('Location: ldap_login.php'.$redirect);
	exit;
}
else
{
	if ($_SESSION["role"]=="user")
	{
		if (array_search(basename($_SERVER['SCRIPT_NAME']), $user_deny_pages)!== False)
		{
			echo "Access Denied!";
			exit;
		}
	}
  
//    //This section sets a session timout in seconds   
//    if (!isset($_SESSION["session_count"])) {
//        $_SESSION["session_count"]=0;
//        $_SESSION["session_start"]=time();
//    } else {
//        $_SESSION["session_count"]++;
//    } 
//    $session_timeout = 900; // 15 minutes (in sec)
//    $session_duration = (time() - $_SESSION["session_start"]);
//    if ($session_duration > $session_timeout) {
//        header("Location: ldap_logout.php");  // Redirect to Login Page
//    }
//    $_SESSION["session_start"]=time();
//    //End of session timeout section
}
?>
