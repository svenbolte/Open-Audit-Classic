<?php
// importiert softwareversionen CSV Datei in Mysql Tabelle softwareversionen

$JQUERY_UI = array('core','dialog','tooltip');
include_once("include.php");

echo '</tr><tr><td> Datei wird heruntergeladen vom Webserver...und importiert, Status siehe im Kasten links unter dem Menü.';

svversionenimport(1);    // nur maximal 1x pro Minute bei Aufruf aktualisieren

echo '</td></tr></table></body></html>';

?>
