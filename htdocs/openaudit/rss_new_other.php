<?php
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
$sitebaseurl = $site_protocol  . $_SERVER["SERVER_NAME"] . ':' . $oaserver_port . dirname($_SERVER["SCRIPT_NAME"]) . "/";

$sitename = "New Other Items Detected in the Last ".$other_detected." Day(s)";
$sitedescription = "New Other Items detected by Open Audit.";

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

  $sql  = "SELECT * FROM other WHERE (other_ip_address <> '' AND ";
  $sql .= "other_first_timestamp > '" . adjustdate(0,0,-$other_detected) . "000000') ORDER BY other_ip_address";
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
 // system.php?other=&view=other_system&  
   do {
      echo '<item>'."\n";
      echo "<guid isPermaLink=\"false\">openauditnewsys-".$myrow["other_ip_address"]."</guid>\n";
      echo '<title>'.$myrow["other_network_name"].'</title>'."\n";
      echo '<link>'.$sitebaseurl.'system.php?other='.$myrow["other_id"].'&amp;view=other_system</link>'."\n";
      echo '<description>'.$myrow["other_network_name"].' '.ip_trans($myrow["other_ip_address"]).' '.return_datetime($myrow["other_first_timestamp"]).'</description>'."\n";
      echo '</item>'."\n";
  
    } while ($myrow = mysqli_fetch_array($result));
  }

  echo '</channel>'."\n";
  echo '</rss>'."\n";
?>
