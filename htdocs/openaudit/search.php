<?php
$page = "";
$extra = "";
$software = "";
$count = 0;
if (isset($_GET['software'])) {$software = $_GET['software'];} else {}
if (isset($_GET['sort'])) {$sort = $_GET['sort'];} else {$sort= "system_name";}
include "include.php";

echo "<td valign=\"top\">\n";
echo "<div class=\"main_each\">";


$title = "";
if (isset($_GET["show_all"])){ $count_system = '10000'; } else {}
if (isset($_GET["page_count"])){ $page_count = $_GET["page_count"]; } else { $page_count = 0;}
$page_prev = $page_count - 1;
if ($page_prev < 0){ $page_prev = 0; } else {}
$page_next = $page_count + 1;
$page_current = $page_count;
$page_count = $page_count * $count_system;

echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
echo " <tr>\n  <td align=\"left\" class=\"contenthead\" >".__("System Search Results")."<br />&nbsp;</td>\n";
//include "include_list_buttons.php";
echo " </tr>\n</table>\n";

echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
echo "<tr>\n";
echo "  <td width=\"150\"><b>&nbsp;".__("IP Address")."</b></td>\n";
echo "  <td width=\"130\"><b>&nbsp;".__("Hostname")."</b></td>\n";
echo "  <td width=\"150\"><b>&nbsp;".__("Field")."</b></td>\n";
echo "  <td><b>&nbsp;".__("Result")."</b></td>\n";
echo "</tr>";

$search = (isset($_GET["search_field"])) ? stripslashes($_GET["search_field"]) : stripslashes($_POST["search_field"]);
$search = mysqli_real_escape_string($db,$search);
$search = strtoupper($search);

