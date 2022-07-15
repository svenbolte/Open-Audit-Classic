<?php
$page = "";
$extra = "";
$software = "";
$count = -1;
if (isset($_GET['software'])) {$software = $_GET['software'];} else {}
if (isset($_GET['sort'])) {$sort = $_GET['sort'];} else {$sort= "system_name";}
include "include.php";
$time_start = microtime_float();
// set an initial 4 min extra timeout
set_time_limit(240000);

echo "<td style=\"vertical-align:top;width:100%\">\n";
echo "<div class=\"main_each\">";
#$sql =  "SELECT software_reg_id, software_title, count(software.software_name) AS number_used, sum(license_purchase_number) as number_purchased FROM ";
#$sql .= "software_register, software, system, software_licenses WHERE ";
#$sql .= "software_reg_id = license_software_id AND ";
#$sql .= "software_title = software_name AND ";
#$sql .= "software_uuid = system_uuid AND ";
#$sql .= "software_timestamp = system_timestamp ";
#$sql .= "GROUP BY software_title";

$sql = "SELECT software_reg_id, software_title, count(software.software_name) AS number_used, software_comments FROM ";
$sql .= "software_register, software, system WHERE ";
$sql .= "software_title = software_name AND ";
$sql .= "software_uuid = system_uuid AND ";
$sql .= "software_timestamp = system_timestamp ";
$sql .= "GROUP BY software_title";

$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  echo "<table   width=\"100%\">\n";
  echo "<tr>\n";
  echo "  <td class=\"contenthead\">Software License Register<br />&nbsp;</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td style=\"max-width:30%;\"><b>Package&nbsp;&nbsp;</b></td>\n";
  echo "<td align=\"center\" ><b>&nbsp;&nbsp;Purchased&nbsp;&nbsp;</b></td>\n";
  echo "<td align=\"center\" ><b>&nbsp;&nbsp;Used&nbsp;&nbsp;</b></td>\n";
  echo "<td align=\"center\" ><b>&nbsp;&nbsp;Audit&nbsp;&nbsp;</b></td>\n";
  echo "<td style=\"min-width:60%;\"><b>&nbsp;&nbsp;Comments&nbsp;&nbsp;</b></td>\n";
  echo "<td align=\"center\" ><b>&nbsp;&nbsp;Remove&nbsp;&nbsp;</b></td>\n";
  echo "</tr>\n";
  do {    $sql2  = "SELECT sum(license_purchase_number) as number_purchased FROM ";
    $sql2 .= "software_licenses, software_register WHERE ";
    $sql2 .= "license_software_id = software_reg_id AND ";
    $sql2 .= "software_reg_id = '" . $myrow['software_reg_id'] . "'";
    $result2 = mysqli_query($db,$sql2);
    $myrow2 = mysqli_fetch_array($result2);
    
    
    $number_purchased = $myrow2["number_purchased"];
    $number_used = $myrow["number_used"];
    settype($number_purchased, "integer");
    settype($number_used, "integer");
    $number_audit = $number_purchased - $number_used;
    $font = "<font>";
    if ($number_audit < "0") { $font = "<font color=\"red\">";}
    if ($number_audit == "0") { $font = "<font color=\"blue\">";}
    if ($number_audit > "0") { $font = "<font color=\"green\">";}
      
    $count = $count + 1;
    $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
	echo " <tr style=\"background-color:" . $bgcolor . ";\">\n";
    echo "<td style=\"max-width:300px;\">";

	if (strpos($myrow["software_title"]," ")!=0) {$logobild = substr($myrow["software_title"],0,strpos($myrow["software_title"]," "));} else {$logobild=$myrow["software_title"];}
	 // if (strlen($logobild)>4) {$show_value = $logobild;}
	if (is_file("softwarelogos/".$logobild.".png")){
	   echo "<img src=\"softwarelogos/".$logobild.".png\" style=\"border:0px;\" alt=\"\" /> ";
	}
	
	echo "<a href=\"software_register_details.php?id=" . $myrow["software_reg_id"] . "\">" . $myrow["software_title"] . "</a>&nbsp;&nbsp;</td>";
    if ($number_purchased == -1) {
      echo "<td align=\"center\">Free</td>";
    } else {
      echo "<td align=\"center\">" . $number_purchased . "</td>";
    }
    echo "<td align=\"center\">" . $number_used . "</td>";
    if ($number_purchased == -1) {
      echo "<td align=\"center\"></td>";
    } else {
      echo "<td align=\"center\">" . $font . $number_audit . "</font></td>";
    }
    echo "<td align=\"left\" style=\"min-width:600px;\">". $myrow['software_comments'] . "</td>\n";
    echo "<td align=\"center\"><div id=\"s" . $myrow['software_reg_id'] . "\">\n";
    echo "<a href=\"#\" onclick=\"sendRequest('" . url_clean($myrow["software_reg_id"]) . "');\"><img border=\"0\" src=\"images/button_fail.png\" width=\"16\" height=\"16\" alt=\"\" /></a>";
    echo "</div></td>\n"; 
    echo "</tr>";
  } while ($myrow = mysqli_fetch_array($result));
  echo "</table>";
} else {
  echo "<p class=\"content\">No Packages in database.</p>"; 
}


echo "</div><br>\n";
echo __("This Page was generated in")." ".number_format((microtime_float()-$time_start),2)." ". __("Seconds").".";


echo "</td>\n";

// include "include_right_column.php";

?>
<script language="javascript" TYPE="text/javascript">

function createRequestObject() {
  var req;
  if(window.XMLHttpRequest){
    // Firefox, Safari, Opera...
    req = new XMLHttpRequest();
  } else if(window.ActiveXObject) {
    // Internet Explorer 5+
    req = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      // There is an error creating the object,
      // just as an old browser is being used.
      alert('Problem creating the XMLHttpRequest object');
    }
  return req;
}

// Make the XMLHttpRequest object
var http = createRequestObject();

function sendRequest(act) {
  // Open PHP script for requests
  http.open('get', 'software_register_remove_ajax.php?act='+act);
  http.onreadystatechange = handleResponse;
  http.send(null);
}
/*
function handleResponse() {
  if(http.readyState == 4 && http.status == 200){
    // Text returned FROM the PHP script
    var response = http.responseText;
    if(response) {
      // UPDATE ajaxTest content
      document.getElementById("ajaxTest").innerHTML = response;
    }
  }
}
*/

function handleResponse() {
  if(http.readyState == 4 && http.status == 200){
    // Text returned FROM the PHP script
    var response = http.responseText;
    if(response) {
      // UPDATE ajaxTest content
      document.getElementById(response).innerHTML = 'Removed';
    }
  }
}

</script>
<?php
echo "</body>\n";
echo "</html>\n";
?>
