<?php

set_time_limit(60);
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require "include_config.php";
require "include_audit_functions.php";
require_once "include_functions.php";
require_once "application_class.php";
error_reporting(0);

$db     = GetOpenAuditDbConnection();
$action = ( isset($_POST["action"]) ) ? $_POST["action"] : $_GET["action"]; 

switch ($action) {
  case "delete_config":   exit(Delete_Audit_Configuration($db));
  case "delete_schedule": exit(Delete_Audit_Schedule($db));
  case "toggle_schedule": exit(Toggle_Audit_Schedule($db));
  case "ws_status":       exit(Web_Schedule_Status('0',$db));
  case "toggle_ws":       exit(Toggle_Web_Schedule_Service($db));
  case "run_config":      exit(Run_Audit_Configuration($db));
  case "ws_log":          exit(Get_Web_Schedule_Log_Entries($db));
}

/**********************************************************************************************************
Function Name:
  Delete_Audit_Configuration
Description:
  Delete an audit configuration from the database
Arguments:
  $db        [IN] [Resource] DB connection 
Returns:    
  [String]  XML string containing the success status of the operation
**********************************************************************************************************/
function Delete_Audit_Configuration($db) {
	header("Content-type: text/xml");
  $config_id = $_GET["config_id"];
  $xml = "<DeleteConfiguration>";

  $schedules = GetAuditSchedulesFromDb();

  if ( !is_null($schedules) ) {
    foreach ( $schedules as $id => $cfg ) {
      if ( $cfg['config_id'] == $config_id ) {
        $xml .= '<result>false</result>';
        $xml .= '<message>Configuration still associated with a schedule</message>';
        return $xml . '</DeleteConfiguration>';
      }
    }
  }

  $configs = GetAuditConfigurationsFromDb();

  if ( !is_null($configs[$config_id]) ){
    /* Delete the configuration */
    $sql = "DELETE FROM audit_configurations WHERE `audit_cfg_id` = '".$config_id."'";
    if ( mysqli_query($db,$sql) ) {
      /* Delete logs associated with it */
      $sql  = "DELETE FROM audit_log WHERE `audit_log_config_id` = '".$config_id."'";
      @mysqli_query($db,$sql);

      /* Delete MySQL queries associated with it */
      $sql  = "DELETE FROM mysqli_queries WHERE `mysqli_queries_cfg_id` = '".$config_id."'";
      @mysqli_query($db,$sql);

      $xml .= "<result>true</result>";
      $xml .= "<message>Deleted Configuration: {$configs[$config_id]['name']}</message>";
    }
    else {
      $xml .= "<result>false</result>";
      $xml .= "<message>Unexpected MySQL Error</message>";
    }
  }
  else {
    $xml .= '<result>true</result><message>Configuration not found in DB</message>';
  }

  $xml .= "</DeleteConfiguration>";
  return $xml;
}

/**********************************************************************************************************
Function Name:
  Run_Audit_Configuration
Description:
  Run the specified configuration now
Arguments:
  $db        [IN] [Resource] DB connection 
Returns:    
  [String]  XML string containing the success status of the operation
**********************************************************************************************************/
function Run_Audit_Configuration($db) {
	header("Content-type: text/xml");
  $xml = '<AuditRun>';

  $config_id = $_GET["config_id"];
  $configs   = GetAuditConfigurationsFromDb();

  global $TheApp;

  if ( !is_null($configs[$config_id]) ) {
    $audit_bin = Get_Audit_Bin();
    $arguments = "--url-path \"" . GetUrlPath() ."\" --run-config $config_id $tonull";
    $tonull    = ( $TheApp->OS == 'Windows' ) ? '> NUL 2>&1' : '> /dev/null 2>&1';

    if ( is_null($audit_bin) ) {
      $xml .= '<result>false</result><message>Cannot find audit.pl/audit/audit.exe</message>';
    }
    else {
      if ( $TheApp->OS == 'Windows' ) {
        $retval = pclose(popen("start /b $audit_bin --run-config $config_id $tonull", 'r')); 
      }
      else {
        system("$audit_bin --daemon --run-config $config_id $tonull", $retval);
      }

      if ( $retval != 0 ) {
       $xml .= "<result>false</result>";
       $xml .= "<message>Failed to run: {$configs[$config_id]['name']} ($retval)</message>";
      }
      else {
       $xml .= "<result>false</result>";
       $xml .= "<message>Running configuration: {$configs[$config_id]['name']}</message>";
      }
    }
  }
  else {
    $xml .= "<result>false</result>";
    $xml .= "<message>Configuration not found in DB</message>";
  }
  $xml .= '</AuditRun>';

  return $xml;
}