if ($search != "") {


//jbsclm
//    $sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, bios_asset_tag, bios_description, bios_manufacturer, bios_serial_number FROM system, bios WHERE ";
//    $sql .= "bios_uuid = system_uuid AND ";
//    $sql .= "bios_timestamp = system_timestamp AND (";
//    $sql .= "bios_asset_tag LIKE '%$search%' OR ";
//    $sql .= "bios_description LIKE '%$search%' OR ";
//    $sql .= "bios_manufacturer LIKE '%$search%' OR ";
//    $sql .= "bios_serial_number LIKE '%$search%')";
	
    $sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, bios_asset_tag, bios_description, bios_manufacturer, bios_serial_number FROM bios LEFT JOIN system ON bios_uuid = system_uuid AND bios_timestamp = system_timestamp WHERE ";
    $sql .= "bios_asset_tag LIKE '%$search%' OR ";
    $sql .= "bios_description LIKE '%$search%' OR ";
    $sql .= "bios_manufacturer LIKE '%$search%' OR ";
    $sql .= "bios_serial_number LIKE '%$search%'";
//jbsclm end	
	
	
	
    $result = mysqli_query($db,$sql);
    if ($myrow = mysqli_fetch_array($result)){
      do {
        if(!isset($myrow["software_name"])) $myrow["software_name"]=" ";
        if (strpos(strtoupper($myrow["bios_description"]), $search) !== false){$search_field = "Bios Description"; $search_result = $myrow["bios_description"] . " - " . $myrow["software_name"];}
        if (strpos(strtoupper($myrow["bios_manufacturer"]), $search) !== false){$search_field = "Bios Manufacturer"; $search_result = $myrow["bios_manufacturer"];}
        if (strpos(strtoupper($myrow["bios_serial_number"]), $search) !== false){$search_field = "Bios Serial"; $search_result = $myrow["bios_serial_number"];}
        if (strpos(strtoupper($myrow["bios_asset_tag"]), $search) !== false){$search_field = "Asset Tag"; $search_result = $myrow["bios_asset_tag"];}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
} else {}
// Added net_user_name AJH 01 Aug 2006
$sql  = "SELECT DISTINCT system_uuid, system_name, net_ip_address, net_domain, net_user_name, system_model, system_primary_owner_name, system_system_type, ";
$sql .= "system_id_number, system_vendor, time_caption, system_os_name, system_country_code, system_description, ";
$sql .= "system_organisation, system_registered_user, system_serial_number, system_version, system_windows_directory ";
$sql .= "FROM system WHERE ";
$sql .= "system_name LIKE '%$search%' OR ";
// removed by ef $sql .= "net_ip_address LIKE '%$search%' OR ";
$sql .= "net_domain LIKE '%$search%' OR ";
$sql .= "net_user_name LIKE '%$search%' OR ";
$sql .= "system_model LIKE '%$search%' OR ";
$sql .= "system_primary_owner_name LIKE '%$search%' OR ";
$sql .= "system_system_type LIKE '%$search%' OR ";
$sql .= "system_id_number LIKE '%$search%' OR ";
$sql .= "system_vendor LIKE '%$search%' OR ";
$sql .= "time_caption LIKE '%$search%' OR ";
$sql .= "system_os_name LIKE '%$search%' OR ";
$sql .= "system_country_code LIKE '%$search%' OR ";
$sql .= "system_description LIKE '%$search%' OR ";
$sql .= "system_organisation LIKE '%$search%' OR ";
$sql .= "system_registered_user LIKE '%$search%' OR ";
$sql .= "system_serial_number LIKE '%$search%' OR ";
$sql .= "system_version LIKE '%$search%' OR ";
$sql .= "system_windows_directory LIKE '%$search%' ";
$sql .= "ORDER BY system_name";
$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if (strpos(strtoupper($myrow["system_name"]), $search) !== false){$search_field = "System Name"; $search_result = $myrow["system_name"];}
    // removed by ef if (strpos(strtoupper($myrow["net_ip_address"]), $search) !== false){$search_field = "IP Address"; $search_result = $myrow["net_ip_address"];}
    if (strpos(strtoupper($myrow["net_domain"]), $search) !== false){$search_field = "Domain"; $search_result = $myrow["net_domain"];}
    if (strpos(strtoupper($myrow["net_user_name"]), $search) !== false){$search_field = "Network User"; $search_result = $myrow["net_user_name"];}
    if (strpos(strtoupper($myrow["system_model"]), $search) !== false){$search_field = "System Model"; $search_result = $myrow["system_model"];}
    if (strpos(strtoupper($myrow["system_primary_owner_name"]), $search) !== false){$search_field = "Registered Owner"; $search_result = $myrow["system_primary_owner_name"];}
    if (strpos(strtoupper($myrow["system_system_type"]), $search) !== false){$search_field = "System Type"; $search_result = $myrow["system_system_type"];}
    if (strpos(strtoupper($myrow["system_id_number"]), $search) !== false){$search_field = "ID Number"; $search_result = $myrow["system_id_number"];}
    if (strpos(strtoupper($myrow["system_vendor"]), $search) !== false){$search_field = "System Manufacturer"; $search_result = $myrow["system_vendor"];}
    if (strpos(strtoupper($myrow["time_caption"]), $search) !== false){$search_field = "Time Zone"; $search_result = $myrow["time_caption"];}
    if (strpos(strtoupper($myrow["system_os_name"]), $search) !== false){$search_field = "Operating System"; $search_result = $myrow["system_os_name"];}
    if (strpos(strtoupper($myrow["system_country_code"]), $search) !== false){$search_field = "Country"; $search_result = $myrow["system_country_code"];}
    if (strpos(strtoupper($myrow["system_description"]), $search) !== false){$search_field = "Description"; $search_result = $myrow["system_description"];}
    if (strpos(strtoupper($myrow["system_organisation"]), $search) !== false){$search_field = "Registered Organisation"; $search_result = $myrow["system_organisation"];}
    if (strpos(strtoupper($myrow["system_registered_user"]), $search) !== false){$search_field = "Registered User"; $search_result = $myrow["system_registered_user"];}
    if (strpos(strtoupper($myrow["system_serial_number"]), $search) !== false){$search_field = "Serial Number"; $search_result = $myrow["system_serial_number"];}
    if (strpos(strtoupper($myrow["system_version"]), $search) !== false){$search_field = "System Version"; $search_result = $myrow["system_version"];}
    if (strpos(strtoupper($myrow["system_windows_directory"]), $search) !== false){$search_field = "Windows Directory"; $search_result = $myrow["system_windows_directory"];}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
} else {}
$search_field = "";
$search_result = "";


//jbsclm
//$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, monitor_manufacturer, monitor_model, monitor_serial FROM system, monitor WHERE ";
//$sql .= "monitor_uuid = system_uuid AND ";
//$sql .= "monitor_timestamp = system_timestamp AND (";
//$sql .= "monitor_manufacturer LIKE '%$search%' OR ";
//$sql .= "monitor_model LIKE '%$search%' OR ";
//$sql .= "monitor_serial LIKE '%$search%')";


$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, monitor_manufacturer, monitor_model, monitor_serial FROM monitor LEFT JOIN system ON monitor_uuid = system_uuid AND monitor_timestamp = system_timestamp WHERE ";
$sql .= "monitor_manufacturer LIKE '%$search%' OR ";
$sql .= "monitor_model LIKE '%$search%' OR ";
$sql .= "monitor_serial LIKE '%$search%'";
//jbsclm end





$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if (strpos(strtoupper($myrow["monitor_manufacturer"]), $search) !== false){$search_field = "Monitor Manufacturer"; $search_result = $myrow["monitor_manufacturer"];}
    if (strpos(strtoupper($myrow["monitor_model"]), $search) !== false){$search_field = "Monitor Model"; $search_result = $myrow["monitor_model"];}
    if (strpos(strtoupper($myrow["monitor_serial"]), $search) !== false){$search_field = "Monitor Serial"; $search_result = $myrow["monitor_serial"];}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
} else {}
// jbsclm
//$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, software_name, software_publisher, software_version FROM system, software WHERE ";
//$sql .= "software_uuid = system_uuid AND ";
//$sql .= "software_timestamp = system_timestamp AND (";
//$sql .= "software_name LIKE '%$search%' OR ";
//$sql .= "software_publisher LIKE '%$search%' OR ";
//$sql .= "software_version LIKE '%$search%')";
$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, software_name, software_publisher, software_version FROM software LEFT JOIN system ON software_uuid = system_uuid AND software_timestamp = system_timestamp WHERE ";
$sql .= "software_name LIKE '%$search%' OR ";
$sql .= "software_publisher LIKE '%$search%' OR ";
$sql .= "software_version LIKE '%$search%'";
//jbsclm end




