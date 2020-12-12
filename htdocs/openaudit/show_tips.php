<?php
/**********************************************************************************************************
Module:	show_license.php

Description:
	Displays the Tipps and Versions page from /versions-pwd.txt

Recent Changes:

	[PBA]           20/10/2014
	[Nick Brown]	29/04/2009
	Removed reference to $validate - doesn't appear to be used anywhere in the application.
	
**********************************************************************************************************/

$page = "";
$extra = "";
$software = "";
$count = 0;
$total_rows = 0;
$latest_version = "10.10.14";

// Check for config, otherwise run setup
//@(include_once "include_config.php") OR die(header("Location: setup.php"));  // Modified by Nick Brown - don't want to actually include the file yet
if(!file_exists("include_config.php"))exit(header("Location: setup.php")); // Nick Brown - alternative method
include "include.php";



$software = GetGETOrDefaultValue("software","");
$sort = GetGETOrDefaultValue("sort","system_name");


echo "<td id='CenterColumn'>\n";

// Now show the specified License in an iframe.
if(isset($license_text) AND $license_text!="") {
// We can alter things here if the file doesn't exist or whatever
// currently do nothing
} else {
// If no license specified, use the gpl.txt file. 

$license_text = "../../readme.txt";
// echo "<img src=\"images/gplv3-88x31.png\" alt=\"\" style=\"border:0px;\" width=\"48\" height=\"48\"  />\n";              
}
echo "<center><h4><a href=\"index.php\">Click to close.</a></h4></center>";

echo "<iframe class=\"main_each\" SRC=\"".$license_text."\" width=\"90%\" height=\"600\" framespacing=0 frameborder=no border=0 scrolling=auto name=license_frame longdesc=\"http://www.gnu.org/licenses/licenses.html#GPL\"></iframe>";

echo "<br><center>Versions and Tps&Tricks: (".$license_text.") </center><br>";

if(isset($license_text) AND $license_text="gpl.txt") {
echo "<img src=\"images/gplv3-88x31.png\" alt=\"\" style=\"border:0px;\" width=\"88\" height=\"31\"  />\n";              
// We can alter things here if the file doesn't exist or whatever
// currently do nothing
} else {
 //
}

//gplv3-88x31.png
echo "</td>\n";

// Now put in the RH menu.
// include "include_right_column.php";
?>