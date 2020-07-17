<?php
$page = "other";
include "include_config.php";
include "include_functions.php";
include "include_lang.php";

$db=GetOpenAuditDbConnection() or die("Could not connect");
mysqli_select_db($db,$mysqli_database) or die("Could not select database");

if(isset($_REQUEST["view"]) AND isset($_REQUEST["category"])){


    //Other-System ------------------------------------------------------------------------------
    if($_REQUEST["view"]=="other_system" AND $_REQUEST["category"]=="summary" OR
       $_REQUEST["view"]=="printer" AND $_REQUEST["category"]=="summary"){

        $sql  = "UPDATE other SET other_network_name = '" . $_REQUEST['other_network_name'] . "',";
        $sql .= " other_ip_address = '" . ip_trans_to($_REQUEST['other_ip_address']) . "',";
        $sql .= " other_mac_address = '" . $_REQUEST['other_mac_address'] . "',";
        $sql .= " other_p_port_name = '" . $_REQUEST['other_p_port_name'] . "',";
        $sql .= " other_description = '" . $_REQUEST['other_description'] . "',";
        $sql .= " other_serial = '" . $_REQUEST['other_serial'] . "',";
        $sql .= " other_manufacturer = '" . $_REQUEST['other_manufacturer'] . "',";
        $sql .= " other_model='" . $_REQUEST['other_model'] . "',";
        $sql .= " other_type='" . $_REQUEST['other_type'] . "',";
        $sql .= " other_location='" . $_REQUEST['other_location'] . "',";
        $sql .= " other_date_purchased='" . $_REQUEST['other_date_purchased'] . "',";
        $sql .= " other_value='" . $_REQUEST['other_value'] . "',";
        $sql .= " other_linked_pc='" . $_REQUEST['other_linked_pc'] . "' ";
        $sql .= " WHERE other_id='" . $_REQUEST['other'] . "'";

        $url="./system.php?other=".$_REQUEST["other"]."&view=".$_REQUEST["view"]." ";

    //Monitor ------------------------------------------------------------------------------------
    }elseif($_REQUEST["view"]=="monitor" AND $_REQUEST["category"]=="summary"){

        $sql  = "UPDATE monitor SET ";
        $sql .= " monitor_uuid = '" . $_REQUEST['monitor_uuid'] . "', ";
        $sql .= " monitor_date_purchased = '" . $_REQUEST['monitor_date_purchased'] . "', ";
        $sql .= " monitor_purchase_order_number = '" . $_REQUEST['monitor_purchase_order_number'] . "', ";
        $sql .= " monitor_value = '" . $_REQUEST['monitor_value'] . "', ";
        $sql .= " monitor_description = '" . $_REQUEST['monitor_description'] . "' ";
        $sql .= " WHERE monitor_id = '" . $_REQUEST['monitor'] . "' ";

        $url="./system.php?monitor=".$_REQUEST["monitor"]."&view=".$_REQUEST["view"]." ";

    //System-Manual-Data ------------------------------------------------------------------------
    }elseif($_REQUEST["view"]=="summary" AND $_REQUEST["category"]=="manual"){

        $sql  = "UPDATE `system_man` SET ";
        $sql .= "`system_man_value` = '" . $_REQUEST['system_man_value'] . "', ";
        $sql .= "`system_man_description` = '" . $_REQUEST['system_man_description'] . "', ";
        $sql .= "`system_man_location` = '" . $_REQUEST['system_man_location'] . "', ";
        $sql .= "`system_man_serial_number` = '" . $_REQUEST['system_man_serial_number'] . "', ";
        $sql .= "`system_man_date_of_purchase` = '" . $_REQUEST['system_man_date_of_purchase'] . "'";
        $sql .= " WHERE `system_man_uuid` = '" . $_REQUEST['pc'] . "' ";

        $url="./system.php?pc=".$_REQUEST["pc"]."&view=".$_REQUEST["view"]." ";

    //System-Manual-Data ------------------------------------------------------------------------
    }elseif($_REQUEST["view"]=="oauser" AND $_REQUEST["category"]=="manual"){

        $old_password=$_REQUEST['auth_hash_old'];
        if($old_password!=$_REQUEST['auth_hash']){
            $password=md5($_REQUEST['auth_hash']);
            $sql_password="`auth_hash` = '" . $password . "', ";
        }else{
            $password=$old_password;
            $sql_password="";
        }


        $sql  = "UPDATE `auth` SET ";
        $sql .= "`auth_username` = '" . $_REQUEST['auth_username'] . "', ";
        $sql .= $sql_password;
        $sql .= "`auth_realname` = '" . $_REQUEST['auth_realname'] . "', ";
        $sql .= "`auth_enabled` = '" . $_REQUEST['auth_enabled'] . "', ";
        $sql .= "`auth_admin` = '" . $_REQUEST['auth_admin'] . "'";
        $sql .= " WHERE `auth_id` = '" . $_REQUEST['user'] . "' ";

        $url="./system.php?user=".$_REQUEST["user"]."&view=".$_REQUEST["view"]." ";

    }else{
        die(__("FATAL: There is now method for this view/summary defined"));
    }

    //Executing the query
    $result=mysqli_query($db,$sql);
    if(!$result) { echo "<br>".__("Fatal Error").":<br><br>".$sql."<br><br>".mysqli_error($db)."<br><br>";
                   echo "<pre>";
                   print_r($_REQUEST);
                   die();
                 };

    //Redirect
    header("Location: ".$url);

}else{
    die(__("FATAL: Not enought variables to proceed: view and category needed"));
}

die(print_r($_REQUEST));

?>
