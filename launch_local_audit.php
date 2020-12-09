<?php
/*
*
* @version $Id: launch_local_audit.php  24th May 2007
*
* @author The Open Audit Developer Team
* @objective Export Config Page for Open Audit.
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
// Script to send a suitably modified audit.vbs to a web browser, so they can audit their machine based on 
// settings sent by the web host.
$host_name = "";
if (isset($_GET['hostname'])and ($_GET['hostname'] <>"")) {
    $host_name=$_GET['hostname'];
} else {
    $host_name=".";
}


$_REAL_SCRIPT_DIR = realpath(dirname($_SERVER['SCRIPT_FILENAME'])); // filesystem path of this page's directory 
$_REAL_BASE_DIR = realpath(dirname(__FILE__)); // filesystem path of this file's directory 
$_MY_PATH_PART = substr( $_REAL_SCRIPT_DIR, strlen($_REAL_BASE_DIR)); // just the subfolder part between <installation_path> and the page

$INSTALLATION_PATH = $_MY_PATH_PART ? substr( dirname($_SERVER['SCRIPT_NAME']), 0, -strlen($_MY_PATH_PART) ) : dirname($_SERVER['SCRIPT_NAME']);
//
$requesting_host = $_SERVER['REMOTE_ADDR'];

$our_host= "http://".$_SERVER['HTTP_HOST'];
$our_instance = $INSTALLATION_PATH;

$application = "open-audit-of-".$host_name."-to-".$_SERVER['HTTP_HOST']."-from-".$requesting_host.".vbs";

$host_url = $our_host.$our_instance."/list_export_config.php?hostname=".$host_name."&application=".$application;

$application = "open-audit-of-".$host_name."-to-".$_SERVER['HTTP_HOST']."-from-".$requesting_host.".vbs";


// Define the donor script name.
$this_file = "scripts/audit.vbs";

SWITCH($application){
    case "http":
    case "https":
    case "ftp":
        header("Location: ".$application."://".$host_url);
    break;
    default:
        //  Reading the template
        $buffer=file($this_file);
        $buffer=implode("",$buffer);
        
        // Replacing Hostname etc
        // Change the Host URL
        $buffer=str_replace ( "%host_url%", $host_url, $buffer );
        // Change references to audit.vbs to the script name...
        $buffer=str_replace ( "audit.vbs", $application, $buffer );
        
//        $buffer=str_replace ( "\n", "\r\n", $buffer );
                                    

        $file_size = strlen($buffer);
        //Send to Browser
        
        header("Content-type: application/force-download");
        header("Content-Transfer-Encoding: Binary");
        header("Content-length: ".$file_size);
        header("Content-disposition: attachment; filename=\"".$application."\"");
        // Throw the $buffer as the page, as it now contains the script.
        echo $buffer;

    break;

}

?>
