<?php
// PHP Tool zum Convertieren von CSV-Dateien mit verschienenen Delimitern nach UTF-8 und EXCEL (xlsx)
// sammelt alle .csv im Ordner "./in" auf und erstellt jeweils eine xlsx Datei im "./out" Ordner

function utf8_converter($array) {
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
            $item = utf8_encode($item);
        }
    }); 
    return $array;
}

function detectDelimiter($csvFile) {
    $delimiters = array(
        ';' => 0,
        ',' => 0,
        "\t" => 0,
        "|" => 0
    );
    $handle = fopen($csvFile, "r");
    $firstLine = fgets($handle);
    fclose($handle); 
    foreach ($delimiters as $delimiter => &$count) {
        $count = count(str_getcsv($firstLine, $delimiter));
    }
    return array_search(max($delimiters), $delimiters);
}

// PHP function to read CSV to array
function csvToArray($csv) {
	$trenner = detectDelimiter($csv);
    // create file handle to read CSV file
    $csvToRead = fopen($csv, 'r');
    // read CSV file using comma as delimiter
    while (! feof($csvToRead)) {
        $csvArray[] = fgetcsv($csvToRead, 1000, $trenner);
    }
    fclose($csvToRead);
    return $csvArray;
}

// Main program
require_once 'simplexlsxgen.php';

include_once("include.php");
echo '</tr><tr><td>';
echo '<h2>Batch: CSV-Dateien nach XLSX konvertieren</h2>';
echo '<p>.csv Dateien in den Ordner openaudit/in legen, dann dieses Skript ausführen, Excel-Dateien aus openaudit/out Ordner entnehmen.</p>';

$xlsdir='./in/';
if ($handle = opendir($xlsdir)) {
	$ctr=0;
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'csv') {
			// do something with $filename
			if ( !is_dir( $xlsdir.'/'.$file ) ) {    // only if folder not exists
				$csvArray = csvToArray($xlsdir.$file);
				// echo '<pre>'; print_r($csvArray); echo '</pre>';
				$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
				$xlsx = Shuchkin\SimpleXLSXGen::fromArray( utf8_converter($csvArray) );
				$xlsx->saveAs('./out/'.$withoutExt.'.xlsx'); // or saveAs('filename.xlsx') or $xlsx_content = (string) $xlsx 

			}	
			$filedate = date('d.m.Y', filemtime($xlsdir.'/'.$file) );
			$ctr++;
			echo '<pre>Konvertiert: #'.$ctr.' &nbsp; '.$file.' &nbsp; '.$withoutExt. '.xlsx &nbsp; '.$filedate.'</pre>';
		}
	}
	closedir($handle);
}

echo '</td></tr></table></body></html>';
?>