<?php
    $page = "";
    include "include.php";
    $time_start = microtime_float();

    if (!empty($_POST['Perform'])) {
      foreach($_POST as $id) {

           $query = "DELETE FROM other WHERE other_id = '" . $id . "'";
           $result = mysqli_query($db,$query)  or die("Query failed at delete stage. groups");

           $query = "DELETE FROM nmap_ports WHERE nmap_other_id = '" . $id . "'";
           $result = mysqli_query($db,$query)  or die("Query failed at delete stage. nmap_ports");

      }
    }

    $sql = "SELECT other_id, other_ip_address, other_network_name, other_description, other_type, other_timestamp FROM other ORDER BY other_ip_address ";
    $result = mysqli_query($db,$sql);
     
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
     
              <table class=\"tftable\"  width=\"100%\">
                <tr>
                   <td class=\"contenthead\">".__("Delete other Equipments")."<br />&nbsp;</td>
                </tr>
              </table>
             
              <table class=\"tftable\"  class=\"tftable\" width=\"100%\">
                <tr>
                   <td width=\"30%\"><input type=\"submit\" name=\"Perform\" id=\"Perform\" value=\"Delete selected other equipments\" onclick=\"return confirm('Do you really want to DELETE all selected other equipments?')\"></td>
                   <td width=\"70%\"><input type=\"checkbox\" name=\"SetUnset\" id=\"SetUnset\" onClick=\"CheckUncheckAll(this.form);\" />Check/Uncheck all<br /></td>   
                </tr>
              </table>

              <table class=\"tftable\"  class=\"tftable\" width=\"100%\">
                <tr>&nbsp;</tr>
                <tr>
                   <td></td>
                   <td class=\"contentsubtitle\">".__("IP Address")."</td>
                   <td class=\"contentsubtitle\">".__("Hostname")."</td>
                   <td class=\"contentsubtitle\">".__("Description")."</td>
                   <td class=\"contentsubtitle\">".__("Type")."</td>
                   <td class=\"contentsubtitle\">".__("Date Audited")."</td>
                </tr>\n";
      do {
          // tabellierung über tftable css
          if (strlen($myrow["other_type"]) > 20) {$typebild="router";} else {$typebild = str_replace(" ","_",$myrow["other_type"]);}
		  echo "<tr  >
                   <td width=\"5%\"><input type=\"checkbox\" name=" . $myrow["other_id"] . " id=" . $myrow["other_id"] . " value=" . $myrow["other_id"] . "></td>
                   <td><a href=\"system.php?other=".$myrow["other_id"]."&amp;view=other_system\">" . ip_trans($myrow["other_ip_address"]) . "</a></td>
                   <td>" . $myrow["other_network_name"] . "</td>
                   <td>" . $myrow["other_description"] . "</td>
                   <td><img src=\"images/o_" .$typebild. ".png\" alt=\"\" border=\"0\" width=\"16\" height=\"16\"  /></td>
                   <td>" . return_date_time($myrow["other_timestamp"]) . "</td>
                </tr>\n";
        } while ($myrow = mysqli_fetch_array($result));
      echo "  </table>
           </form>";
       
    } else {
        echo "<p class=\"content\">No Other Systems in database.</p>\n";
      }

    echo "</div>\n";
    
    echo "</td>\n";
    // include "include_right_column.php";
?>