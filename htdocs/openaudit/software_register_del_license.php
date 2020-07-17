<?php
	
include "include_functions.php";

	if ($_GET['confirm']=1) {

	$db=GetOpenAuditDbConnection() or die("Could not connect");
	mysqli_select_db($db,"$mysqli_database") or die("Could not select database");

	$query = "DELETE FROM software_licenses WHERE license_id = '" . $_GET['id'] . "'";
	$result = mysqli_query($db,$query)  or die("Query failed at insert stage. license");

	header("Location: software_register_details.php?id=" . $_GET["id2"]);
	} else {}

?>
