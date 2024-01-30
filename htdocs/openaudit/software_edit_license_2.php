<?php

include "include_config.php";
include "include_functions.php";

// Process the form
$db=GetOpenAuditDbConnection() or die("Could not connect");
mysqli_select_db($db,$mysqli_database) or die("Could not select database");

$sql = "update software_licenses SET ";
$sql .= "license_purchase_date ='".$_POST['date_purchased']."', "; 
$sql .= "license_purchase_vendor ='".$_POST['vendor']."', "; 
$sql .= "license_purchase_cost_each ='".$_POST['cost']."', "; 
$sql .= "license_purchase_number ='".$_POST['number_purchased']."', "; 
$sql .= "license_comments ='".$_POST['comments']."', "; 
$sql .= "license_purchase_type ='".$_POST['type']."', "; 
$sql .= "license_order_number ='".$_POST['order']."' "; 

$sql .= " WHERE license_id=".$_POST['id']." ";

$result = mysqli_query($db,$sql);

header("Location: software_register_details.php?id=" . $_POST['id2']);
?>





