<?php
if ($count_system <> "10000"){
  echo "  <td align=\"right\">\n";
  echo "    <a href=\"" . $_SERVER["PHP_SELF"] . "?page_count=" . $page_prev . "&amp;sort=" . $sort . "\"><img src=\"images/go-prev.png\" alt=\"Previous " . $count_system . " Systems\" title=\"Previous " . $count_system . " Systems\" border=\"0\" width=\"16\" height=\"16\" /></a>&nbsp;&nbsp;&nbsp;\n"; 
  echo "    <a href=\"" . $_SERVER["PHP_SELF"] . "?page_count=0&amp;show_all=1&amp;sort=" . $sort . "\"><img src=\"images/go-all.png\" alt=\"All Systems\" title=\"All Systems\" border=\"0\" width=\"16\" height=\"16\" /></a>&nbsp;&nbsp;&nbsp;\n"; 
  echo "    <a href=\"" . $_SERVER["PHP_SELF"] . "?page_count=" . $page_next . "&amp;sort=" . $sort . "\"><img src=\"images/go-next.png\" alt=\"Next " . $count_system . " Systems\" title=\"Next " . $count_system . " Systems\" border=\"0\" width=\"16\" height=\"16\" /></a>&nbsp;<br />&nbsp;\n  </td>\n"; 
} else {}
?>