/**********************************************************************************************************
Function Name:
  Delete_Audit_Schedule
Description:
  Delete the specified audit schedule, and any logs, from the database
Arguments:
  $db       [IN] [Integer]  The audit schedule to delete from the database
Returns:    
  [String]  XML string containing the success status of the operation
**********************************************************************************************************/
function Delete_Audit_Schedule($db) {
	header("Content-type: text/xml");
  $xml = "<DeleteSchedule>";

  $sched_id = $_GET["schedule_id"];

  $schedules = GetAuditSchedulesFromDb();

  if ( !is_null($schedules[$sched_id]) ) {
    $sched_name = $schedules[$sched_id]['name'];
    $sql     = "DELETE FROM audit_schedules WHERE `audit_schd_id` = '$sched_id'";

    if ( mysqli_query($db,$sql) ) {
      /* Delete logs associated with it */
      $sql  = "DELETE FROM audit_log WHERE `audit_log_schedule_id` = '$sched_id'";
      @mysqli_query($db,$sql);

      $xml .= "<result>true</result>";
      $xml .= "<message>Deleted Schedule: $sched_name</message>";
    }
    else {
      $xml .= "<result>false</result>";
      $xml .= "<message>Unexpected MySQL Error</message>";
    }
  }
  else {
    $xml .= "<result>true</result>";
    $xml .= "<message>Schedule Does Not Exist in DB</message>" ;
  }

  $xml .= "</DeleteSchedule>";

  return $xml;
}

/**********************************************************************************************************
Function Name:
  Toggle_Audit_Schedule
Description:
  Activate/Deactivate a schedule in the database
Arguments:
  $db       [IN] [Resource] DB connection 
Returns:    
  [String]  XML string containing the success status of the operation
**********************************************************************************************************/
function Toggle_Audit_Schedule($db) {
	header("Content-type: text/xml");
  $xml = "<ToggleSchedule>";

  $id  = $_GET["schedule_id"];
  $cfg = GetAuditSchedulesFromDb();

  if ( !is_null($cfg[$id]) ) {
    $sched_set = ( $cfg[$id]['active'] ) ? '0' : '1'; 
    $action    = ( $cfg[$id]['active'] ) ? 'Deactivated' : 'Activated'; 

    $sql  = "UPDATE audit_schedules SET audit_schd_active='$sched_set'
             WHERE `audit_schd_id` = '$id'";

    $result  =  ( mysqli_query($db,$sql) ) ? 'true' : 'false';
    $xml    .=  "<result>$result</result><action>$action</action>";
    $xml    .=  ( $result == 'true' ) ?
      "<message>$action Schedule: {$cfg[$id]['name']}</message>" :
      "<message>Unexpected MySQL Error</message>" ;
  }
  else {
    $xml .=  "<result>false</result><action></action>";
    $xml .=  "<message>Schedule does not exist in DB</message>";
  }

  $xml .= '</ToggleSchedule>';

  return $xml;
}

