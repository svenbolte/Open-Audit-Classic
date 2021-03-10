<?php
$page = "";
$extra = "";
$software = "";
$count = -1;
if (isset($_GET['software'])) {$software = $_GET['software'];} else {}
if (isset($_GET['sort'])) {$sort = $_GET['sort'];} else {$sort= "system_name";}
include "include.php";

echo "<td valign=\"top\">\n";
echo "<div class=\"main_each\">";
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
echo "<tr>\n";
echo "  <td class=\"contenthead\">Network Monitoring.<br />&nbsp;</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\"><b>Host</b></td>\n";
echo "<td align=\"center\" width=\"20%\"><b>IP Address</b></td>\n";
echo "<td align=\"center\" width=\"20%\"><b>Type</b></td>\n";
echo "<td align=\"center\" width=\"20%\"><b>Detail</b></td>\n";
echo "<td align=\"center\" width=\"20%\"><b>Time</b></td>\n";
echo "</tr>\n";

$bgcolor = "#FFFFFF";
$sql = "SELECT scan_type_uuid, scan_type_ip_address, scan_type, scan_type_detail, scan_type_frequency, system_name FROM scan_type, system WHERE scan_type_uuid = system_uuid ORDER BY system_name, scan_type_frequency, scan_type_id";
$db=GetOpenAuditDbConnection() or die("Could not connect");
mysqli_select_db($db,$mysqli_database) or die("Could not select database");
$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  do{
    if ($bgcolor == "#F1F1F1") { $bgcolor = "#FFFFFF"; } else { $bgcolor = "#F1F1F1"; }
    echo "<tr style=\"background-color:".$bgcolor."\">\n";
    echo " <td><a href=\"system.php?pc=" . $myrow['scan_type_uuid'] . "\">" . $myrow['system_name'] . "</a></td>\n";
    echo " <td align=\"center\">" . $myrow['scan_type_ip_address'] . "</td>\n";
    echo " <td align=\"center\">" . $myrow['scan_type'] . "</td>\n";
    echo " <td align=\"center\">" . $myrow['scan_type_detail'] . "</td>\n";
    echo " <td align=\"center\">" . $myrow['scan_type_frequency'] . "</td>\n";
    echo "</tr>\n";
  } while ($myrow = mysqli_fetch_array($result));
}

echo "</table>";
echo "</div>";
echo "</td>";
// include "include_right_column.php";
echo "</body>";
echo "</html>";
?>

