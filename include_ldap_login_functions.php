<?php
/**********************************************************************************************************
Module:	include_ldap_login_functions.php

Description:
		This module is included by "ldap_login.php" and "ldap_logout.php". The functions are primarily concerned with 
		authenticating the user against LDAP and determining the user's "role" (admin/user)
		
Recent Changes:
	
	[Nick Brown]	02/03/2009	Added AuthenticateUsingLdap(), GetUserRole(), GetUserInfo(), 
	GetUserPrimaryGroupName(), GetDomainSidAsSddl(), GetGroupNameFromSddl(),  IsUserInRoleArray(), 
	ConvertBinarySidToSddl() and DestroySession()
	[Nick Brown]	11/03/2009	Changes to AuthenticateUsingLdap(), GetUserPrimaryGroupName(), 
	GetDomainSidAsSddl()
	[Nick Brown]	17/03/2009	Changes to GetUserPrimaryGroupName() . Removed GetDomainSidAsSddl()
	[Nick Brown]	23/03/2009	Change to GetUserInfo()
	[Nick Brown]	03/04/2009	Moved  ConvertBinarySidToSddl() to "include_functions.php"
	[Nick Brown]	24/04/2009	Added utf8_encode() to LDAP search filter strings
	[Nick Brown]	01/05/2009	AuthenticateUsingLdap() - added SSL support	
	[Nick Brown]	19/08/2009	Added support for Open LDAP in AuthenticateUsingLdap(), GetUserRole(). New
	function GetOpenLdapUserDN().

**********************************************************************************************************/
include_once "include_functions.php";
include_once "include_config.php";

/**********************************************************************************************************
Function Name:
	AuthenticateUsingLdap
Description:
	Authenticate user against LDAP source. Wrapper for ConnectToLdapServer()
Arguments:
	$username				[IN]	[STRING]		user name for authentication
	$password				[IN]	[STRING]		password for authentication
	$ldap_connection		[IN]	[Array]			LDAP connection info array
Returns:
	LDAP link				[RESOURCE]  	if succesful, or...
	Error info				[ARRAY] 		if not.
Change Log:
	24/02/2009			New function	[Nick Brown]
	11/03/2009			$_SESSION["username"] no longer set in this function[Nick Brown]
	01/05/2009			Added SSL support	[Nick Brown]
	19/08/2009			Added Open LDAP support 	[Nick Brown]
**********************************************************************************************************/
function AuthenticateUsingLdap($username, $password, &$ldap_connection)
{
	/*
	global $TheApp;
	
	// If this connection is configured for SSL, but SSL isn't enabled, throw error
	if ($ldap_connection['use_ssl'] == 1 && !$TheApp->OpenSslEnabled)
	{
		$errmsg = "This LDAP connection is configured to use SSL, but your Open Audit server doesn't appear to have OpenSSL enabled.";
		DisplayError($errmsg);
	}
	*/
	
	if($ldap_connection['schema'] == "AD")
		{$user = isEmailAddress($username) ? $username : $username."@".$ldap_connection['fqdn'];}
	if($ldap_connection['schema'] == "OpenLDAP")
		{$user = GetOpenLdapUserDN($username, $ldap_connection);}
		
	// Authenticate
  error_reporting(E_ERROR | E_PARSE);
	$server = ($ldap_connection['use_ssl'] == 1) ? "ldaps://".$ldap_connection['server'] : $ldap_connection['server'];
	$connect = ConnectToLdapServer($server, $user, $password);
	return $connect;
}

