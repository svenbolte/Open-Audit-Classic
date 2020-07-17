<?php
include "include.php";

$tm_start = array_sum(explode(' ', microtime()));
$whoami =  shell_exec('whoami');
$minute = date('i');
$date = date('Y-m-d H:i');


/*
if (substr($whoami,0,4) <> 'root') {
 echo "You are not root.<br />\n";
 echo "This page is not designed to be called from within a browser.<br />\n";
 echo "You should call this page on the command line as root (or setup cron for root to call this page, each minute)<br />\n";
 exit;
} else { 
*/
  // We have been called by the 'root' user (on Ubuntu, use sudo xxx.php).
  // We need root to run nmap on Linux
  $jobs_run = 0;

  if (($minute == "0") AND ($jobs_run == '0')) {
    // Run the 60, 45, 30, 20, 15, 10, 5 and 1 minute jobs
    $jobs_run = 1;
    $sql = "SELECT * FROM scan_type WHERE scan_type_frequency = '1' OR 
                                          scan_type_frequency = '5' OR 
                                          scan_type_frequency = '10' OR 
                                          scan_type_frequency = '15' OR 
                                          scan_type_frequency = '20' OR 
                                          scan_type_frequency = '30' OR 
                                          scan_type_frequency = '45' OR 
                                          scan_type_frequency = '60' ORDER BY scan_type_frequency, scan_type_id";
  } else {
    // Check for other intervals
  }

  if (is_int($minute / 45) AND ($jobs_run == '0')) {
    // Run the 45, 30, 20, 15, 10, 5 and 1 minute jobs
    $jobs_run = 1;
    $sql = "SELECT * FROM scan_type WHERE scan_type_frequency = '1' OR 
                                          scan_type_frequency = '5' OR 
                                          scan_type_frequency = '15' OR 
                                          scan_type_frequency = '45' ORDER BY scan_type_frequency, scan_type_id";
  } else {
    // Check for other intervals
  }
  
  if (is_int($minute / 30) AND ($jobs_run == '0')) {
    // Run the 15, 10, 5 and 1 minute jobs
    $jobs_run = 1;
    $sql = "SELECT * FROM scan_type WHERE scan_type_frequency = '1' OR 
                                          scan_type_frequency = '5' OR 
                                          scan_type_frequency = '10' OR 
                                          scan_type_frequency = '15' OR 
                                          scan_type_frequency = '30' ORDER BY scan_type_frequency, scan_type_id";  
  } else {
    // Check for other intervals
  }

  if (is_int($minute / 20) AND ($jobs_run == '0')) {
    // Run the 15, 10, 5 and 1 minute jobs
    $jobs_run = 1;
    $sql = "SELECT * FROM scan_type WHERE scan_type_frequency = '1' OR 
                                          scan_type_frequency = '5' OR 
                                          scan_type_frequency = '10' OR 
                                          scan_type_frequency = '20' ORDER BY scan_type_frequency, scan_type_id";  
  } else {
    // Check for other intervals
  }

  if (is_int($minute / 15) AND ($jobs_run == '0')) {
    // Run the 15, 10, 5 and 1 minute jobs
    $jobs_run = 1;
    $sql = "SELECT * FROM scan_type WHERE scan_type_frequency = '1' OR 
                                          scan_type_frequency = '5' OR 
                                          scan_type_frequency = '15' ORDER BY scan_type_frequency, scan_type_id";  
  } else {
    // Check for other intervals
  }

  if (is_int($minute / 10) AND ($jobs_run == '0')) {
    // Run the 10, 5 and 1 minute jobs
    $jobs_run = 1;
    $sql = "SELECT * FROM scan_type WHERE scan_type_frequency = '1' OR 
                                          scan_type_frequency = '5' OR 
                                          scan_type_frequency = '10' ORDER BY scan_type_frequency, scan_type_id";  
  } else {
    // Check for other intervals
  }

  if (is_int($minute / 5) AND ($jobs_run == '0')) {
    // Run the 5 & 1 minute jobs
    $jobs_run = 1;
    $sql = "SELECT * FROM scan_type WHERE scan_type_frequency = '1' OR 
                                          scan_type_frequency = '5' ORDER BY scan_type_frequency, scan_type_id";  
  } else {
    // Check for other intervals
  }
  
  if (is_int($minute / 1) AND ($jobs_run == '0')) {
    // Run the 1 minute jobs
    $jobs_run = 1;
    $sql = "SELECT * FROM scan_type WHERE scan_type_frequency = '1' ORDER BY scan_type_frequency, scan_type_id";
  } else {
    // Check for other intervals
  }

  include "include_config.php";
  $db=GetOpenAuditDbConnection() or die("Could not connect");
  mysqli_select_db($db,$mysqli_database) or die("Could not select database");
  $result = mysqli_query($db,$sql);
  if ($myrow = mysqli_fetch_array($result)){
    do{
      if ($myrow["scan_type"] == "port"){
        $result_scan = port_scan($myrow['scan_type_ip_address'],$myrow['scan_type_detail']);
        if (($result_scan == "down") OR ($result_scan == "closed")){
          $success = "n";
        } else {
          $success = "y";
        }
        $sql_insert =  "INSERT INTO scan_log (scan_log_uuid, scan_log_ip_address, scan_log_type, scan_log_detail, ";
        $sql_insert .= "scan_log_frequency, scan_log_date_time, scan_log_result, scan_log_success) VALUES ('";
        $sql_insert .= $myrow['scan_type_uuid'] . "','";
        $sql_insert .= $myrow['scan_type_ip_address'] . "','";
        $sql_insert .= $myrow['scan_type'] . "','";
        $sql_insert .= $myrow['scan_type_detail'] . "','";
        $sql_insert .= $myrow['scan_type_frequency'] . "','";
        $sql_insert .= $date . "','";
        $sql_insert .= $result_scan . "','";
        $sql_insert .= $success . "')";
        $insert = mysqli_query($db,$sql_insert) or die("There was an issue with the insert.");
        $sql_remove  = "DELETE FROM scan_latest WHERE scan_latest_uuid = '" . $myrow['scan_type_uuid'] . "' AND ";
        $sql_remove .= "scan_latest_ip_address = '" . $myrow['scan_type_ip_address'] . "' AND ";
        $sql_remove .= "scan_latest_type = '" . $myrow['scan_type'] . "' AND ";
        $sql_remove .= "scan_latest_detail = '" . $myrow['scan_type_detail'] . "'";
        $remove = mysqli_query($db,$sql_remove) or die("There was an issue with the insert.");
        $sql_insert =  "INSERT INTO scan_latest (scan_latest_uuid, scan_latest_ip_address, scan_latest_type, scan_latest_detail, ";
        $sql_insert .= "scan_latest_frequency, scan_latest_date_time, scan_latest_result, scan_latest_success) VALUES ('";
        $sql_insert .= $myrow['scan_type_uuid'] . "','";
        $sql_insert .= $myrow['scan_type_ip_address'] . "','";
        $sql_insert .= $myrow['scan_type'] . "','";
        $sql_insert .= $myrow['scan_type_detail'] . "','";
        $sql_insert .= $myrow['scan_type_frequency'] . "','";
        $sql_insert .= $date . "','";
        $sql_insert .= $result_scan . "','";
        $sql_insert .= $success . "')";
        $insert = mysqli_query($db,$sql_insert) or die("There was an issue with the insert.");
      }
    } while ($myrow = mysqli_fetch_array($result));
  }
