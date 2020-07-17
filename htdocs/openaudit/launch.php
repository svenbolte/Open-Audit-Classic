<?php
include "include_config.php";


if(isset($_GET["application"])) $application=$_GET["application"];
if(isset($_GET["hostname"])) $hostname=$_GET["hostname"];
if(isset($_GET["domain"])) $domain=$_GET["domain"];
if(isset($_GET["ext"])) $ext=$_GET["ext"];

/*
if (ereg(chr(46),$domain)){
    $domain = $domain.".local";
    }
*/



$fqdn=$hostname.".".$domain;
$domain_parts=explode (".",$fqdn);
$domain_parts_count = count($domain_parts);
if ($domain_parts_count == 1){
        $domain_parts[2] = $management_domain_suffix;
}

$fqdn=implode(".",$domain_parts);


SWITCH($application){
    case "http":
    case "https":
    case "ftp":
        header("Location: ".$application."://".$fqdn);
    break;
    default:
        //Reading the template
        //Supports RDP VNC and UltraVNC
        $buffer=file("launch_filedef_".$application.".txt");
        $buffer=implode("",$buffer);
        //Replacing Hostname
        //$fqdn = $hostname.
        $buffer=str_replace ( "NAME", $fqdn, $buffer );

        //Send to Browser
        header("Content-type: application/force-download");
        header("Content-Transfer-Encoding: Binary");
        $filename=$fqdn.".".$ext;

        header("Content-disposition: attachment; filename=\"".$filename."\"");

        echo trim($buffer);
    break;

}

?>