/**********************************************************************************************************
Function Name:
	GetOpenLdapUserDN
Description:
	Attempts to locate user's DN in LDAP directory from their CN value
Arguments:
	$username		[IN]	[STRING]			Users' CN value
	$ldap_connection		[IN]	[Array]			LDAP connection info array 
Returns:
	User's DN 			[STRING]
Change Log:
	19/08/2009			New function	[Nick Brown]
**********************************************************************************************************/
function GetOpenLdapUserDN(&$username, &$ldap_connection)
{
	$db = ConnectToOpenAuditDb();
	
	// Connect to LDAP server anonymously to perform search
	$ldap = ConnectToLdapServer($ldap_connection['server'],"","");
	
	// Get all LDAP paths for supplied LDAP connection from MySQL db
	$sql = "SELECT ldap_paths_dn FROM ldap_paths WHERE ldap_paths_connection_id=".$ldap_connection['id'];
	$result = mysqli_query($db,$sql);
	
	// Loop thru all defined paths
	$userpath ="";
	if ($myrow = mysqli_fetch_array($result))
	{
		// Perform LDAP query using each path until user is found
		do
		{
			$path = $myrow["ldap_paths_dn"];
			$ldap_search_query = "(&(objectClass=organizationalPerson)(cn=".$username."))";
			$sr = ldap_search($ldap, $path, utf8_encode($ldap_search_query));
			$entries = ldap_get_entries($ldap, $sr);
			if($entries['count'] > 0)
			{
				// Found user in this path - exit loop
				$userpath = ",".$path;
				break;
			} 
		}
		while ($myrow = mysqli_fetch_array($result));
	}

	// tidy up resource handles
	mysqli_close();
	ldap_unbind($ldap);

	return "cn=".$username.$userpath;
}

/**********************************************************************************************************
Function Name:
	GetUserRole
Description:
	Gets user's role - "admin", "user" or "none" - as defined by the $admin_list and $user_list arrays
Arguments:
	$ldap						[IN]	[RESOURCE]	LDAP link identifier
	$ldap_connection		[IN]	[Array]			LDAP connection info array 
Returns:
	User's role				[STRING]
Change Log:
	24/02/2009			New function	[Nick Brown]
	19/08/2009			Added (very basic) Open LDAP support 	[Nick Brown]
**********************************************************************************************************/
function GetUserRole(&$ldap, &$ldap_connection)
{
	global $admin_list, $user_list;
	
	if($ldap_connection['schema'] == "OpenLDAP"){return "admin";}
	
	if ((count($admin_list)>0) || (count($user_list)>0))
	{
		$user = GetUserInfo($ldap, $ldap_connection);
		$primary_group = GetUserPrimaryGroupName($ldap, $user, $ldap_connection);
		if (IsUserInRoleArray($ldap, $admin_list, $user, $primary_group, $ldap_connection)) return "admin";
		if (IsUserInRoleArray($ldap, $user_list, $user, $primary_group, $ldap_connection)) return "user";
	}
	return "none";
}

/**********************************************************************************************************
Function Name:
	GetUserInfo
Description:
	Gets users LDAP attributes from LDAP source  (connection)
Arguments:
	$ldap						[IN]	[RESOURCE]	LDAP link identifier
	$ldap_connection		[IN]	[Array]			LDAP connection info array 
Returns:
	LDAP attributes		[ARRAY] 
Change Log:
	24/02/2009			New function	[Nick Brown]
	23/03/2009			Added ldap_get_values_len() code to get objectsid as binary data	[Nick Brown]
	24/04/2009			Added utf8_encode() to LDAP search filter	[Nick Brown]
**********************************************************************************************************/
function GetUserInfo(&$ldap, &$ldap_connection)
{
	$ldap_search_query = "(&(objectClass=user)(objectCategory=person)(|(sAMAccountName=".$_SESSION['username'].")))";
	$sr = ldap_search($ldap, $ldap_connection['nc'], utf8_encode($ldap_search_query));
	$info = ldap_get_entries($ldap, $sr);
	
	// ObjectSid is binary - need to use ldap_get_values_len() to ensure that it's correctly retrieved
	$entry = ldap_first_entry($ldap, $sr);
	$objectsid = ldap_get_values_len($ldap, $entry, "objectsid");
	$info[0]["objectsid"][0] = $objectsid[0];
	
	return $info;
}

