<?php
$page = "add_pc";
include "include.php";

echo "<td style=\"vertical-align:top;width:100%\">\n";
echo "<div class=\"main_each\">";

//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
//echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
//echo "<head>\n";
//echo "<title>Open-AudIT</title>\n";
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
//include "include_style.php";
//echo "</head>\n";
//echo "<body>\n";
//echo "<table width=\"100%\" border=\"0\">\n";
//echo "<tr><td colspan=\"3\" class=\"main_each\"><img src=\"images/logo.png\" width=\"300\" height=\"48\" alt=\"\"/></td></tr>\n";
//echo "<tr>\n";
//echo "<td style=\"vertical-align:top;width:100%\">\n";
//echo "<div class=\"main_each\">\n";
echo "<p class=\"contenthead\">".__("Add a PC")."</p>\n";
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