$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if (strpos(strtoupper($myrow["software_publisher"]), $search) !== false){$search_field = "Software Publisher"; $search_result = $myrow["software_publisher"] . " - " . $myrow["software_name"];}
    if (strpos(strtoupper($myrow["software_name"]), $search) !== false){$search_field = "Software Name"; $search_result = $myrow["software_name"];}
    if (strpos(strtoupper($myrow["software_version"]), $search) !== false){$search_field = "Software Version"; $search_result = $myrow["software_version"];}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
} else {}


//jbsclm
//$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, usb_description FROM system, usb WHERE ";
//$sql .= "usb_uuid = system_uuid AND ";
//$sql .= "usb_timestamp = system_timestamp AND (";
//$sql .= "usb_description LIKE '%$search%')";

$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, usb_description FROM usb LEFT JOIN system ON usb_uuid = system_uuid AND usb_timestamp = system_timestamp WHERE ";
$sql .= "usb_description LIKE '%$search%'";
//jbsclm end



$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if(!isset($myrow["software_name"])) $myrow["software_name"]=" ";
    if (strpos(strtoupper($myrow["usb_description"]), $search) !== false){$search_field = "USB Description"; $search_result = $myrow["usb_description"] . " - " . $myrow["software_name"];}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
} else {}


//jbsclm
//$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, video_description FROM system, video WHERE ";
//$sql .= "video_uuid = system_uuid AND ";
//$sql .= "video_timestamp = system_timestamp AND (";
//$sql .= "video_description LIKE '%$search%')";