#}

// Make the page for the scan_latest.sql results
// The page is called scan_results_include and contains only table rows.
// this can be called from scan_results.php - or included on index.php, etc
// No need for seperate SQL queries each time a page is called - just include this file
$sql = "SELECT scan_latest_uuid, scan_latest_ip_address, scan_latest_type, scan_latest_detail, scan_latest_date_time, scan_latest_success, system_name FROM scan_latest, system WHERE scan_latest_uuid = system_uuid ORDER BY scan_latest_date_time, scan_latest_success, scan_latest_frequency";
$result = mysqli_query($db,$sql);
$bgcolor = "#FFFFFF";
$content = "<?php \n";
if ($myrow = mysqli_fetch_array($result)){
  do{
    if ($bgcolor == "#F1F1F1") { $bgcolor = "#FFFFFF"; } else { $bgcolor = "#F1F1F1"; }
    if ($myrow['scan_latest_success'] == "y"){
      #$success = "<td align=\\\"center\\\" bgcolor=\\\"green\\\" style=\\\"color: white;\\\">UP</td>";
      $success = "<td align=\\\"center\\\" style=\\\"color: green;\\\"><b>UP</b></td>";
    } else {
      #$success = "<td align=\\\"center\\\" bgcolor=\\\"red\\\" style=\\\"color: white;\\\">DOWN</td>";
      $success = "<td align=\\\"center\\\" style=\\\"color: red;\\\"><b>DOWN</b></td>";
    }
    $content .= "echo \"<tr bgcolor=\\\"" . $bgcolor . "\\\">";
    $content .= "<td><a href=\\\"system.php?pc=" . $myrow['scan_latest_uuid'] . "\\\">" . $myrow['system_name'] . "</a></td>";
    $content .= "<td align=\\\"center\\\">" . $myrow['scan_latest_ip_address'] . "</td>";
    $content .= "<td align=\\\"center\\\">" . $myrow['scan_latest_type'] . "</td>";
    $content .= "<td align=\\\"center\\\">" . $myrow['scan_latest_detail'] . "</td>";
    $content .= "<td align=\\\"center\\\">" . $myrow['scan_latest_date_time'] . "</td>";
    $content .= $success;
    $content .= "</tr>\";\n";
  } while ($myrow = mysqli_fetch_array($result));
}

$content .= "?>";

$filename = "scan_results_include.php";
if (!file_exists($filename) or is_writable($filename)) {
  $handle = @fopen($filename, 'w') or die(writeConfigHtml());
  @fwrite($handle, $content) or die(writeConfigHtml());
  @fclose($handle);
} else {
  echo "Problem creating scan_results.php";
}


function port_scan($ip,$port){
  $nmap_exec = 'nmap -vv ' . $ip . ' -p' . $port;
  $nmap_command = shell_exec($nmap_exec) or die("Failed to evecute nmap command - are you root ?");
  $nmap_input = explode("\n", $nmap_command);
  $test = $port . '/tcp';
  foreach ($nmap_input as $nmap_input_line) {
    if ( substr($nmap_input_line, 0, strlen($test)) == $test) {
      $return = explode(" ", $nmap_input_line);
        return $return[1];
    }  // end of if $port/tcp ($test)
    if ( substr($nmap_input_line, 0, 21) == "Note: Host seems down") {
      return("down");
    }
  }  // End of for each
  return("down"); // if we didn't find anyting, return that the host is down
}

$secs_total = array_sum(explode(' ', microtime())) - $tm_start;
echo "Elapsed time in seconds: " . substr($secs_total, 0, 5) . "<br />";
?> 
<script language="JavaScript">
setTimeout("top.location.href = 'http://localhost/trunk/scan_results.php'",5000);
</script>

