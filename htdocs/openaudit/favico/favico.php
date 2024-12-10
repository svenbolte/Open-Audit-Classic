<?php
// Codeeinheit zum Batch Herunterladen von Favicons aus einer Liste von URLs und Ausgabe als dataURLs in Textdatei

require 'favicondownloader.php';
use Vincepare\FaviconDownloader\FaviconDownloader;

function get_favicon($seitenurl) {
	// Find & download favicon
	$favicon = new FaviconDownloader($seitenurl);
	if (!$favicon->icoExists) {
		echo "<br><br>No favicon for ".$favicon->url;
		die(1);
	}
	echo "<h3>Favicon found : ".$favicon->icoUrl."</h3>";
	// Saving favicon to file
	$filename = dirname(__FILE__).DIRECTORY_SEPARATOR.'favicon-'.time().'.'.$favicon->icoType;
	file_put_contents($filename, $favicon->icoData);
	echo "Saved to ".$filename."<br><br>";
	$fileshort = basename($filename);
	echo "Short filename ".$fileshort."<br><br>";
	echo "Details :<br>";
	$favicon->debug();
	return $fileshort;
}


    function geticongoogle($url, &$info = null) {
        $url = 'https://t3.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url='.$url;
		$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow redirects (302, 301)
        curl_setopt($ch, CURLOPT_MAXREDIRS, 20);         // Follow up to 20 redirects
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
        
        // Don't check SSL certificate to allow autosigned certificate
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $content = curl_exec($ch);
        $info['curl_errno'] = curl_errno($ch);
        $info['curl_error'] = curl_error($ch);
        $info['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $info['effective_url'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $info['redirect_count'] = curl_getinfo($ch, CURLINFO_REDIRECT_COUNT);
        $info['content_type'] = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);
        
        if ($info['curl_errno'] !== CURLE_OK || in_array($info['http_code'], array(403, 404, 500, 503))) {
            return false;
        }
        return $content;
    }


// Main Programm =url=https://sss.de oder array unten füllen--------------------------------------------

echo '<html><head><title>FAVICON herunterladen und als Bild und dataurl speichern</title></head><body>';
echo '<h1>FAVICON URL to IMG and DATA-URL</h1>';
echo '<p>Füllen Sie den Array im PHP-Ciode mit URLs oder geben hinter dieser seite den Parameter ?url=https:/webeite.de an</p>';

$iconout = array();

if (isset($_GET['url'])) {
	// Save image to folder and display it
	$iconfilename = 'favicon-'.time().'-'.str_replace(".","-",parse_url($_GET['url'], PHP_URL_HOST)).'.png';
	file_put_contents($iconfilename, geticongoogle($_GET['url']));
	echo '<br><br>Icon: <img src="'.$iconfilename.'">';
	$iconout[] = $iconfilename;
	// use library to get icon alternately
	//$iconout[] = get_favicon($_GET['url']);
} else {
	// Hier die URLs in den Array eintragen
	$urlarray = array (
		'https://gws.ms',
		'https://tech-nachrichten.de',
	);
	foreach ($urlarray as $singleurl) {
		$iconfilename = 'favicon-'.time().'-'.str_replace(".","-",parse_url($singleurl, PHP_URL_HOST)).'.png';
		file_put_contents($iconfilename, geticongoogle($singleurl));
		echo '<br><br>Icon: <img src="'.$iconfilename.'">';
		$iconout[] = $iconfilename;
		// use library to get icon alternately
		//$iconout[] = get_favicon($singleurl);
	}
}	
	
// Umwandeln in DataURL Strings
require 'imagetourl.php';
use imageToURI\imageToURI;
$images = new imageToURI();
$images->imageToURI( $iconout, '0-output-dataUris.txt', false);

?>