$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, video_description FROM video LEFT JOIN system ON video_uuid = system_uuid AND video_timestamp = system_timestamp WHERE ";
$sql .= "video_description LIKE '%$search%'";
//jbsclm end




$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if(!isset($myrow["software_name"])) $myrow["software_name"]=" ";
    if (strpos(strtoupper($myrow["video_description"]), $search) !== false){$search_field = "Video Description"; $search_result = $myrow["video_description"] . " - " . $myrow["software_name"];}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
} else {}

// Search for Services 
//$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, service_timestamp, service_uuid, service_name, service_display_name, service_started, sd_description, sd_display_name  FROM system, service, service_details WHERE ";
//$sql .= "service_uuid = system_uuid AND ";
//$sql .= "service_display_name = sd_display_name AND ";
//$sql .= "service_timestamp = system_timestamp AND (";
//$sql .= "service_name LIKE '%$search%' OR ";
//$sql .= "sd_description LIKE '%$search%' OR ";
//$sql .= "service_display_name LIKE '%$search%')";


//jbsclm

$sql = "SELECT DISTINCT system_name, system_uuid, net_ip_address, service_timestamp, service_uuid, service_name, service_display_name, service_started, sd_description, sd_display_name  FROM service LEFT JOIN service_details on service_display_name = sd_display_name  left join system on service_uuid = system_uuid AND service_timestamp = system_timestamp  WHERE ";
$sql .= "service_name LIKE '%$search%' OR ";
$sql .= "service_display_name LIKE '%$search%' ";
$sql .= "UNION ";
$sql .= "SELECT DISTINCT system_name, system_uuid, net_ip_address, service_timestamp, service_uuid, service_name, service_display_name, service_started, sd_description, sd_display_name  FROM service_details LEFT JOIN service on service_display_name = sd_display_name  left join system on service_uuid = system_uuid AND service_timestamp = system_timestamp  WHERE ";
$sql .= "sd_description LIKE '%$search%'";

//jbsclm

$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if (strpos(strtoupper($myrow["service_name"]), $search) !== false){$search_field = "Service Name"; $search_result = $myrow["service_name"];}
    if (strpos(strtoupper($myrow["service_display_name"]), $search) !== false){$search_field = "Service Full Name"; $search_result = $myrow["service_display_name"];}
    if (strpos(strtoupper($myrow["sd_description"]), $search) !== false){$search_field = "Service Description"; $search_result = $myrow["sd_description"]." [".__("Service Started")."=".$myrow["service_started"]."]";}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]),  $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
} else {}
//


//jbsclm
//$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, system_man_description, //system_man_location, system_man_value, system_man_serial_number FROM system, system_man WHERE ";
//$sql .= "system_man_uuid = system_uuid AND (";
//$sql .= "system_man_description LIKE '%$search%' OR ";
//$sql .= "system_man_location LIKE '%$search%' OR ";
//$sql .= "system_man_serial_number LIKE '%$search%')";

$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, system_man_description, system_man_location, system_man_value, system_man_serial_number FROM system_man LEFT JOIN system ON system_man_uuid = system_uuid WHERE ";
$sql .= "system_man_description LIKE '%$search%' OR ";
$sql .= "system_man_location LIKE '%$search%' OR ";
$sql .= "system_man_serial_number LIKE '%$search%'";
//jbsclm end


