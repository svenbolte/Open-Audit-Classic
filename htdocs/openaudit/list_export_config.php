<?php
/*
*
* @version $Id: index.php  24th May 2007
*
* @author The Open Audit Developer Team
* @objective Export Config Page for Open Audit.
* @package open-audit (www.open-audit.org)
* @copyright Copyright (C) open-audit.org All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see ../gpl.txt
* Open-Audit is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See www.open-audit.org for further copyright notices and details.
*
*/ 
//
// Warning, dont include include.php
// 'cos this would set our http header, and we dont want one.
include_once("include_config.php");
include_once("include_functions.php");
include_once("include_lang.php");
//
//
$host_name = "";
if (isset($_GET['hostname'])and ($_GET['hostname'] <>"")) {
    $host_name=$_GET['hostname'];
} else {
    $host_name=".";
}

$application = "";
if (isset($_GET['application'])and ($_GET['application'] <>"")) {
    $application=$_GET['application'];
} else {
    $application="auidit.vbs";
}

// Currently we can only do a "this machine" audit, not a domain audit.
// Firs we need to figure out our server installation path etc so we can generate a suitable config

$_REAL_SCRIPT_DIR = realpath(dirname($_SERVER['SCRIPT_FILENAME'])); // filesystem path of this page's directory 
$_REAL_BASE_DIR = realpath(dirname(__FILE__)); // filesystem path of this file's directory 
$_MY_PATH_PART = substr( $_REAL_SCRIPT_DIR, strlen($_REAL_BASE_DIR)); // just the subfolder part between <installation_path> and the page

$INSTALLATION_PATH = $_MY_PATH_PART ? substr( dirname($_SERVER['SCRIPT_NAME']), 0, -strlen($_MY_PATH_PART) ) : dirname($_SERVER['SCRIPT_NAME']);
//
// We subtract the subfolder part from the end of <installation_path>, leaving us with just <installation_path> :)
//
$our_host= "http://".$_SERVER['HTTP_HOST'];


// Find requesting host IP
$remote_addr= $_SERVER['REMOTE_ADDR'];

//Note:  Your web server must be configured to create this variable.
//For example in Apache you'll need HostnameLookups On  inside httpd.conf for it to exist. See also gethostbyaddr().
$remote_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
if ( $remote_host == "") {
$remote_host= $_SERVER['REMOTE_HOST'];
} else {}

// Now we can set our instance to the correct location
$our_instance = $INSTALLATION_PATH;

// Set up usable <CR><LF> line ends for vbscript, otherwise the cript wont parse correctly
$config_newline="\r\n";
// 


// We will put everything in the $this_config variable

// Now we set up the rest of the config variables which will mostly be standard stuff
$this_config='audit_location = "r"'.$config_newline;


// Not used, but worth including
$this_config=$this_config.'server_install_path = "'.$INSTALLATION_PATH.'"'.$config_newline;


// A lot of this would be best coming from a set up page or from records from the database

//Set this to "y" for debugging and "n" for silent mode 
$this_config=$this_config.'verbose = "n"'.$config_newline; 

// 
$this_config=$this_config.'audit_host="'.$our_host.'"'.$config_newline;


$this_config=$this_config.'online = "yesxml"'.$config_newline; 

// Force just the requested PC to be audited.
//$this_config=$this_config.'strComputer = "'.$remote_host.'"'.$config_newline; 
$this_config=$this_config.'strComputer = "'.$host_name.'"'.$config_newline; 
$this_config=$this_config.'strUser = ""'.$config_newline; 
$this_config=$this_config.'strPass = ""'.$config_newline; 

$this_config=$this_config.'ie_visible = "n" '.$config_newline;
$this_config=$this_config.'ie_auto_submit = "y" '.$config_newline;

//  Switch this on to debug
$this_config=$this_config.'ie_submit_verbose = "n"'.$config_newline;

