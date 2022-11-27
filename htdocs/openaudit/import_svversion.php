<?php
// importiert softwareversionen CSV Datei in Mysql Tabelle softwareversionen

$JQUERY_UI = array('core','dialog','tooltip');
include_once("include.php");

echo '</tr><tr><td> Datei wird heruntergeladen vom Webserver...und importiert, Status siehe oben.';

svversionenimport(30);

echo '</td></tr></table></body></html>';

?>
