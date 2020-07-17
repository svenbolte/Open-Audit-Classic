<?php
include_once "include_config.php";
include_once "include_functions.php";
include_once "include_lang.php";

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


$sitename = "Software Detected Changes in the Last ".$days_software_detected." Day(s)";
$sitedescription = "Software changes detected by Open Audit.";

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

      $sql  = "SELECT sw.software_id, sw.software_name, sw.software_first_timestamp, sys.system_name, sys.system_uuid, sys.net_ip_address, sys.net_user_name FROM software sw, system sys ";
      $sql .= "WHERE sw.software_first_timestamp >= '" . adjustdate(0,0,-$days_software_detected) . "000000' ";
      $sql .= "AND sys.system_first_timestamp < '" . adjustdate(0,0,-$days_software_detected) . "000000' ";
      $sql .= "AND sw.software_name NOT LIKE '%Hotfix%' AND sw.software_name NOT LIKE '%Service Pack%' AND sw.software_name NOT REGEXP '[KB|Q][0-9]{6,}' ";
      $sql .= "AND sw.software_timestamp = sys.system_timestamp ";
      $sql .= "AND sw.software_uuid = sys.system_uuid ";
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
      echo '<guid isPermaLink="false">openaudit-'.$myrow["software_id"]."</guid>\n";
      echo '<title>'.htmlentities($myrow["software_name"]).'</title>'."\n";
      echo '<link>'.$sitebaseurl.'system.php?pc='.$myrow["system_uuid"].'&amp;view=summary</link>'."\n";
      echo '<description>'.__("Machine Name").' :'.$myrow["system_name"].' '.__("IP Address").':'.ip_trans($myrow["net_ip_address"]).' '.__("User").':'.$myrow["net_user_name"].' </description>'."\n";
      echo '</item>'."\n";

    } while ($myrow = mysqli_fetch_array($result));
  }

  echo '</channel>'."\n";
  echo '</rss>'."\n";
?>
