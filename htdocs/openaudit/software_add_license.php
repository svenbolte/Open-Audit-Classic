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
$sql = "SELECT * from software_register WHERE software_reg_id = '" . $_GET["id"] . "'";
$result = mysqli_query($db,$sql);
if ($myrow = mysqli_fetch_array($result)){
  ?>
  <form action="software_add_license_2.php" method="post">
  <table class="content">
    <tr><td class="contenthead">Add Software License for: <?php echo $myrow["software_title"]; ?></td></tr>
  </table><table class="tftable">
    <tr><td width="25%">Date Purchased:  </td><td><input type="text" name="date_purchased" size="20" />&nbsp;(yyyy-mm-dd)</td></tr>
    <tr><td>Number Purchased:  </td><td><input type="text" name="number_purchased" size="20" /> Set to "-1" if this is free</td></tr>
    <tr><td>Vendor:  </td><td><input type="text" name="vendor" size="20" /></td></tr>
    <tr><td><nobr>Cost per License:</nobr></td><td><input type="text" name="cost" size="20" /></td></tr>
    <tr><td>Order Number:  </td><td><input type="text" name="order" size="20" /></td></tr>
    <tr><td>License Type:  </td><td><select size="1" name="type">
      <option value="OEM">OEM (or System Builder)</option>
      <option value="Retail">Retail (Boxware/EDL)</option>
      <option value="Microsoft 365 Business Plan">Microsoft 365 Business Plan</option>
      <option value="Microsoft 365 Enterprise Plan">Microsoft 365 Enterprise Plan</option>
      <option value="Open License">Open License</option>
      <option value="Open Value">Open Value</option>
      <option value="Enterprise Agreement">Enterprise Agreement</option>
      <option value="Select License">Select License</option>
      <option value="VSPremium-MSDN">Visual Studio Premium MSDN</option>
      <option value="Abonnement">Lizenz-Software-Abonnement</option>
      <option value="Other">Other</option>
    </select></td></tr>
    <tr><td valign="top">Comments: </td><td colspan="2"><textarea rows="4" name="comments" cols="60"></textarea></td></tr>
    <tr><td><input name="Submit" value="Submit" type="submit" /></td></tr>
  </table>
  <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="id" />
  </form>
  </div>
  <?php
} else {
  echo "<div class=\"main_each\">";
  echo "Please add the Software Package to the register, before attempting to add a license.";
  echo "</div>";
}
echo "</div>\n";
echo "</td>\n";

echo "</body>\n";
echo "</html>\n";
?>
