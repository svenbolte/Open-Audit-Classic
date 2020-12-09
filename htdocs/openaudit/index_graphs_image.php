<?php
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
//header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
set_time_limit(60);

// Get image variables
$height=$_GET["height"];
$width=$_GET["width"];
$top=$_GET["top"];

header("Content-type: image/png");

$border = 1;

// create image
$image = imagecreatetruecolor ($width, $height);
$white = imagecolorallocate($image, 255, 255, 255);
$orange = imagecolorallocate($image, 0,128, 0);
$edge = imagecolorallocate($image, 192,192,192 );

imagefilledrectangle ($image, 0, 0, $width, $height, $white);
imagecolortransparent($image, $white);

if($height-$top>$border)
{
	imagefilledrectangle ($image, 0 , $top, $width , $height, $edge);
	DrawColorGradient($image, $border, $top + (2 * $border),$width - (2 * $border) -1,$height - $top - $border -1  ,$orange,$white,"v");
}
else
{
	imagefilledrectangle ($image, 0 , $height-$border, $width , $height, $edge);
}
	
imagepng($image);
	

// ****** DrawColorGradient *****************************************************
function DrawColorGradient($im, $x1, $y1, $width, $height, $start_color, $end_color, $direction) 
{
	$start_color = int2rgbarray($start_color);
	$end_color = int2rgbarray($end_color);

	$length = ($direction == "v") ? $height : $width;
	if($length<1) return;
	$color0=($start_color[0]-$end_color[0])/$length;
	$color1=($start_color[1]-$end_color[1])/$length;
	$color2=($start_color[2]-$end_color[2])/$length;
	
	for ($i=0;$i<=$length;$i++) 
	{ 
		$red=$start_color[0]-floor($i*$color0); 
		$green=$start_color[1]-floor($i*$color1); 
		$blue=$start_color[2]-floor($i*$color2); 
		$col= imagecolorallocate($im, $red, $green, $blue);
		if($direction != "v") {imageline($im, $x1+$i, $y1, $x1+$i, $y1+$height, $col);}
		else {imageline($im, $x1, $y1+$i, $x1+$width, $y1+$i, $col);}
	} 
}

// ****** int2rgb *****************************************************
function int2rgbarray($intcolor)
{
  return array(0xFF & ($intcolor >> 0x10), 0xFF & ($intcolor >> 0x8), 0xFF & $intcolor);
}

?>