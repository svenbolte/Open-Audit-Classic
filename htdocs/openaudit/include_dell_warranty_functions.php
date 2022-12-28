<?php
/**
*
* @version $Id: include_dell_warranty_functions.php  15th Jan 2009
*
* @author The Open Audit Developer Team
* @objective Dell Warranty functions for Open-Autit.
* @package open-audit (www.open-audit.org)
* @copyright Copyright (C) open-audit.org All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see ../gpl.txt
* Open-Audit is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See www.open-audit.org for further copyright notices and details.
*
*/ 



/* Test Routine
if(isset($_REQUEST["serial_number"]) AND $_REQUEST["serial_number"]!=""){
//
$this_serial_number = $_REQUEST["serial_number"] ;
//
}else{
//
$this_serial_number = "NULL";
//   
}

$this_dell_warranty_remaining = get_dell_warranty_days( $this_serial_number);

echo "Warranty Remaining ".$this_dell_warranty_remaining." days.";

*/

function get_dell_warranty_days ( $this_serial_number) {
// We assume this is a UK machine, if US, use the second URL, since the US like their dates to be a little strange ;¬) 
//
$this_url="http://support.euro.dell.com/support/topics/topic.aspx/emea/shared/support/my_systems_info/en/details?c=uk&l=en&s=gen&servicetag=";
//$this_url="http://support.dell.com/support/topics/global.aspx/support/my_systems_info/en/details?c=uk&cs=usbsdt1&servicetag=";

// Add the serial number to the URL
$this_url=$this_url.$this_serial_number;

$this_web_page = get_web_page($this_url);
//print_r($this_web_page);
$this_content = $this_web_page[content];


$content=file_get_contents_utf8($this_url,FALSE,NULL,0,20);


// Objective, find the fields on the Dell Warranty page - Description	Provider	Start Date	End Date	Days Left
// Search for the string of the warranty date fields
//
// $this_string is a unique string on the warranty page just before the first date field
// 
// this gives us an offset from the start of the page to look for the first field (Start Date)
// from this, we need to look for the first 'contract_oddrow' characters this will give us the first
// character of the date, but since the date is not fixed length, we then need to read up to the start of the next tag '<'
// Next we look for the start of the next field, read the next date using the same method, and finally look for the days remaining
// which is slightly more complicated as it is either a number at the end of a next field or a zero surrounded by <b> and <red> tags
 

// Search for the string at the start of the date fields
//
$this_string = "DELL</td><td class=";
// $this_string = '\"<td class=\"contract_oddrow\">DELL<\/td><td class=\"contract_oddrow\">';
//Find the start of the data 
$this_warranty_data_pos = stripos($content,$this_string,0);


// define the offset for the start date
//$this_string = '<td class=\"contract_oddrow\">';

$this_string = 'contract_oddrow';
$this_end_string = '<';

$this_warranty_start_date_offset = stripos($content,$this_string,$this_warranty_data_pos) + 17;
$this_warranty_start_date_length = stripos($content,$this_end_string,$this_warranty_start_date_offset)-$this_warranty_start_date_offset;


$this_warranty_end_date_offset = stripos($content,$this_string,$this_warranty_start_date_offset) + 17;
$this_warranty_end_date_length = stripos($content,$this_end_string,$this_warranty_end_date_offset)-$this_warranty_end_date_offset;


$this_warranty_start_date_pos=$this_warranty_start_date_offset;
$this_warranty_end_date_pos=$this_warranty_end_date_offset;


$this_warranty_start_date = substr($content,$this_warranty_start_date_pos,$this_warranty_start_date_length);
$this_warranty_end_date = substr($content,$this_warranty_end_date_pos,$this_warranty_end_date_length);

if(isset($timezone)){
 date_default_timezone_set($timezone);
	} else {
 date_default_timezone_set('Europe/London');
	}
	
	
$warranty_days_left=strtotime($this_warranty_end_date);
$this_date = strtotime("now");



if (is_like_a_date($this_warranty_end_date)){
// 

$date = explode("/",$this_warranty_end_date);
//var_dump(checkdate($date[1], $date[0], $date[2]));


// echo "Warranty End date ".date("D d M Y",mktime(0,0,0,$date[1],$date[0],$date[2]))." ";
// Convert to US style date, (not needed if you use the US web page...)
$this_us_date = mktime(0,0,0,$date[1],$date[0],$date[2]);

$days_left = $this_us_date - $this_date ;
} else {
$days_left = 0;
}
// If we are over the warrenty period we always have zero 
if ($days_left < 0 ) {
$days_left = 0 ; 
}else{
}


// $days_left = get_formated_duration($days_left);
$days_left = get_days_left($days_left);


// echo "Warranty Remaining ".$days_left." days.";
return $days_left;

}

function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);
      return htmlentities($content);
	  // mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

function get_days_left($time_in_sec) {
        $time_day = ceil($time_in_sec/(60*60*24));
        $formated_time = $time_day;
    return $formated_time;
}


/**
 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 * array containing the header fields and content.
 */

function get_web_page( $url )
{
    $options = array( 'http' => array(
        'user_agent'    => 'spider',    // who am i
        'max_redirects' => 10,          // stop after 10 redirects
        'timeout'       => 120,         // timeout on response
    ) );
    $context = stream_context_create( $options );
    $page    = @file_get_contents( $url, false, $context );
 
    $result  = array( );
    if ( $page != false )
        $result['content'] = $page;
    else if ( !isset( $http_response_header ) )
        return null;    // Bad url, timeout

    // Save the header
    $result['header'] = $http_response_header;

    // Get the *last* HTTP status code
    $nLines = count( $http_response_header );
    for ( $i = $nLines-1; $i >= 0; $i-- )
    {
        $line = $http_response_header[$i];
        if ( strncasecmp( "HTTP", $line, 4 ) == 0 )
        {
            $response = explode( ' ', $line );
            $result['http_code'] = $response[1];
            break;
        }
    }
 
    return $result;
}

function is_like_a_date($this_date)
#
{
  $this_result = TRUE;
   // First check to see we only have valid characters
   if(!ereg ("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $this_date))
   {
    $this_result = FALSE;
   }
   else //format is okay, check that days, months, years are okay
   {
     // Further checks... later
   }//end else
   return ($this_result);
} //end function is_us_date
?>