<?php
$application=$_GET["application"];
$hostname=$_GET["hostname"];
$mac=$_GET["mac"];
$ext=$_GET["ext"];

SWITCH($application){
    case "http":
    case "https":
    case "ftp":
        header("Location: ".$application."://".$hostname);
    break;
    default:
        //Reading the template
        $buffer=file("launch_filedef_".$application.".txt");
        $buffer=implode("",$buffer);
        
        //Replacing Hostname
        
        $buffer=str_replace ( "%hostname%", $hostname, $buffer );
        $buffer=str_replace ( "\n", "\r\n", $buffer );
                                    

        //Send to Browser
        
        header("Content-type: application/force-download");
        header("Content-Transfer-Encoding: Binary");
        header("Content-length: ".filesize($file));
        header("Content-disposition: attachment; filename=\"".basename($hostname.".".$ext)."\"");
        echo trim($buffer);
    break;

}

?>
