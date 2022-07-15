<?php
    $page = "";
    include "include.php";
    $time_start = microtime_float();

    if (!empty($_POST['Perform'])) {
      foreach($_POST as $id) {

       $query = "DELETE FROM auto_updating WHERE au_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. auto_updating");

       $query = "DELETE FROM battery WHERE battery_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. battery");

       $query = "DELETE FROM bios WHERE bios_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. bios");

       $query = "DELETE FROM browser_helper_objects WHERE bho_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. browser_helper_objects");

       $query = "DELETE FROM environment_variable WHERE env_var_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. environment_variable");

       $query = "DELETE FROM event_log WHERE evt_log_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. event_log");

       $query = "DELETE FROM firewall_auth_app WHERE firewall_app_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. firewall_auth_app");

       $query = "DELETE FROM firewall_ports WHERE port_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. firewall_ports");

       $query = "DELETE FROM firewire WHERE fw_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. firewire");

       $query = "DELETE FROM floppy WHERE floppy_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. floppy");

       $query = "DELETE FROM graphs_disk WHERE disk_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. graphs_disk");

       $query = "DELETE FROM groups WHERE groups_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. groups");

       $query = "DELETE FROM hard_drive WHERE hard_drive_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. hard_drive");

       $query = "DELETE FROM iis WHERE iis_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. iis");

       $query = "DELETE FROM iis_ip WHERE iis_ip_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. iis_ip");

       $query = "DELETE FROM iis_vd WHERE iis_vd_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. iis_vd");

       $query = "DELETE FROM iis_web_ext WHERE iis_web_ext_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. iis_web_ext");

       $query = "DELETE FROM invoice WHERE invoice_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. invoice");
   
       $query = "DELETE FROM ip_route WHERE ip_route_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. ip_route");

       $query = "DELETE FROM keyboard WHERE keyboard_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. keyboard");

       $query = "DELETE FROM manual_software WHERE man_soft_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. manual_software");

       $query = "DELETE FROM mapped WHERE mapped_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. mapped");

       $query = "DELETE FROM media WHERE media_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. media");

       $query = "DELETE FROM memory WHERE memory_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. memory");

       $query = "DELETE FROM modem WHERE modem_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. modem");

       $query = "DELETE FROM monitor WHERE monitor_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. monitor");
   
       $query = "DELETE FROM motherboard WHERE motherboard_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. motherboard");

       $query = "DELETE FROM mouse WHERE mouse_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. mouse");

       $query = "DELETE FROM ms_keys WHERE ms_keys_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. ms_keys");

       $query = "DELETE FROM network_card WHERE net_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. network_card");

       $query = "DELETE FROM nmap_ports WHERE nmap_other_id = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. nmap_ports");

       $query = "DELETE FROM notes WHERE notes_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. notes");
   
       $query = "DELETE FROM onboard_device WHERE onboard_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. onboard_device");

       $query = "DELETE FROM optical_drive WHERE optical_drive_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. optical_drive");
   
       $query = "DELETE FROM pagefile WHERE pagefile_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. pagefile");

       $query = "DELETE FROM `partition` WHERE partition_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. partition");

       $query = "DELETE FROM passwords WHERE passwords_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. passwords");

       $query = "DELETE FROM processor WHERE processor_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. processor");

       $query = "DELETE FROM scheduled_task WHERE sched_task_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. scheduled_task");

       $query = "DELETE FROM scsi_controller WHERE scsi_controller_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. scsi_controller");

       $query = "DELETE FROM scsi_device WHERE scsi_device_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. scsi_device");

       $query = "DELETE FROM service WHERE service_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. service");

       $query = "DELETE FROM shares WHERE shares_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. shares");

       $query = "DELETE FROM software WHERE software_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. software");

       $query = "DELETE FROM sound WHERE sound_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. sound");

       $query = "DELETE FROM startup WHERE startup_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. startup");

       $query = "DELETE FROM system WHERE system_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. system");

       $query = "DELETE FROM system_audits WHERE system_audits_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. system_audits");

       $query = "DELETE FROM system_man WHERE system_man_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. system_man");

       $query = "DELETE FROM system_security WHERE ss_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. system_security");

       $query = "DELETE FROM tape_drive WHERE tape_drive_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. tape_drive");

       $query = "DELETE FROM usb WHERE usb_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. usb");

       $query = "DELETE FROM users WHERE users_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. users");

       $query = "DELETE FROM video WHERE video_uuid = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. video");

       $query = "DELETE FROM other WHERE other_linked_pc = '" . $id . "'";
       $result = mysqli_query($db,$query)  or die("Query failed at delete stage. other");

       }
    }

    $sql = "SELECT system_uuid, net_ip_address, system_name, net_domain, system_os_name, system_system_type, system_timestamp FROM system ORDER BY system_name ";
    $result = mysqli_query($db,$sql);
    $bgcolor = "#FFFFFF";
    echo "<td style=\"vertical-align:top;width:100%\">
          <div class=\"main_each\">";

    if ($myrow = mysqli_fetch_array($result)){
      echo "<form name=\"DeleteList\" id=\"DeleteList\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\" >
           
            <script language=\"JavaScript\" TYPE=\"text/javascript\">
             function CheckUncheckAll(form){
               if(!form)
                 return;
               var objElements = form.elements;
               if(!objElements)
                 return;
               var countElements = objElements.length;
               if(!countElements)
                 return;
               else
                 for (var i = 1; i < countElements; i++){   
                   eval(\"objElements[\" + i + \"].checked = objElements[1].checked\"); 
                 }
              }
            </script>
     
              <table width=\"100%\">
                <tr>
                   <td class=\"contenthead\">".__("Delete Systems")."<br />&nbsp;</td>
                </tr>
              </table>
             
              <table   width=\"100%\">
                <tr>
                   <td width=\"30%\"><input type=\"submit\" name=\"Perform\" id=\"Perform\" value=\"Delete selected systems\" onclick=\"return confirm('Do you really want to DELETE all selected Systems?')\"></td>
                   <td width=\"70%\"><input type=\"checkbox\" name=\"SetUnset\" id=\"SetUnset\" onClick=\"CheckUncheckAll(this.form);\" />Check/Uncheck all<br /></td>   
                </tr>
              </table>

              <table class=\"tftable\" width=\"100%\">
                <tr>&nbsp;</tr>
                <tr>
                   <td></td>
                   <td class=\"contentsubtitle\">".__("IP Address")."</td>
                   <td class=\"contentsubtitle\">".__("Hostname")."</td>
                   <td class=\"contentsubtitle\">".__("Domain")."</td>
                   <td class=\"contentsubtitle\">".__("OS")."</td>
                   <td class=\"contentsubtitle\">".__("Type")."</td>
                   <td class=\"contentsubtitle\">".__("Date Audited")."</td>
                </tr>\n";
      do {
          $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
          echo "<tr style=\"bgcolor:" . $bgcolor . ";\">
                   <td width=\"5%\"><input type=\"checkbox\" name=\"" . $myrow["system_uuid"] . "\" id=\"" . $myrow["system_uuid"] . "\" value=\"" . $myrow["system_uuid"] . "\"></td>
                   <td><a href=\"system.php?pc=".$myrow["system_uuid"]."&amp;view=summary\">" . ip_trans($myrow["net_ip_address"]) . "</a></td>
                   <td><a href=\"system.php?pc=".$myrow["system_uuid"]."&amp;view=summary\">" . $myrow["system_name"] . "</a></td>
                   <td>" . $myrow["net_domain"] . "</td>
                   <td>" . determine_os($myrow["system_os_name"]) . "</td>
                   <td>" . determine_img($myrow["system_os_name"],$myrow["system_system_type"]) . "</td>
                   <td>" . return_date_time($myrow["system_timestamp"]) . "</td>
                </tr>\n";
        } while ($myrow = mysqli_fetch_array($result));
      echo "  </table>
           </form>";
       
    } else {
        echo "<p class=\"content\">No Systems in database.</p>\n";
      }

    echo "</div>\n";
    echo "</td>\n";
    // include "include_right_column.php";
?>
