<?php
    
include "include_config.php";

    if ($_GET['confirm']=1) {

    $link = $db=GetOpenAuditDbConnection() or die("Could not connect");
    mysqli_select_db($db,"$mysqli_database") or die("Could not select database");

    $query = "DELETE FROM battery";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. browser_helper_objects");

    $query = "DELETE FROM bios";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. browser_bios");

    $query = "DELETE FROM browser_helper_objects";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. browser_helper_objects");
    
    $query = "DELETE FROM firewall_auth_app";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. firewall_auth_app");    
    
    $query = "DELETE FROM firewall_ports";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. firewall_ports");
    
    $query = "DELETE FROM firewire";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. firewire");

    $query = "DELETE FROM floppy";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. floppy");

    $query = "DELETE FROM graphs_disk";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. graphs_disk");

    $query = "DELETE FROM groups";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM groups_details";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups_details");

    $query = "DELETE FROM group_members";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM hard_drive";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. hard_drive");

    $query = "DELETE FROM hotfix";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. hotfixes");

    $query = "DELETE FROM iis";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. iis");

    $query = "DELETE FROM iis_ip";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. iis_ip");

    $query = "DELETE FROM iis_vd";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. iis_vd");

    $query = "DELETE FROM invoice";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. invoice");

    $query = "DELETE FROM keyboard";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM manual_software";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. manual_software");

    $query = "DELETE FROM mapped";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. mapped");

    $query = "DELETE FROM media";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. media");

    $query = "DELETE FROM memory";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. memory");

    $query = "DELETE FROM modem";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. modem");

    $query = "DELETE FROM monitor";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM mouse";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM ms_keys";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. ms_keys");

    $query = "DELETE FROM network_card";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. network_card");

    $query = "DELETE FROM nmap_ports";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. nmap_other_ports");

    $query = "DELETE FROM notes";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. notes");

    $query = "DELETE FROM optical_drive";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. optical_drive");

    $query = "DELETE FROM `partition`";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. partition");

    $query = "DELETE FROM passwords";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. passwords");

    $query = "DELETE FROM printer";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. printer");

    $query = "DELETE FROM processor";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. processor");

    $query = "DELETE FROM scsi_controller";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. scsi_controller");

    $query = "DELETE FROM scsi_device";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. scsi_device");

    $query = "DELETE FROM service";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. services");

    $query = "DELETE FROM service_details";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM shares";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. shares");

    $query = "DELETE FROM software";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. software");

    $query = "DELETE FROM sound";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM startup";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. startup");

    $query = "DELETE FROM system";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. system");

    $query = "DELETE FROM system_audits";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM system_change";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM system_change_log";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM system_man";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. system_man");

    $query = "DELETE FROM system_security";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM system_security_bulletins";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. groups");

    $query = "DELETE FROM tape_drive";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. tape_drive");

    $query = "DELETE FROM usb";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. usb");

    $query = "DELETE FROM users";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. users");

    $query = "DELETE FROM users_detail";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. users_detail");

    $query = "DELETE FROM video";
    $result = mysqli_query($db,$query)  or die("Query failed at insert stage. video");    
    
    header("Location: index.php");
    } else {}

?>
