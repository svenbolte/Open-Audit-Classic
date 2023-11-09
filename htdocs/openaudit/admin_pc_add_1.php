<?php
$page = "add_pc";
include "include.php";

echo "<td style=\"vertical-align:top;width:100%\">\n";
echo "<div class=\"main_each\">";
echo "<table width=\"100%\"><tr><td class=\"contenthead\">\n";
echo __("Add a PC") . '</td></tr><tr><td>';

echo "<form action=\"admin_pc_add_2.php\" method=\"post\">\n";
echo "<table>\n";
//echo "<tr><td>System Name:&nbsp;</td><td><input type=\"text\" name=\"systemname\" size=\"30\" /></td></tr>\n";
//echo "<tr><td>Status:&nbsp;</td><td><input type=\"text\" name=\"comment\" size=\"30\" /></td></tr>\n";
//echo "<tr><td>UserName:&nbsp;</td><td><input type=\"text\" name=\"user_name\" size=\"30\" /></td></tr>\n";
//echo "<tr><td>UUID:&nbsp;</td><td><input type=\"text\" name=\"uuid\" size=\"30\" /></td></tr>\n";
//echo "<tr><td>Timestamp:&nbsp;</td><td><input type=\"text\" name=\"timestamp\" size=\"30\" /></td></tr>\n";
//echo "<tr><td>Verbose on Submit&nbsp;</td><td><input type=\"text\" name=\"verbose\" size=\"10\"><br />";
//echo "<tr><td>Software Audit&nbsp;</td><td><input type=\"text\" name=\"software_audit\" size=\"10\"><br />";
echo "<tr><td colspan=\"2\"><textarea rows=\"20\" name=\"add\" cols=\"90\" class=\"for_forms\"></textarea></td></tr>\n";
echo "<tr><td colspan=\"2\"><input name=\"submit\" value=\"".__("Save")."\" type=\"submit\" /></td></tr>\n";
echo "</table>\n";
echo "</form>\n";
echo "</div>\n";
echo "<a href=\"javascript:window.close()\" name=\"clicktoclose\"> </a>\n";
echo "</td>\n";
//// include "include_right_column.php";
echo "</table>\n";
echo "</body>\n";
echo "</html>\n";
?>
