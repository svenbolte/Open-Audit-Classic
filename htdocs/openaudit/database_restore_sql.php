<?php
set_time_limit(300);
$page = "database_restore_sql";
include "include.php";
echo "<td style=\"vertical-align:top;width:100%\">\n";
echo "<div class=\"main_each\">\n";

//$backup_name = $_GET['backup_name'];
//$filename = "backup/".$backup_name;


if(!(isset($_POST['submit']))){
  $backup_name = $_GET['backup_name'];
  echo "        <form name=\"setup\" action=\"database_restore_sql.php\" method=\"post\" >\n";
  echo "        <table>\n";
  echo "          <tr>\n";
  echo "            <td class=\"contenthead\" colspan=\"2\">".__("Restore Database")."</td>\n";
  echo '          </tr></table><table class="tftable">';
  echo "          <tr>\n";
  echo "            <td colspan=\"2\"><hr /></td>\n";
  echo "          </tr>\n";
  echo "          <tr>\n";
  echo "    </select></td>\n";
  echo "            <td>".__("Restoring backup")." '".$backup_name."'</td></tr>\n";
  echo "            <td>".__("CAUTION: This will restore your backup to the current database ".$mysqli_database." and overwrite the current contents.")."!</td>\n";
  echo "          </tr>\n";
  echo "            <td><input type=\"submit\" name=\"submit\" value=\"".__("Restore")."\" /></td>\n";
  echo "            <td><input type=\"hidden\" name=\"backup_name\" value=\"$backup_name\" /></td>\n";
  echo "          </tr>\n";
  echo "        </table>\n";
  echo "        </form>\n";

} else {

$backup_name = $_POST['backup_name'];
$filename = "./backup/".$backup_name.".sql";

  echo "<table>\n";
  echo '<tr><td class=\"contenthead\">'.__("Restoring the Database").'</td></tr></table><table class="tftable">';
  echo "<tr><td colspan=\"3\">&nbsp;</td></tr>";
  echo "<tr><td class=\"views_tablehead\">".__("Task")."</td><td colspan=\"2\" class=\"views_tablehead\">".__("Restore")."</td></tr>";
  echo "          <tr>\n";
  echo "<tr><td>".__("Opening MySQL Dump")." '".$filename."'</td>\n";

  $handle = fopen($filename, "rb");
  $contents = fread($handle, filesize($filename));
  fclose($handle);
  echo "<td>".__("Done").".</td>\n";
  echo "<td><img src=\"images/button_success.png\" width=\"16\" height=\"16\" /></td>\n";
  echo "</tr>\n";
  //echo "<tr><td>".__("Creating database").".</td>\n";
   $sql = $contents;
  $sql2 = explode(";", $sql);
  echo "<tr><td>".__("Running SQL upload").".</td>\n";
  foreach ($sql2 as $sql3) {
	echo "<tr><td>" . $sql3 . "</td></tr>";
  $result = mysqli_query($db,$sql3 . ";") ;
    if (!$result) {
        echo "</tr><tr><td><font color=\"red\">" . $sql3 . "</font></td></tr><tr><td colspan=\"2\"><h3>MySQL Error:</h3> " . mysqli_error($db) . "</td></tr><tr>\n";
        }
  }
  echo "<td>".__("Done").".</td>\n";
  echo "<td><img src=\"images/button_success.png\" width=\"16\" height=\"16\" /></td>\n";
  echo "</tr>\n";
  echo "<tr><td><br />".__("Click")." <a href=\"index.php\">".__("here")."</a> ".__("to continue").".</td></tr>\n";
  echo "</table>\n";
}

echo "</div>";
echo "</td>\n";
echo "</body>";
echo "</html> ";
?>
