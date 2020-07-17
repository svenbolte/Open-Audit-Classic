<?php
$page = "";
$extra = "";
$software = "";
$count = 0;

function div_clean($div)
{
$div_clean = str_replace ('%','',$div);
$div_clean = str_replace ('(','',$div_clean);
$div_clean = str_replace (')','',$div_clean);
$div_clean = str_replace ('`','',$div_clean);
$div_clean = str_replace ('_','',$div_clean);
$div_clean = str_replace ('!','',$div_clean);
$div_clean = str_replace ("'","",$div_clean);
$div_clean = str_replace ('$','',$div_clean);
$div_clean = str_replace (' ','',$div_clean);
$div_clean = str_replace ('+','',$div_clean);
$div_clean = str_replace ('&','',$div_clean);
$div_clean = str_replace (',','',$div_clean);
$div_clean = str_replace ('/','',$div_clean);
$div_clean = str_replace (':','',$div_clean);
$div_clean = str_replace ('=','',$div_clean);
$div_clean = str_replace ('?','',$div_clean);
$div_clean = str_replace ('<','',$div_clean);
$div_clean = str_replace ('>','',$div_clean);
$div_clean = str_replace ('#','',$div_clean);
$div_clean = str_replace ('{','',$div_clean);
$div_clean = str_replace ('}','',$div_clean);
$div_clean = str_replace ('|','',$div_clean);
$div_clean = str_replace ('\\','',$div_clean);
$div_clean = str_replace ('^','',$div_clean);
$div_clean = str_replace ('~','',$div_clean);
$div_clean = str_replace ('[','',$div_clean);
$div_clean = str_replace (']','',$div_clean);
$div_clean = str_replace ('`','',$div_clean);
return $div_clean;
}

  include "include.php";
  echo "<td valign=\"top\">\n"; 
  echo "<div class=\"main_each\">";
  echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
  echo "<tr>\n";
  echo "  <td class=\"contenthead\" colspan=\"3\">".__("Add Package to Software License Register").".<br />&nbsp;</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td colspan=\"3\"><div id=\"ajaxTest\"><br /> </div></td>\n";
  echo "</tr>\n";

  $sql = "SELECT count(software_name), software_name FROM software ";
  $sql .= "INNER JOIN system ON (software_uuid = system_uuid AND ";
  $sql .= "software_timestamp = system_timestamp ) ";
  $sql .= "LEFT JOIN software_register ON (software.software_name = software_register.software_title) ";
  $sql .= "WHERE software_title IS NULL AND software_name NOT LIKE '%hotfix%' AND software_name NOT LIKE '%Service Pack%' AND software_name NOT REGEXP '[KB|Q][0-9]{6,}' ";
  $sql .= "group by software_name ORDER BY software_name";
  
  $result = mysqli_query($db,$sql);
  if ($myrow = mysqli_fetch_array($result)){
    echo "  <tr>\n";
    echo "    <td><b>Count</b></td>\n";
    echo "    <td>&nbsp;&nbsp;<b>Package Name</b></td>\n";
    echo "    <td align=\"center\"><b>Tick to Add</b></td>\n";
    echo "  </tr>\n";
    do {
      if ($bgcolor == $bg1) { $bgcolor = "#FFFFFF"; } else { $bgcolor = $bg1; }
      echo "<tr bgcolor=\"" . $bgcolor . "\">\n";
      echo "  <td align=\"center\">" . $myrow["count(software_name)"] . "</td>\n";
      echo "  <td>&nbsp;&nbsp;" . $myrow["software_name"] . "</td>\n";
      echo "<td align=\"center\">";
      echo "<div id=\"s" . div_clean($myrow["software_name"]) . "\">";
      echo "<a href=\"#\" onclick=\"sendRequest('" . url_clean($myrow["software_name"]) . "');\"><img border=\"0\" src=\"images/button_success.png\" width=\"16\" height=\"16\" alt=\"\" /></a>";
      echo "</div>\n";
      echo "</td>\n";
      echo "<td valign=\"top\">\n";
      echo "</tr>";
    } while ($myrow = mysqli_fetch_array($result));
    echo "</table>\n";
  } else {
    echo "No Software in database.";
  }
echo "</div>\n";
echo "</td>\n";
// // include "include_right_column.php";
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
  http.open('get', 'software_register_add_ajax.php?act='+act);
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
      document.getElementById(response).innerHTML = "Added";
    }
  }
}

</script>
<?php
echo "</body>\n";
echo "</html>\n";
?>
