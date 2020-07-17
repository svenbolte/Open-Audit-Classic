<?php
/**********************************************************************************************************
Module:	rss_hard_disk_alerts.php

Description:
	
		
Changelog:
	
	[Edoardo]	28/05/2010	New page
	
**********************************************************************************************************/
include_once "include_config.php";
include_once "include_functions.php";
include_once "include_lang.php";

function return_datetime($timestamp)
{
$timestamp = substr($timestamp, 0, 4) . "-" . substr($timestamp, 4, 2) . "-" . substr($timestamp, 6, 2) . " " . substr($timestamp, 8, 2) . ":" . substr($timestamp, 10, 2);
return $timestamp;
}

header('Content-type: application/rss+xml');

//Variables
if (isset($use_https) AND $use_https == "y") {
$site_protocol = "https://";
}
else
{
$site_protocol = "http://";
}
$sitebaseurl = $site_protocol  . $_SERVER["SERVER_NAME"]  . dirname($_SERVER["SCRIPT_NAME"]) . "/";

$sitename = "Hard Disks Alerts Detected in the Last ".$hard_disk_alerts_days." Day(s)";
$sitedescription = "Hard Disks Alerts detected by Open Audit.";

//New Translatation-System
if($language=="") $GLOBALS["language"]="en";
$language_file="./lang/".$GLOBALS["language"].".inc";
if(is_file($language_file)){
    include($language_file);
}else{
    die("Language-File not found: ".$language_file);
}

$db=GetOpenAuditDbConnection() or die('Could not connect: ' . mysqli_error($db));
  mysqli_select_db($db,$mysqli_database);

  $sql  = "SELECT system_name, net_ip_address, system_uuid, system_timestamp, hard_drive_index, hard_drive_model, hard_drive_status, hard_drive_predicted_failure FROM system, hard_drive ";
  $sql .= "WHERE hard_drive_uuid = system_uuid AND hard_drive_timestamp = system_timestamp AND system_timestamp > '" . adjustdate(0,0,-$hard_disk_alerts_days) . "000000' "; 
  $sql .= "AND (hard_drive_status <> 'OK' OR hard_drive_predicted_failure = 'Yes') ";
  $sql .= "ORDER BY system_name, hard_drive_index";
  $result = mysqli_query($db,$sql);
  $bgcolor = "#FFFFFF";


   echo  '<rss version="2.0">'."\n";
   echo '<channel>'."\n";
   echo '<image>'."\n";
   echo '<url>'.$sitename.'favicon.ico</url>'."\n";
   echo '</image>'."\n";
   echo '<title>'.$sitename.'</title>'."\n";
   echo '<link>'.$sitebaseurl.'</link>'."\n";
   echo '<description>'.$sitedescription.'</description>'."\n";

  if ($myrow = mysqli_fetch_array($result)){ 
   
   do {
      echo '<item>'."\n";
      echo "<guid isPermaLink=\"false\">openauditnewsys-".$myrow["system_uuid"]."</guid>\n";
      echo '<title>'.$myrow["system_name"].'</title>'."\n";
      echo '<link>'.$sitebaseurl.'system.php?pc='.$myrow["system_uuid"].'&amp;view=summary</link>'."\n";
      echo '<description>'.__("Machine Name").': '.$myrow["system_name"].' '.__("IP Address").': '.ip_trans($myrow["net_ip_address"]).' '.__("Date").': '.return_datetime($myrow["system_timestamp"]).' '.__("HDD index").': '.$myrow["hard_drive_index"].' '.__("HDD model").': '.$myrow["hard_drive_model"].' '.__("HDD status").': '.$myrow["hard_drive_status"].' '.__("S.M.A.R.T. failure").': '.$myrow["hard_drive_predicted_failure"].'</description>'."\n";
      echo '</item>'."\n";

    } while ($myrow = mysqli_fetch_array($result));
  }

  echo '</channel>'."\n";
  echo '</rss>'."\n";
?>
