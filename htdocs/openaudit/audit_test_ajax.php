<?php
/**********************************************************************************************************
  Test various queries/functions on the audit_schedule.php and audit_configuration.php pages
**********************************************************************************************************/

set_time_limit(60);
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require "include_config.php";
require "include_audit_functions.php";
error_reporting(0);

switch ($_POST['type']) {
  case "cron": exit(Get_Next_Run());
  default:     exit(TestSettings());
}

/**********************************************************************************************************
Function Name:
  TestSettings
Description:
  Called from audit_configuration.php via AJAX to test NMAP, LDAP, or MySQL depending on what button was
  pushed.
Arguments: None
Returns:    
  [String] Result of the command in HTML form
**********************************************************************************************************/
function TestSettings() {
  $test_type = $_POST['type'];
  $id = $_POST['config_id'];
  $html = null;

  /* Check if config at least exists */
  $cfg = GetAuditConfigurationsFromDb();
  if (!is_null($cfg) && array_key_exists($id,$cfg)) {
    /* check to make sure the the settings were saved */
    if ( $test_type == "ldap" && $cfg[$id]['type'] != "domain" ) {
      $html .= "The configuration is not set to use LDAP<br />";
      $html .= "Make sure you submit your changes before testing the connection!<br />";
    }
    else if ( $test_type == "mysql" && $cfg[$id]['type'] != "mysql" ) {
      $html .= "The configuration is not set to use MySQL<br />";
      $html .= "Make sure you submit your changes before testing the connection!<br />";
    }
    else if ( $test_type == "nmap" && ( $cfg[$id]['action'] != "nmap" || $cfg[$id]['action'] == "pc_nmap" ) ) {
      $html .= "The configuration is not set to use Nmap<br />";
      $html .= "Type was {$cfg[$id]['type']}<br/>";
      $html .= "Make sure you submit your changes before testing the connection!<br />";
    }
    else {
      $audit_bin = Get_Audit_Bin();

      if ( is_null($audit_bin) ) {
        return "Cannot find an audit.exe/audit.pl/audit file";
      }

      $test_results = array();
      $test = ( $test_type == 'ldap' or $test_type == 'mysql' ) ? 'query' : $test_type ;
      exec("$audit_bin --test-$test $id 2>&1",$test_results);
      foreach ( $test_results as $line ) { $html .= $line . "<br />"; }
    }
  }
  else {
    $html .= "No such audit configuration in the database<br />";
  }

  return $html;
}

/**********************************************************************************************************
Function Name:
  Get_Next_Run
Description:
  Called from audit_schedule.php via AJAX to test the syntax of the cron entry or see when it would run
Arguments: None
  [String] The cron entry to test
Returns:    
  [String] The result, as HTML
**********************************************************************************************************/
function Get_Next_Run() {
  $cron_line = $_POST['cron_line'];
  $next_run  = Verify_Cron_Line($cron_line);

  return ( !is_null($next_run) ) ? date('D M jS Y h:i:s A',$next_run) : "<b>Invalid Cron Entry</b>";
}

?>
