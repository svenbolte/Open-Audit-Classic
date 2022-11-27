<?php 
// use headless chrome to take a browser shot and resize it to 420x260px

// Commandline Parameter
if (isset($_GET['resize'])) $resize = ($_GET['resize']); else $resize = .4; // Faktor für Resizing 1=100%
if (isset($_GET['url'])) { $shoturl = ($_GET['url']); } else {
	include_once("include.php");
	$shoturl='https://github.com/svenbolte';
	echo '</tr><tr><td>';
	echo '<h2>Browser-Thumbnail erstellen</h2><form method="get"><p>URL <input type="text" name="url" value="'.$shoturl.'" size="70">';
	echo '</p><p>Size [0.4 ... 2] <input type="number" value="0.4" step="0.2" min="0.4" max="2" name="resize"> <input type="submit" value="Download Thumbnail"></p></form>';
	echo '</td></tr></table></body></html>';
	exit;
}	

$from = array('/[^-a-z0-9.]+/i');
$workfilename = str_replace('https','',strtolower(preg_replace($from,'',$shoturl)));

require __DIR__ . '/headlesschrome.php';
require __DIR__ . '/hdlc-command.php';
use daandesmedt\PHPHeadlessChrome\HeadlessChrome;

$headlessChromer = new HeadlessChrome();
$headlessChromer->setWindowSize(1600, 900);
$headlessChromer->setUrl($shoturl);
$headlessChromer->setBinaryPath('C:\Program Files (x86)\Google\Chrome\Application\chrome');
$headlessChromer->setOutputDirectory(__DIR__);
$headlessChromer->toScreenShot($workfilename.'.png');
// echo 'Screenshot saved to : ' . $headlessChromer->getFilePath();

// PNG nach JPG konvertieren für Thumb
$originalImage = $headlessChromer->getFilePath();
$quality=100; // for jpg good quality
$outputImage = $workfilename.'.jpg';   //for thumbfile save.  
$imageTmp=imagecreatefrompng($originalImage);
imagejpeg($imageTmp, $outputImage, $quality);
imagedestroy($imageTmp);
unlink($headlessChromer->getFilePath());
// Thumb erzeugen
$percent = $resize;
list($width, $height) = getimagesize($outputImage);
$new_width = $width * $percent;
$new_height = $height * $percent;
// Resample
$image_p = imagecreatetruecolor($new_width, $new_height);
$image = imagecreatefromjpeg($outputImage);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

if (empty($listurls)) {
	// Output as browser download
	header('Content-Type: image/jpeg');
	header('Content-Disposition: attachment; filename=' . $outputImage);
	imagejpeg($image_p, null, 96);
	imagedestroy($image_p);
	unlink($outputImage);
} else {	
	// output as jpg file in php folder
	imagejpeg($image_p, '/bshots/'.$outputImage, 96);
}	


