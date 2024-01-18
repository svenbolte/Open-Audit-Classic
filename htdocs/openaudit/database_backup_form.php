<?php
//    Open Audit Backup Database
//    
include "include.php";

$newline = "\r\n";
$page = "database_backup_form.php";
 

set_time_limit(240);


echo "<td style=\"vertical-align:top;width:100%\">$newline";
echo "<div class=\"main_each\">$newline";
echo "<table class=\"tftable\"  border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" >$newline";

echo "  <tr><td class=\"contenthead\">".__("Backup the Database")."</td></tr>";
echo "  <tr><td colspan=\"1\"><hr /></td></tr>";

echo "<tr><td>".__("Select a database")."</td>";

// Added Drop Tables tick box
if (isset($_POST['add_drop_tables'])) {$add_drop_tables = $_POST['add_drop_tables'];} else { $add_drop_tables = "n";}

echo "<tr><td>".__("Include DROP TABLE IF EXISTS [Table_name] commands in backup file").":&nbsp;</td><td><input type=\"checkbox\" name=\"add_drop_tables\" value=\"y\"";
  if (isset($add_drop_tables) AND $add_drop_tables == "y"){ echo "checked=\"checked\"";}
  echo "/></td>";


//echo "  <tr><td colspan=\"1\"><hr /></td></tr>";
$today = date("dmYGis");
$backup_dir = './backup/';

if (!file_exists($backup_dir)) {
   mkdir($backup_dir) or die(__('Insufficient rights, could not create backup folder, Please create this manually. ') );
}


// Start of Backup Selection section

echo "<form method=\"GET\" action=\"database_backup_sql.php\" name=\"database_backup\">";
echo "<table class=\"tftable\"    class=\"content\">";
//echo "<tr><td colspan=\"1\"><hr /></td></tr>";
echo "<tr>\n";
echo "<td>".__("Database").":</td>\n";
echo "<td><select size=\"1\" name=\"backup_name\" class=\"for_forms\">\n";

$db=GetOpenAuditDbConnection();
$db_list = mysqli_query($db,"SHOW DATABASES");

while ($row = mysqli_fetch_array($db_list)) {
    $my_database_names = $row->Database ;
    // if($mysqli_database==$my_database_names) $selected = "selected"; else $selected = "";
    echo "<OPTION $selected>".$row[0]."</OPTION>\n";
}


echo "<tr><td><input type=\"submit\" value=\"".__("Backup")."\" name=\"submit_button\" /></td></tr>\n";

echo "<tr><td colspan=\"2\"><br>".__("This process can take several minutes")."</td></tr>\n";



//End of Backup Selection  section
echo "</tr>";
// Added Drop Tables tick box 


echo "</td>";


echo "</table>\n";


// // include "include_right_column.php";

echo "</body>\n</html>\n";



?>
