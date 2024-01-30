<?php
$page = "";
$extra = "";
$software = "";
$count = -1;
if (isset($_GET['software'])) {$software = $_GET['software'];} else {}
if (isset($_GET['sort'])) {$sort = $_GET['sort'];} else {$sort= "system_name";}
include "include.php";

echo "<td style=\"vertical-align:top;width:100%\">\n";
echo "<div class=\"main_each\">";
$sql = "SELECT * FROM software_licenses WHERE license_comments <> 'OA initial license' AND license_id = '" . $_GET["id"] . "'";
$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)) {
  ?>
  <form action="software_edit_license_2.php" method="post">
  <table class="content">
    <tr><td class="contenthead">Edit Software License # <?php echo $myrow["license_id"]; ?> for Software # <?php echo $myrow["license_software_id"]; ?></td></tr>
  </table><table class="tftable">
    <tr><td width="25%">Date Purchased:  </td><td>
	  <input type="text" placeholder="yyyy-mm-dd" value="<?php echo substr($myrow["license_purchase_date"],0,10); ?>" name="date_purchased" size="20" /></td></tr>
    <tr><td>Number Purchased:  </td><td>
	  <input type="text" value="<?php echo $myrow["license_purchase_number"]; ?>" name="number_purchased" size="20" /> Set to "-1" if this is free</td></tr>
    <tr><td>Vendor:  </td><td>
	  <input type="text" value="<?php echo $myrow["license_purchase_vendor"]; ?>" name="vendor" size="20" /></td></tr>
    <tr><td><nobr>Cost per License:</nobr></td><td>
	  <input type="text" value="<?php echo $myrow["license_purchase_cost_each"]; ?>" name="cost" size="20" /></td></tr>
    <tr><td>Order Number:  </td><td>
	  <input type="text"  value="<?php echo $myrow["license_order_number"]; ?>" name="order" size="20" /></td></tr>
    <tr><td>License Type:  </td><td><select size="1" name="type">
      <?php
	  if ($myrow["license_purchase_type"] == "OEM") echo '<option selected value="OEM">OEM (or System Builder)</option>'; else echo '<option value="OEM">OEM (or System Builder)</option>';
	  if ($myrow["license_purchase_type"] == "Retail") echo '<option selected value="Retail">Retail (Boxware/EDL)</option>'; else echo '<option value="Retail">Retail (Boxware/EDL)</option>';

	  if ($myrow["license_purchase_type"] == "Microsoft 365 Business Plan") echo '<option selected value="Microsoft 365 Business Plan">Microsoft 365 Business Plan</option>'; else echo '<option value="Microsoft 365 Business Plan">Microsoft 365 Business Plan</option>';
	  if ($myrow["license_purchase_type"] == "Microsoft 365 Enterprise Plan") echo '<option selected value="Microsoft 365 Enterprise Plan">Microsoft 365 Enterprise Plan</option>'; else echo '<option value="Microsoft 365 Enterprise Plan">Microsoft 365 Enterprise Plan</option>';
	  if ($myrow["license_purchase_type"] == "Open License") echo '<option selected value="Open License">Open License</option>'; else echo '<option value="Open License">Open License</option>';
	  if ($myrow["license_purchase_type"] == "Open Value") echo '<option selected value="Open Value">Open Value</option>'; else echo '<option value="Open Value">Open Value</option>';
	  if ($myrow["license_purchase_type"] == "Enterprise Agreement") echo '<option selected value="Enterprise Agreement">Enterprise Agreement</option>'; else echo '<option value="Enterprise Agreement">Enterprise Agreement</option>';
	  if ($myrow["license_purchase_type"] == "Select License") echo '<option selected value="Select License">Select (plus) License</option>'; else echo '<option value="Select License">Select (plus) License</option>';
	  if ($myrow["license_purchase_type"] == "VSPremium-MSDN") echo '<option selected value="VSPremium-MSDN">Visual Studio Premium MSDN</option>'; else echo '<option value="VSPremium-MSDN">Visual Studio Premium MSDN</option>';
	  if ($myrow["license_purchase_type"] == "Abonnement") echo '<option selected value="Abonnement">Lizenz-Software-Abonnement</option>'; else echo '<option value="Abonnement">Lizenz-Software-Abonnement</option>';
	  if ($myrow["license_purchase_type"] == "Other") echo '<option selected value="Other">Other</option>'; else echo '<option value="Other">Other</option>';
	  if ($myrow["license_purchase_type"] == "NewCommerceAbo") echo '<option selected value="NewCommerceAbo">Microsoft New Commerce Abo</option>'; else echo '<option value="NewCommerceAbo">Microsoft New Commerce Abo</option>';
	  ?>
    </select></td></tr>
    <tr><td valign="top">Comments: </td><td colspan="2"><textarea rows="4" name="comments" cols="60"><?php echo trim($myrow["license_comments"]); ?></textarea></td></tr>
    <tr><td colspan=2><input name="Submit" value="Lizenz bearbeiten" type="submit" /></td></tr>
  </table>
  <input type="hidden" value="<?php echo $myrow["license_id"]; ?>" name="id" />
  <input type="hidden" value="<?php echo $_GET["id2"]; ?>" name="id2" />
  </form>
  </div>
  <?php
} else {
  echo "<div class=\"main_each\">";
  echo "Please add the Software Package to the register, before attempting to edit a license.";
  echo "</div>";
}
echo "</div>\n";
echo "</td>\n";

echo "</body>\n";
echo "</html>\n";
?>
