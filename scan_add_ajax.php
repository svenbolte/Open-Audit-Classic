<?php

include "include_config.php";


if (isset($_GET['uuid'])){ $uuid = $_GET['uuid']; } else { $uuid = ''; }
if (isset($_GET['ip'])){ $ip = $_GET['ip']; } else { $ip = ''; }
if (isset($_GET['detail'])){ $detail = $_GET['detail']; } else { $detail = ''; }
if (isset($_GET['time'])){ $time = $_GET['time']; } else { $time = ''; }
if (isset($_GET['type'])){ $type = $_GET['type']; } else { $type = ''; }

$db=GetOpenAuditDbConnection() or die("Could not connect");
mysqli_select_db($db,$mysqli_database) or die("Could not select database");
$sql = "DELETE FROM scan_type WHERE scan_type_uuid = '$uuid' AND scan_type = '$type' AND scan_type_detail = '$detail' AND scan_type_ip_address = '$ip'";
$result = mysqli_query($db,$sql);
$sql  = "INSERT INTO scan_type (scan_type_uuid, scan_type_ip_address, scan_type, scan_type_detail, scan_type_frequency) VALUES ('";
$sql .= $uuid . "','" . $ip . "','" . $type . "','" . $detail . "','" . $time . "')";
$result = mysqli_query($db,$sql);
echo $uuid;

?>
