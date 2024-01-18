<?php
$page = "admin";
include "include.php";
echo "<td style=\"vertical-align:top;width:100%\">\n";
echo "<div class=\"main_each\">";
echo "<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n";
echo "<p class=\"contenthead\">".__("Add a PC")."</p>\n";
echo "<table class=\"tftable\" >\n";
echo "<tr><td colspan=\"2\"><textarea rows=\"20\" name=\"add\" cols=\"90\" class=\"for_forms\"></textarea></td></tr>\n";
echo "<tr><td><input name=\"submit\" value=\"".__("Save")."\" type=\"submit\" /></td></tr>\n";
echo "</table>\n";
echo "</form>\n";
echo "</div>\n";
echo "</td>\n";
echo "</body>\n";
echo "</html>\n";
?>