/**********************************************************************************************************
Function Name:
  Toggle_Web_Schedule_Service
Description:
  Stop/Start the web schedule service
Arguments:
  $db [IN] [Resource] DB connection 
Returns:    
  [String]  XML string containing the success status of the operation
**********************************************************************************************************/
function Toggle_Web_Schedule_Service($db) {
	header("Content-type: text/xml");
  $xml = "<ToggleWebSchedule>";

  global $TheApp;

  $audit_bin = Get_Audit_Bin();
  $audit_cfg = GetAuditSettingsFromDb();
  $tonull    = ( $TheApp->OS == 'Windows' ) ? '> NUL 2>&1' : '> /dev/null 2>&1';
  $arguments = "--url-path \"" . GetUrlPath() ."\" --cron-start --daemon $tonull"; 

  $cmd = array (
    "Start"         => "$audit_bin $arguments",
    "Stop"          => "$audit_bin --cron-stop $tonull",
    "Windows_Start" => "net start \"{$audit_cfg['service_name']}\" $tonull",
    "Windows_Stop"  => "net stop \"{$audit_cfg['service_name']}\" $tonull",
  );

  if ( Web_Schedule_Status('1',$db) == 'running' ) {
    $action = "stop";

    if ( $TheApp->OS == 'Windows' ) {
      if ( $audit_cfg['service_enabled'] ) {
        system($cmd['Windows_Stop'], $retval);
        $result = ( $retval ) ? 'false' : 'true';
      }
      else {
        $sql = "UPDATE audit_settings SET audit_settings_active='0'";
        $result = ( mysqli_query($db,$sql) ) ? 'pending' : 'false';
      }
    }
    else {
      system($cmd['Stop'], $retval);
      $result = ( $retval ) ? 'false' : 'true';
    }
  }
  else {
    $action = "start";

    if ( $TheApp->OS == 'Windows' and !$audit_cfg['service_enabled'] ) {
      $retval = pclose(popen("start /b {$cmd["Start"]}", 'r'));
      $result = ( $retval ) ? 'false' : 'true';
    }
    else {
      if ( $TheApp->OS == 'Windows' and $audit_cfg['service_enabled'] ) {
        system($cmd['Windows_Start'], $retval);
      }
      else{
        system($cmd['Start'], $retval);
      }
      $result = ( $retval ) ? 'false' : 'true';
    }
  }

  $xml .= "<action>$action</action>";
  $xml .= "<result>$result</result>";
  $xml .= "</ToggleWebSchedule>";

  return $xml;
}

/**********************************************************************************************************
Function Name:
  Web_Schedule_Status
Description:
  Get the current status of the web schedule service
Arguments:
  $check [IN] [Integer]  0 if called directly via AJAX
  $db    [IN] [Resource] DB connection 
Returns:    
  [String]  XML string containing the success status of the operation
**********************************************************************************************************/
function Web_Schedule_Status($check,$db) {
	header("Content-type: text/xml");

  $audit_bin = Get_Audit_Bin();
  $audit_cfg = GetAuditSettingsFromDb();

  if ( !$audit_cfg['pid'] ) {
    $result = 'stopped';
  }
  else {
    system("{$audit_bin} --check-pid", $retval);
    $result = ( $retval == 0 ) ? 'running' : 'stopped';
  }

  if ( !$check ) {
	  header("Content-type: text/xml");
    return "<WebScheduleStatus><result>$result</result></WebScheduleStatus>";
  }

  return $result;
}

/**********************************************************************************************************
Function Name:
  Get_Web_Schedule_Log_Entries
Description:
  Return the specified number of web-schedule log entries
Arguments:
  $db       [IN] [Resource] DB connection 
Returns:    
  [String]  HTML to be placed in the log DIV
**********************************************************************************************************/
function Get_Web_Schedule_Log_Entries($db) {
  $num        = $_GET["row_num"];
  $sql        = "SELECT * FROM ws_log ORDER BY ws_log_timestamp DESC LIMIT $num";
  $results    =  @mysqli_query($db,$sql);
  $cron_lines = array();
  $html       = null;

  if (@mysqli_num_rows($results) != 0) {
    while ( $myrow = mysqli_fetch_array($results) ) {
      $message   = $myrow['ws_log_message'];
      $timestamp = date('d/m/y h:i:s a',$myrow['ws_log_timestamp']);
      if ( strlen($message) > 40 ) {
        $message = substr($message,0,40) . "...";
      }
      $html .= $timestamp . " - " . $message . "<br />\n";
    }
  }
  else {
    $html = "<center><strong><i>Your log is currently empty</i></strong></center>";
  }

  return $html;
}

?>
