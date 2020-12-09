<?php
/**********************************************************************************************************
Module:	application_class.php

Description:
	A PHP class that encapsulates the Open Audit host and configuration settings into a single entity
	
Properties:
	MySqlEnabled		[Bool]		TRUE if MySQL PHP extensions are enabled else FALSE
	LdapEnabled		[Bool]		TRUE if LDAP PHP extensions are enabled else FALSE
	OpenSslEnabled	[Bool]		TRUE if OpenSSL PHP extensions are enabled else FALSE
	OsString			[STRING]	Concatenation of various environment strings indicating the host OS/platform 
	OS					[STRING]	Generic OS string - "Windows" or "Linux"

Public Methods:
		
Recent Changes:
	
	[Nick Brown]	01/05/2009	New code.
	[Chad Sikorra]	05/10/2009	Add 'linux' to OS regex.

**********************************************************************************************************/
class xOpenAuditApplication
{
	var $MySqlEnabled = false;
	var $LdapEnabled = false;
	var	$OpenSslEnabled = false;
	var $OsString ="Unknown";
	var $OS ="Unknown";
	
	// *****	Class Constructor	********************************************************************************
	function OpenAuditApplication()
	{
		$this->GetHostOSInfo();
		$this->MySqlEnabled = function_exists("mysqli_connect");
		$this->LdapEnabled = function_exists("ldap_search");
		$this->OpenSslEnabled = $this->IsOpenSslEnabled();
	}
	
	// *****	Determines if OpenSSL is configured on host	********************************************************
	function IsOpenSslEnabled()
	{
		if (!function_exists("openssl_open")) return FALSE;
		
		/* Don't know if we really need to check for ldap.conf as well - will investigate
		switch ($this->OS)
		{
			case "Windows":
				if (file_exists("C:\openldap\sysconf\ldap.conf")) {return TRUE;} else {return FALSE;}
			case "Linux":
				if (file_exists("/etc/openldap/ldap.conf")) {return TRUE;} else {return FALSE;}
		}
		*/
		
		// Don't know OS - return TRUE
		return TRUE;		
	}
	
	// *****	Determines host OperatingSystem type **************************************************************
	function GetHostOSInfo()
	{
		$err_level = error_reporting(0);

		// Try various methods to determine OS  - See http://www.compdigitec.com/labs/2008/07/16/determine-the-os-and-version-of-php/
		$os_string = php_uname("s");  
		$os_string .= phpversion();
		$os_string .= $_SERVER["SERVER_SOFTWARE"];
		$os_string .= $_ENV['OS'];
		$os_string .= $_SERVER['OS'];
		$this->OsString = $os_string.PHP_OS;
		
		if (preg_match("/(ubuntu|suse|linux)/i", $os_string)){$this->OS = "Linux";}
		elseif (preg_match("/(win32|winnt|Windows)/i", $os_string)){$this->OS = "Windows";}
		
		error_reporting($err_level); 
	}
}

// Create application class instance called $TheApp
$TheApp = new xOpenAuditApplication;
?>
