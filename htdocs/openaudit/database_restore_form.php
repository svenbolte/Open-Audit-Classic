<?php
//    Open Audit Restore Database
//    restore over current database. 
include "include.php";
//$this_page="https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; 
//echo "<meta name=\"refresh\" content=\"10;".$this_page."\">";
//
$newline = "\r\n";
$page = "database_restore.php";
$bgcolor = "#FFFFFF";

set_time_limit(240);


echo "<td style=\"vertical-align:top;width:100%\">$newline";
echo "<div class=\"main_each\">$newline";
echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" >$newline";
echo "  <tr><td class=\"contenthead\">".__("Restore the Database")."</td></tr>";
echo "  <tr><td colspan=\"1\"><hr /></td></tr>";
echo "<tr><td>".__("Select a backup")."</td>";
//echo "  <tr><td colspan=\"1\"><hr /></td></tr>";
$today = date("dmYGis");
$backup_dir = './backup/';

if (!file_exists($backup_dir)) {
   mkdir($backup_dir);
} 
 

// Start of restore section

echo "<form method=\"GET\" action=\"database_restore_sql.php\" name=\"database_restore\">";
echo "<table   class=\"content\">";
//echo "<tr><td colspan=\"1\"><hr /></td></tr>";
echo "<tr>\n";
echo "<td>".__("Database").":</td>\n";
echo "<td><select size=\"1\" name=\"backup_name\" class=\"for_forms\">\n";

$handle=opendir($backup_dir);
while ($file = readdir ($handle)) {
    if ($file != "." && $file != "..") {
        if(substr($file,strlen($file)-4)==".sql"){
            if($language == substr($file,0,strlen($file)-4) ) $selected="selected"; else $selected="";
            echo "<option $selected>".substr($file,0,strlen($file)-4)."</option>\n";
        }
    }
}
closedir($handle);
//echo "<tr><td colspan=\"5\"><hr /></td></tr>\n";
echo "<tr><td><input type=\"submit\" value=\"".__("Restore")."\" name=\"submit_button\" /></td></tr>\n";

//End of restore section
echo "</tr></td></table>\n";

// // include "include_right_column.php";

echo "</body>\n</html>\n";



?>
