<?php
// importiert softwareversionen CSV Datei in Mysql Tabelle softwareversionen

$JQUERY_UI = array('core','dialog','tooltip');
include_once("include.php");
$time_start = microtime_float();

echo '</tr><tr><td> Datei wird heruntergeladen vom Webserver...und importiert, Status siehe oben.';

svversionenimport();

echo '</td></tr></table></body></html>';

?>
