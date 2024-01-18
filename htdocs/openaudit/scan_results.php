<?php
$page = "";
$extra = "";
$software = "";
$count = -1;
if (isset($_GET['software'])) {$software = $_GET['software'];} else {}
if (isset($_GET['sort'])) {$sort = $_GET['sort'];} else {$sort= "system_name";}
include "include.php";

echo "<td style=\"vertical-align:top;width:100%\">\n";
echo "<div class=\"main_each\">";
echo "<table class=\"tftable\"    width=\"100%\">\n";
echo "<tr>\n";
echo "  <td class=\"contenthead\" colspan=\"5\">Network Monitoring.<br />&nbsp;</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"15%\"><b>Host</b></td>\n";
echo "<td align=\"center\" width=\"15%\"><b>IP Address</b></td>\n";
echo "<td align=\"center\" width=\"15%\"><b>Type</b></td>\n";
echo "<td align=\"center\" width=\"15%\"><b>Detail</b></td>\n";
echo "<td align=\"center\" width=\"25%\"><b>Time</b></td>\n";
echo "<td align=\"center\" width=\"15%\"><b>Result</b></td>\n";
echo "</tr>\n";

include "scan_results_include.php";

echo "</table>";
echo "</div>";
echo "</td>";
// include "include_right_column.php";
echo "</body>";
echo "</html>";
?>
