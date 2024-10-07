<?php
/**
* @version $Id: include_LENOVO_warranty_functions.php  15th Jan 2009
*
*/ 

function get_LENOVO_warranty_days ( $this_serial_number) {
	// Add the serial number to the URL
	$this_url = "https://pcsupport.lenovo.com/de/de/products/".$this_serial_number;
	$lenovo_ststem_links = '<a target="_blank" href="'.$this_url.'/warranty">Garantiestatus</a>';
	$lenovo_ststem_links .= ' &nbsp; <a target="_blank" href="'.$this_url.'/downloads/driver-list">Treiber</a>';
	$lenovo_ststem_links .= ' &nbsp; <a target="_blank" href="'.$this_url.'">Systeminfo</a>';
	return $lenovo_ststem_links;
}
?>
