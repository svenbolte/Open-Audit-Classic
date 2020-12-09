<?php
	
include "include_functions.php";

	if ($_GET['confirm']=1) {

    if (isset($_GET['id'])){
      $id = $_GET['id'];
    } else {
    echo "No $id transmitted.";
       header("Location: software_register.php");
    }

	$db=GetOpenAuditDbConnection() or die("Could not connect");
	mysqli_select_db($db,"$mysqli_database") or die("Could not select database");

	$query = "DELETE FROM software_register WHERE software_reg_id = '" . $_GET['id'] . "'";
	$result = mysqli_query($db,$query)  or die("Query failed at insert stage. register");

	$query = "DELETE FROM software_licenses WHERE license_software_id = '" . $_GET['id'] . "'";
	$result = mysqli_query($db,$query)  or die("Query failed at insert stage. license");

	header("Location: software_register.php");
	} else {}

?>