$result = mysqli_query($db,$sql);
    if ($myrow = mysqli_fetch_array($result)){
      do {
        if (strpos(strtoupper($myrow["system_man_description"]), $search) !== false){$search_field = "Manual Description"; $search_result = $myrow["system_man_description"];}
        if (strpos(strtoupper($myrow["system_man_location"]), $search) !== false){$search_field = "Manual Location"; $search_result = $myrow["system_man_location"];}
        if (strpos(strtoupper($myrow["system_man_serial_number"]), $search) !== false){$search_field = "System Serial Number"; $search_result = $myrow["system_man_serial_number"];}
        $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
        $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
   } while ($myrow = mysqli_fetch_array($result));
}

// Search for MAC address, description or manufacturer into "Other" table
$sql  = "SELECT DISTINCT other_network_name, other_id, other_ip_address, other_mac_address, other_description, other_manufacturer FROM other WHERE ";
$sql .= "other_mac_address LIKE '%$search%' OR ";
$sql .= "other_description LIKE '%$search%' OR ";
$sql .= "other_manufacturer LIKE '%$search%'";

$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if (strpos(strtoupper($myrow["other_mac_address"]), $search) !== false){$search_field = "Device MAC Address"; $search_result = $myrow["other_mac_address"];}
    if (strpos(strtoupper($myrow["other_description"]), $search) !== false){$search_field = "Device Description"; $search_result = $myrow["other_description"];}
    if (strpos(strtoupper($myrow["other_manufacturer"]), $search) !== false){$search_field = "Device Manufacturer"; $search_result = $myrow["other_manufacturer"];}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["other_network_name"], $myrow["other_id"], ip_trans($myrow["other_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));

} else {}

// Search for IP address into "system"  table

$search_padded = ip_trans_to($search);

$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address ";
$sql .= "FROM system WHERE ";
$sql .= "net_ip_address LIKE '%$search%' OR ";
$sql .= "net_ip_address LIKE '%$search_padded%'";

$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if (strpos(strtoupper($myrow["net_ip_address"]), $search) !== false)
      {$search_field = "IP Address"; $search_result = $myrow["net_ip_address"];}
   else
      {if (strpos(strtoupper($myrow["net_ip_address"]), $search_padded) !== false)
        {$search_field = "IP Address"; $search_result = ip_trans($myrow["net_ip_address"]);}
    }
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));

} else {}

// Search for IP address into "other" table

$search_padded = ip_trans_to($search);

$sql  = "SELECT DISTINCT other_network_name, other_id, other_ip_address ";
$sql .= "FROM other WHERE ";
$sql .= "other_ip_address LIKE '%$search%' OR ";
$sql .= "other_ip_address LIKE '%$search_padded%'";

