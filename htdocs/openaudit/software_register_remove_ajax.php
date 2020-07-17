<?php

include "include_config.php";

if (isset($_GET['act'])){ $package = $_GET['act']; } else { $package = ''; }
$db=GetOpenAuditDbConnection() or die("Could not connect");
mysqli_select_db($db,$mysqli_database) or die("Could not select database");

$sql = "DELETE FROM software_register WHERE software_reg_id = '$package'";
$result = mysqli_query($db,$sql) or die ('<td>Query Failed: ' . mysqli_error($db) . '<br />' . $sql . "</td>");

$sql = "DELETE FROM software_licenses WHERE license_software_id = '$package'";
$result = mysqli_query($db,$sql) or die ('<td>Query Failed: ' . mysqli_error($db) . '<br />' . $sql . "</td>");

echo "s" . $package;

?>