/**********************************************************************************************************
Function Name:
	GetUserPrimaryGroupName
Description:
	Gets the name of the user's primary group
Arguments:
	$ldap						[IN]	[RESOURCE]	LDAP link identifier
	$user					[IN]	[ARRAY]			User's LDAP attributes array
	$ldap_connection		[IN]	[Array]			LDAP connection info array 
Returns:
	Primary group name		[STRING] 
Change Log:
	24/02/2009			New function	[Nick Brown]
	11/03/2009			$ldap argument dropped from call to GetDomainSidAsSddl() [Nick Brown]
	17/03/2009			Now using users SID to retrieve domain SID [Nick Brown]
**********************************************************************************************************/
function GetUserPrimaryGroupName(&$ldap, &$user, &$ldap_connection)
{
	$user_sddl = ConvertBinarySidToSddl($user[0]["objectsid"][0]);
	$sddl_array = explode("-",$user_sddl);
	array_pop($sddl_array);
	$domain_sddl = implode("-",$sddl_array);
	$primary_group_rid = $user[0]["primarygroupid"][0];
	$sddl = $domain_sddl."-".$primary_group_rid;
	$group_name = GetGroupNameFromSddl($ldap, $sddl);
	return $group_name;
}

/**********************************************************************************************************
Function Name:
	GetGroupNameFromSddl
Description:
	Authenticate user against LDAP source.
Arguments:
	$ldap			[IN]	[RESOURCE]	LDAP link identifier
	$sddl			[IN]	[STRING]		Group SID in SDDL format 
Returns:
	Group name			[STRING] 
Change Log:
	24/02/2009			New function	[Nick Brown]
**********************************************************************************************************/
function GetGroupNameFromSddl(&$ldap, &$sddl)
{
	$ldap_search_query = "(objectClass=*)";
	$sr = ldap_search($ldap, "<SID=".$sddl.">", $ldap_search_query);
	$info = ldap_get_entries($ldap, $sr);
	return $info[0]["samaccountname"][0];
}

/**********************************************************************************************************
Function Name:
	IsUserInRoleArray
Description:
	Determines if user is defined within the supplied role array
Arguments:
	$ldap						[IN]	[RESOURCE]	LDAP link identifier
	$arr						[IN]	[ARRAY]			"role" array - array of strings representing user/group names
	$user					[IN]	[ARRAY]			Array of user's LDAP attributes
	$primary_group		[IN]	[STRING]		Name of user's primary group
	$ldap_connection		[IN]	[Array]			LDAP connection info array 
Returns:
	[BOOLEAN]	TRUE if user account is in the role array or user is a member of any group in the array
Change Log:
	24/02/2009			New function	[Nick Brown]
	24/04/2009			Added utf8_encode() to LDAP search filter	[Nick Brown]
**********************************************************************************************************/
function IsUserInRoleArray(&$ldap, &$arr, &$user, &$primary_group, &$ldap_connection)
{
	if (count($arr) == 0) return FALSE;
	for ($j=0; $j<count($arr); $j++)
	{
		// See if this item from list matches user name 
		if (strtolower($_SESSION['username']) == strtolower($arr[$j])) return TRUE;

		// See if this item from list matches users primary group
		if (strtolower($primary_group) == strtolower($arr[$j])) return TRUE;
		
		// Now see if item from list is a group and user is a member of that group
		$sr = ldap_search($ldap, $ldap_connection['nc'], utf8_encode("(&(objectClass=group)(sAMAccountName=".$arr[$j]."))"));
		$info = ldap_get_entries($ldap, $sr);

		for ($i=0; $i<$info["count"]; $i++)
		{
			for ($k=0; $k<count($info[$i]["member"])-1; $k++)
			{if ($info[$i]["member"][$k] == $user[0]["dn"]) return TRUE;}
		}		
	}
	return FALSE;
}

/**********************************************************************************************************
Function Name:
	DestroySession
Description:
	Destroys PHP session and associated cookie
Arguments: None
Returns:	None
Change Log:
	24/02/2009			New function	[Nick Brown]
**********************************************************************************************************/
function DestroySession()
{
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {setcookie(session_name(), '', time()-42000, '/');}
	session_destroy();
}
?>