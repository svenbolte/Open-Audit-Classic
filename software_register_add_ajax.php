<?php

include "include_functions.php";

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

if (isset($_GET['act'])){ $package = $_GET['act']; } else { $package = ''; }
$sql = "SELECT count(*) AS count FROM software_register WHERE software_title = '$package'";
$db=GetOpenAuditDbConnection() or die("Could not connect");
mysqli_select_db($db,$mysqli_database) or die("Could not select database");
$result = mysqli_query($db,$sql) or die ('<td>Insert Failed: ' . mysqli_error($db) . '<br />' . $sql . "</td>");
$myrow = mysqli_fetch_array($result);
if ($myrow["count"] == "0") {
  $sql = "INSERT INTO software_register (software_title) VALUES ('$package')"; 
  $result = mysqli_query($db,$sql) or die ('<td>Insert Failed: ' . mysqli_error($db) . '<br />' . $sql . "</td>");
  $id = mysqli_insert_id($db);
  $sql = "INSERT INTO software_licenses (license_software_id, license_purchase_number, license_comments) VALUES ('$id', '0', 'OA initial license')";
  $result = mysqli_query($db,$sql) or die ('<td>Insert Failed: ' . mysqli_error($db) . '<br />' . $sql . "</td>");
  # echo "Added '$package' to the software register.";
  echo "s" . div_clean($package);
}
?>