// Set to the OA host and instance of OA
$this_config=$this_config.'ie_form_page = "'.$our_host.$our_instance.'/admin_pc_add_1.php"'.$config_newline; 
$this_config=$this_config.'non_ie_page = "'.$our_host.$our_instance.'/admin_pc_add_2.php"'.$config_newline; 

// Not used for a local audit.
$this_config=$this_config.'input_file = ""'.$config_newline; 

// Doesn't make sense to set the email stuff, it will fail gracefully
// send_email
$this_config=$this_config.'send_email = FALSE'.$config_newline;    


$this_config=$this_config.'email_to = "openaudit@mydonain.com"'.$config_newline;    
$this_config=$this_config.'email_from = "openaudit@mydonain.com"'.$config_newline;
$this_config=$this_config.'email_sender = "Open Audit"'.$config_newline;
$this_config=$this_config.'email_server = "mail.mydomain.com"'.$config_newline;  
$this_config=$this_config.'email_port = "25"'.$config_newline;                
$this_config=$this_config.'email_auth = "1"'.$config_newline;
$this_config=$this_config.'email_user_id = "openaudit@mydonain.com"'.$config_newline;
$this_config=$this_config.'email_user_pwd = "MailPassword"'.$config_newline;
$this_config=$this_config.'email_use_ssl = "false"'.$config_newline;
$this_config=$this_config.'email_timeout = "60"'.$config_newline;

//FIXME We should parse the audit.config in scripts for some of this, particularly uuid_type
// Ignore the rest too since currently this is for single machines
$this_config=$this_config.'audit_local_domain = "n"'.$config_newline;
$this_config=$this_config.'domain_type = "ldap"'.$config_newline;
$this_config=$this_config.'local_domain = "LDAP://mydomain.local"'.$config_newline; 
$this_config=$this_config.'hfnet = "n"'.$config_newline; 
$this_config=$this_config.'Count = 0'.$config_newline; 
$this_config=$this_config.'number_of_audits = 10'.$config_newline; 
$this_config=$this_config.'script_name = "'.$application.'"'.$config_newline; 
$this_config=$this_config.'monitor_detect = "y"'.$config_newline; 
$this_config=$this_config.'printer_detect = "y"'.$config_newline; 
$this_config=$this_config.'software_audit = "y"'.$config_newline; 
$this_config=$this_config.'uuid_type = "uuid"'.$config_newline;

// We could figure out suitable defaults for this though 
$this_config=$this_config.'nmap_subnet = "192.168.0."'.$config_newline;            
$this_config=$this_config.'nmap_subnet_formatted = "192.168.000."'.$config_newline; 

// Set to the OA host and instance of OA
$this_config=$this_config.'nmap_ie_form_page = "'.$our_host.$our_instance.'/admin_nmap_input.php"'.$config_newline;
$this_config=$this_config.'nmap_ie_visible = "n"'.$config_newline;
$this_config=$this_config.'nmap_ie_auto_close = "y"'.$config_newline;
$this_config=$this_config.'nmap_ip_start = 1'.$config_newline;
$this_config=$this_config.'nmap_ip_end = 254'.$config_newline;

// Use this option to always destroy the audit.config and thus force a request for a fresh one at each run.  
$this_config=$this_config.'keep_this_config = "y"'.$config_newline;

//Use this option to always keep the log of what was audited.
$this_config=$this_config.'keep_audit_log = "y"'.$config_newline;

// We can use this info to modify script actions.
// Note: The requesting host will be blank if Apache or IIS is not confiured to do hostname lookups.
// in other words your web server must be configured to create this variable.
//For example in Apache you'll need HostnameLookups On  inside httpd.conf for it to exist. See also gethostbyaddr().
//
$this_config=$this_config.'requesting_host = "'.$remote_host.'"'.$config_newline;

// Therefore this is more likley to be used, unless you use dynamic addressing, in which case you will have to use requesting_host
$this_config=$this_config.'requesting_addr = "'.$remote_addr.'"'.$config_newline;


// We have everything we need, so now we throw back a the page by echoing out $this_config
echo $this_config;

?>
