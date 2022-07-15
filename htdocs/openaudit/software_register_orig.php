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
$sql = "select * from software_register ORDER BY software_title";
//$sql = "select * from software_register left outer join group_names on software_register.group_id = group_names.group_id ORDER BY group_names.group_id, software_title";
$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  $group_id = $myrow["group_id"];
  echo "<table  >\n";
  echo "<tr>\n";
  echo "  <td class=\"contenthead\">Software License Register.<br />&nbsp;</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td width=\"25%\"><b>Package&nbsp;&nbsp;</b></td>\n";
  echo "<td align=\"center\" width=\"25%\"><b>&nbsp;&nbsp;Purchased&nbsp;&nbsp;</b></td>\n";
  echo "<td align=\"center\" width=\"25%\"><b>&nbsp;&nbsp;Used&nbsp;&nbsp;</b></td>\n";
  echo "<td align=\"center\" width=\"25%\"><b>&nbsp;&nbsp;Audit&nbsp;&nbsp;</b></td>\n";
  echo "</tr>\n";
  //echo "<tr><td colspan=\"4\">Group: " . $myrow["group_name"] . "</td></tr>\n";
  do {
    if ($group_id != $myrow['group_id']) {
    echo "<tr><td colspan=\"4\">&nbsp;</td></tr>\n";
    echo "<tr><td colspan=\"4\">Group: " . $myrow["group_name"] . "</td></tr>\n";
    $bgcolor = "#FFFFFF";
    $group_id = $myrow['group_id'];
    } else {}
    $sql3 = "SELECT SUM(license_purchase_number) AS number_purchased FROM software_licenses WHERE license_software_id = '" . $myrow["software_reg_id"] . "'";
    $result3 = mysqli_query($db,$sql3);
    $myrow3 = mysqli_fetch_array($result3);
      
    //$sql4 = "SELECT count(software_name) AS number_used FROM software WHERE software_name = '" . addslashes($myrow["software_title"]) . "' AND software_no_detect_date = '1111-11-11' "; 
    $sql4 = "SELECT count(software_name) AS number_used FROM software left outer join group_members on concat('mac-',software.software_uuid) = group_members.group_uuid left outer join software_group_members sgm on sgm.group_software_title = software.software_name left outer join software_group_names sgn on sgn.group_id = sgm.group_id WHERE (software_name = '". addslashes($myrow["software_title"]) . "' or sgn.group_name = '". addslashes($myrow["software_title"]) . "') AND group_names_id = '" . $group_id . "'";
echo $sql4 . "<br />\n";
    $result4 = mysqli_query($db,$sql4);
    $myrow4 = mysqli_fetch_array($result4);
      
    if ($myrow3["number_purchased"] == "") { $number_purchased = 0; } else { $number_purchased = $myrow3["number_purchased"]; }
    if ($myrow4["number_used"] == "") { $number_used = 0; } else { $number_used = $myrow4["number_used"]; }
    settype($number_purchased, "integer");
    settype($number_used, "integer");
    $number_audit = $number_purchased - $number_used;

    //settype($number_audit, "integer");
    $font = "<font>";
    if ($number_audit < "0") { $font = "<font color=\"red\">";}
    if ($number_audit == "0") { $font = "<font color=\"blue\">";}
    if ($number_audit > "0") { $font = "<font color=\"green\">";}
      
    $count = $count + 1;
    if ($bgcolor == $bg1) {
      $bgcolor = "#FFFFFF"; }
    else { $bgcolor = $bg1; }
    echo "<tr style=\"background-color:".$bgcolor."\">";
    echo "<td><a href=\"software_register_details.php?id=" . $myrow["software_reg_id"] . "\">" . $myrow["software_title"] . "</a>&nbsp;&nbsp;</td>";
    //echo "<td align=\"center\">" . $number_purchased . "</td>";
    if ($number_purchased == -1) {
      echo "<td align=\"center\">Free</td>";
    } else {
      echo "<td align=\"center\">" . $number_purchased . "</td>";
    }
    echo "<td align=\"center\">" . $number_used . "</td>";
    //echo "<td align=\"center\">" . $font . $number_audit . "</font></td>";
    if ($number_purchased == -1) {
      echo "<td align=\"center\"></td>";
    } else {
      echo "<td align=\"center\">" . $font . $number_audit . "</font></td>";
    }
    echo "</tr>";
  } while ($myrow = mysqli_fetch_array($result));
  echo "</table>";
} else {
  echo "<p class=\"content\">No Packages in database.</p>"; 
}


echo "</div>\n";
echo "</td>\n";
// include "include_right_column.php";
echo "</body>\n";
echo "</html>\n";
?>
