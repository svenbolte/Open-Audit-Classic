<?php
header("Content-type: image/png");

$scale = '2';
$width = '5';
$height = '100';

$top = ($height - $_GET['disk_percent']) *$scale;

$disk_warning = $_GET['disk_free_warn'] ;

$image = imagecreate($width, ($height *$scale));

// Set "Empty" Colour
$empty = imagecolorallocate( $image, 210, 210, 210 );
// Set "Full" Colour

$full = imagecolorallocate( $image, 156, 190, 222 );

imagefilledrectangle ($image, 0, $top, $width, ($scale *$height), $full);

// Set "Warning" Colour

$warn = imagecolorallocate( $image, 239, 40, 41 );

imagerectangle($image, 0, ((100-$disk_warning)*$scale), $width, (((100-$disk_warning)*$scale) + 1), $warn );

imagepng($image);
?>
