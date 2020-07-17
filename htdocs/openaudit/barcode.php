<?php
#===========================================================================
#= Free software; you can redistribute it and/or modify
#= it under the terms of the GNU General Public License as published by
#= the Free Software Foundation; either version 2 of the License, or
#= (at your option) any later version.
#=
#= phpFile is distributed in the hope that it will be useful,
#= but WITHOUT ANY WARRANTY; without even the implied warranty of
#= MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#= GNU General Public License for more details.
#=
#= You should have received a copy of the GNU General Public License
#= along with DownloadCounter; if not, write to the Free Software
#= Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#===========================================================================
# Produces a barcode as barcode.png
# barcode.php?barcodetext=Baked Beans
# requires code128.class.php 
include_once('./lib/barcode/code128.class.php');
# Also include the openaudit config file, (only actually uses $summary_barcode_font so you could just set this manually to 
# 	something like $summary_barcode_font = './lib/fonts-ttf/LiberationSerif-Bold.ttf';
#
include_once('./include_config.php');
$thisimagename = 'barcode2.png';
unlink($thisimagename);
//
  if (isset($_GET['barcodetext'])) {
// Produce a CODE128 barcode if we have some text. 
  $thistext = ($_GET['barcodetext']);
// 
// 
$thisfont = $summary_barcode_font; 

$barcode = new phpCode128($thistext, 150, $thisfont, 18);
$barcode->setEanStyle(false);
$barcode->setShowText(true);
$barcode->saveBarcode($thisimagename);
// Comment out the next line if you are going to use the generated file elsewhere rather than just showing it inline
echo "<img src='".$thisimagename."'>";

} else {
// Nothing to do if we have no text. 
}
// unlink($thisimagename);

?>