$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if (strpos(strtoupper($myrow["other_ip_address"]), $search) !== false)
      {$search_field = "Device IP Address"; $search_result = $myrow["other_ip_address"];}
   else
      {if (strpos(strtoupper($myrow["other_ip_address"]), $search_padded) !== false)
        {$search_field = "Device IP Address"; $search_result = ip_trans($myrow["other_ip_address"]);}
    }
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["other_network_name"], $myrow["other_id"], ip_trans($myrow["other_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));

} else {}



//jbs clm
//$sql  = "SELECT DISTINCT system_name, system_uuid, system.net_ip_address, net_mac_address, net_driver_provider, net_driver_version, net_driver_date FROM system, network_card WHERE ";
//$sql .= "net_uuid = system_uuid AND ";
//$sql .= "net_timestamp = system_timestamp AND (";
//$sql .= "net_mac_address LIKE '%$search%' OR ";
//$sql .= "net_driver_provider LIKE '%$search%' OR ";
//$sql .= "net_driver_version LIKE '%$search%' OR ";
//$sql .= "net_driver_date LIKE '%$search%')";

$sql  = "SELECT DISTINCT system_name, system_uuid, system.net_ip_address, net_mac_address, net_driver_provider, net_driver_version, net_driver_date FROM network_card LEFT JOIN system ON net_uuid = system_uuid AND net_timestamp = system_timestamp WHERE ";
$sql .= "net_mac_address LIKE '%$search%' OR ";
$sql .= "net_driver_provider LIKE '%$search%' OR ";
$sql .= "net_driver_version LIKE '%$search%' OR ";
$sql .= "net_driver_date LIKE '%$search%'";
//jbsclm end




$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if(!isset($myrow["software_name"])) $myrow["software_name"]=" ";
    if (strpos(strtoupper($myrow["net_mac_address"]), $search) !== false){$search_field = "System MAC Address"; $search_result = $myrow["net_mac_address"];}
    if (strpos(strtoupper($myrow["net_driver_provider"]), $search) !== false){$search_field = "Network Driver Provider"; $search_result = $myrow["net_driver_provider"];}
    if (strpos(strtoupper($myrow["net_driver_version"]), $search) !== false){$search_field = "Network Driver Version"; $search_result = $myrow["net_driver_version"];}
    if (strpos(strtoupper($myrow["net_driver_date"]), $search) !== false){$search_field = "Network Driver Date"; $search_result = $myrow["net_driver_date"];}
  
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
 } else {} 

// Search for motherboard
$sql  = "SELECT DISTINCT system_name, system_uuid, net_ip_address, motherboard_manufacturer, motherboard_product ";
$sql .= "FROM system, motherboard WHERE ";
$sql .= "motherboard_uuid = system_uuid AND motherboard_timestamp = system_timestamp AND (";
$sql .= "motherboard_manufacturer LIKE '%$search%' OR ";
$sql .= "motherboard_product LIKE '%$search%') ";

$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do {
    if (strpos(strtoupper($myrow["motherboard_manufacturer"]), $search) !== false){$search_field = "Motherboard manufacturer"; $search_result = $myrow["motherboard_manufacturer"];}
    if (strpos(strtoupper($myrow["motherboard_product"]), $search) !== false){$search_field = "Motherboard Product"; $search_result = $myrow["motherboard_product"];}
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    $result_set[] = array($myrow["system_name"], $myrow["system_uuid"], ip_trans($myrow["net_ip_address"]), $search_field, $search_result);
  } while ($myrow = mysqli_fetch_array($result));
} else {}
// End search for motherboard
 
} else {} // end if search != ""

if(isset($result_set) AND $result_set) {
  sort($result_set);
  $count = count ($result_set);
  for ($i=0; $i<$count; $i++){
    $countmore=count($result_set[0]);
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
    echo "<tr bgcolor=\"$bgcolor\">";
    echo "<td>&nbsp;" . $result_set[$i][2] . "&nbsp;</td>";
    $result_type = substr($result_set[$i][3],0,6);
    if ($result_type == "Device"){
    echo "<td>&nbsp;<a href=\"system.php?other=" . $result_set[$i][1] . "&view=other_system\">" . $result_set[$i][0] . "</a>&nbsp;</td>";
    } else     {
    echo "<td>&nbsp;<a href=\"system.php?pc=" . $result_set[$i][1] . "&view=summary\">" . $result_set[$i][0] . "</a>&nbsp;</td>";
    }
    echo "<td>&nbsp;" . $result_set[$i][3] . "&nbsp;</td>";
    echo "<td>&nbsp;" . $result_set[$i][4] . "&nbsp;</td>";
    echo "</tr>\n";
  }
  echo "<tr><td colspan=\"2\"><br /><b>".__("Results").": " . $count . "</b></td></tr>\n";
}
else {
  echo "<tr><td colspan=\"4\">".__("No Results")."</td></tr>\n";
}

echo "</table>\n";
echo "</div>\n";
echo "</td>\n";
// include "include_right_column.php";
echo "</body>\n";
echo "</html>\n";
?>
