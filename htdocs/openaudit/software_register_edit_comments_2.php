<?php

include "include_functions.php";

if (isset($_GET['id'])){
  $id = $_GET['id'];
} else {
  header("Location: software_register.php?pc=");
}

if (isset($_POST['comments'])){
  $comments = $_POST['comments'];
} else {
  $comments = "";
}

$page = "other";
include "include_config.php";

// Process the form
$db=GetOpenAuditDbConnection() or die("Could not connect");
mysqli_select_db($db,$mysqli_database) or die("Could not select database");

$sql = "update software_register set software_comments = '$comments' WHERE software_reg_id='$id'";

$result = mysqli_query($db,$sql);

header("Location: software_register_details.php?id=$id");


?>





