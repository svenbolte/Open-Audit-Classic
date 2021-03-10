<?php
// wake_on_lan.php sends WOL packet to mac address specified in database.
// Accepts hostname, mac and socket number, requires hostname and mac.
include_once("include.php");
$time_start = microtime_float();

$count_system_max="10000";

echo "<td>\n";
$hostname=$_GET["hostname"];
$mac=$_GET["mac"];
if (isset($_GET["socket_number"])) {$socket_number=$_GET["socket_number"];} else {$socket_number="12287";}
// Set the Search Button action
echo "<div class=\"main_each\">";
echo "<form action=\"search.php?sub=no\" method=\"post\">";
echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" class=\"content\">";


$this_error = '';
// Fire off the WOL magic packet.
$result = WakeOnLan($hostname,$mac,$socket_number,$this_error);
// Show the result
$bgcolor = "#F1F1F1";
        echo "<td><img src='images/tv_l.png' width='64' height='64' alt='' /><td><b>".__("Wake on LAN")."</b></td>";
//      Show Hostname
$bgcolor = change_row_color($bgcolor,$bg1,$bg2);
           echo "<tr style=\"background-color:".$bgcolor."\"><td>".__("Hostname").":</td><td>" .$hostname . "</td></tr>";
//      Show Target MAC address
$bgcolor = change_row_color($bgcolor,$bg1,$bg2);
           echo "<tr style=\"background-color:".$bgcolor."\"><td>".__("Mac Address").":</td><td>" .$mac. "</td></tr>";
//      Show Target Socket Number
$bgcolor = change_row_color($bgcolor,$bg1,$bg2);
           echo "<tr style=\"background-color:".$bgcolor."\"><td>".__("Socket Number").":</td><td>" .$socket_number. "</td></tr>";
//      Show Result
$bgcolor = change_row_color($bgcolor,$bg1,$bg2);
           echo "<tr style=\"background-color:".$bgcolor."\"><td>".__("Result").":</td><td>" .$result. "</td></tr>";
$bgcolor = change_row_color($bgcolor,$bg1,$bg2);
           echo "<tr style=\"background-color:".$bgcolor."\"><td>".__("NOTE:  Wake on LAN only works on the local LAN and not between networks.").":</td><td></td></tr>";           
//      Show Target Timing
$bgcolor = change_row_color($bgcolor,$bg1,$bg2);
          echo "<tr style=\"background-color:".$bgcolor."\"><td>".__("This Page was generated in")." ".number_format((microtime_float()-$time_start),2)." ". __("Seconds").".<td></td></td></tr>";
//
//
//echo " Result:".$result ;


echo "</div>\n";


echo "</table>";

echo "</td>\n";

echo "</td>\n";
// include "include_right_column.php";
echo "</body>\n";
echo "</html>\n";


?>
