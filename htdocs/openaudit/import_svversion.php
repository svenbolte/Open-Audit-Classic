<?php
// importiert softwareversionen CSV Datei in Mysql Tabelle softwareversionen

$JQUERY_UI = array('core','dialog','tooltip');
include_once("include.php");
$time_start = microtime_float();
// set an initial 4 min extra timeout
set_time_limit(240000);

echo '</tr><tr><td>';

$filename = dirname(__FILE__).'/wordpresssoftware.csv';
echo $filename;
$flag = true;
$file = fopen($filename, "r");
// Tabelle vorher löschen
$sql_all = "truncate table softwareversionen";
$result_all = mysqli_query($db,$sql_all);

while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
	if($flag) { $flag = false; continue; }
	if (isset($emapData[0])) {
		$emapData[5] = mb_convert_encoding($emapData[5], "HTML-ENTITIES", "UTF-8");
		//iconv( "UTF-8", "latin1Windows-1252",  );
		$sql_all = "INSERT into softwareversionen (sv_datum,sv_rating,sv_id,sv_product,sv_version,sv_bemerkungen,sv_vorinstall,sv_quelle,sv_lizenztyp,sv_lizenzgeber,sv_lizenzbestimmungen,sv_herstellerwebsite)
 values ('$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','$emapData[11]')";
		$result_all = mysqli_query($db,$sql_all);
	}	
}
fclose($file);
echo "<br><br><strong>CSV File has been successfully Imported.</strong></td></tr></table></body></html>";
?>
