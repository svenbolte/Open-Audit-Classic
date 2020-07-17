<?php

include "include_config.php";

        if (isset($_GET['other'])) {

        $link = $db=GetOpenAuditDbConnection() or die("Could not connect");
        mysqli_select_db($db,"$mysqli_database") or die("Could not select database");

        $query = "DELETE FROM other WHERE other_id = '" . $_GET['other'] . "'";
        $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

        header("Location: ./list.php?view=delete_other");
        }

?>
